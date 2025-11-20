<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Check if already logged in as admin
        if (session('admin_authenticated')) {
            return redirect('/dashboard');
        }
        
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        try {
            // Check admin status before attempting login
            $admin = Admin::where('username', $credentials['username'])->first();
            
            if ($admin && !$admin->is_active) {
                return back()->withErrors([
                    'username' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ])->withInput($request->only('username'));
            }

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                
                // Update last login
                $admin = Auth::guard('admin')->user();
                $admin->update(['last_login' => now()]);
                
                // Set session data untuk admin
                $request->session()->put('admin_id', Auth::guard('admin')->id());
                $request->session()->put('admin_name', Auth::guard('admin')->user()->username);
                $request->session()->put('admin_authenticated', true);
                
                return redirect()->intended('/dashboard')->with('success', 'Selamat datang, Admin! Berikut adalah statistik sekolah dan aksi cepat untuk mengelola konten.');
            }

            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->withInput($request->only('username'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Database connection error
            \Log::error('Database connection error during admin login: ' . $e->getMessage());
            
            return back()->withErrors([
                'username' => 'Sistem sedang mengalami masalah koneksi database. Silakan cek konfigurasi database di Railway atau hubungi administrator.',
            ])->withInput($request->only('username'));
        } catch (\Exception $e) {
            \Log::error('Unexpected error during admin login: ' . $e->getMessage());
            
            return back()->withErrors([
                'username' => 'Terjadi kesalahan saat proses login. Silakan coba lagi.',
            ])->withInput($request->only('username'));
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Hapus semua data session yang terkait admin
        $request->session()->forget(['admin_id', 'admin_name', 'admin_authenticated']);
        
        return redirect('/')->with('success', 'Anda berhasil logout. Silakan login kembali untuk mengakses fitur admin.');
    }

    /**
     * Menampilkan form ganti password
     */
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    /**
     * Memproses perubahan password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
        ], [
            'new_password.regex' => 'Password harus mengandung setidaknya satu huruf dan satu angka',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password.min' => 'Password minimal 8 karakter',
        ]);

        $admin = Auth::guard('admin')->user();

        // Verifikasi password saat ini
        if (!password_verify($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah'])->withInput();
        }

        // Update password baru
        $admin->password = bcrypt($request->new_password);
        $admin->save();

        return redirect()->route('admin.password.change')
            ->with('success', 'Password berhasil diubah!');
    }
}
