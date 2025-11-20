<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        session([
            'register_captcha_a' => $a,
            'register_captcha_b' => $b,
            'register_captcha_answer' => $a + $b,
        ]);
        return view('user.register', ['captchaA' => $a, 'captchaB' => $b]);
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nisn' => 'required|string|size:10|unique:users',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => 'required|numeric',
        ]);

        // Verify captcha
        if ((int)$request->input('captcha') !== (int)session('register_captcha_answer')) {
            // regenerate captcha for next attempt
            $a = random_int(1, 9);
            $b = random_int(1, 9);
            session([
                'register_captcha_a' => $a,
                'register_captcha_b' => $b,
                'register_captcha_answer' => $a + $b,
            ]);
            return back()->withErrors(['captcha' => 'Captcha is incorrect. Please try again.'])->withInput();
        }

        // Upload foto
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('uploads/users'), $photoName);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'photo' => $photoName ? 'uploads/users/' . $photoName : null,
            'password' => Hash::make($request->password),
        ]);

        // clear captcha session
        $request->session()->forget(['register_captcha_a','register_captcha_b','register_captcha_answer']);

        Auth::guard('web')->login($user);

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }

    // Show login form
    public function showLogin()
    {
        return view('user.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::guard('web')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/')->with('success', 'Login berhasil!');
            }

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        } catch (\Illuminate\Database\QueryException $e) {
            // Database connection error
            \Log::error('Database connection error during login: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Sistem sedang mengalami masalah koneksi database. Silakan coba lagi beberapa saat lagi atau hubungi administrator.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
            \Log::error('Unexpected error during login: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat proses login. Silakan coba lagi.',
            ])->onlyInput('email');
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout berhasil!');
    }

    // Admin: View all registered users
    public function adminIndex()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }
}
