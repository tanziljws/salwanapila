<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriKategori;
use App\Models\GaleriLike;
use App\Models\GaleriComment;
use Illuminate\Support\Facades\Auth;

class GaleriController extends Controller
{
    public function publicIndex()
    {
        try {
            $galeri = Galeri::with(['kategori', 'galeriComments.user'])
                ->where(function($query) {
                    $query->where('gambar', 'not like', '%logo%')
                          ->where('gambar', 'not like', '%jurusan%')
                          ->where('judul', 'not like', '%logo%')
                          ->where('judul', 'not like', '%smk%')
                          ->where('judul', 'not like', '%pplg%')
                          ->where('judul', 'not like', '%tkj%')
                          ->where('judul', 'not like', '%tpfl%')
                          ->where('judul', 'not like', '% to %')
                          ->where('judul', '!=', 'To')
                          ->where('judul', '!=', 'TO');
                })
                ->orderBy('kategori_id', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $kategoris = GaleriKategori::orderBy('nama')->get();
            $user = Auth::guard('web')->user();
            
            return view('galeri', compact('galeri', 'kategoris', 'user'));
        } catch (\Exception $e) {
            \Log::error('Error loading galeri page: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return view with empty data instead of redirecting
            // Use try-catch for each to prevent cascading errors
            try {
                $galeri = collect([]);
            } catch (\Exception $e2) {
                $galeri = collect([]);
            }
            
            try {
                $kategoris = GaleriKategori::orderBy('nama')->get();
            } catch (\Exception $e2) {
                $kategoris = collect([]);
            }
            
            try {
                $user = Auth::guard('web')->user();
            } catch (\Exception $e2) {
                $user = null;
            }
            
            return view('galeri', compact('galeri', 'kategoris', 'user'))->with('error', 'Terjadi kesalahan saat memuat halaman galeri.');
        }
    }

    public function incrementView($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->increment('views');
        $galeri->refresh();
        
        return response()->json(['success' => true, 'views' => $galeri->views]);
    }

    public function like($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->incrementLikes();
        
        return response()->json(['success' => true, 'likes' => $galeri->likes]);
    }

    public function dislike($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->incrementDislikes();
        
        return response()->json(['success' => true, 'dislikes' => $galeri->dislikes]);
    }

    // Admin view galeri (khusus untuk admin panel)
    public function adminGaleri()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        try {
            $galeriUmum = Galeri::with(['kategori', 'galeriComments'])
                ->where(function($query) {
                    $query->where('gambar', 'not like', '%logo%')
                          ->where('gambar', 'not like', '%jurusan%')
                          ->where('judul', 'not like', '%logo%')
                          ->where('judul', 'not like', '%smk%')
                          ->where('judul', 'not like', '%pplg%')
                          ->where('judul', 'not like', '%tkj%')
                          ->where('judul', 'not like', '%tpfl%')
                          ->where('judul', 'not like', '% to %')
                          ->where('judul', '!=', 'To')
                          ->where('judul', '!=', 'TO');
                })
                ->orderBy('created_at', 'desc')
                ->get();
            return view('admin.galeri.index', compact('galeriUmum'));
        } catch (\Exception $e) {
            \Log::error('Error loading admin galeri page: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memuat halaman galeri admin.');
        }
    }

    public function index()
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu untuk mengakses halaman galeri admin.');
        }

        $galeri = Galeri::with('kategori')
            ->where(function($query) {
                $query->where('gambar', 'not like', '%logo%')
                      ->where('gambar', 'not like', '%jurusan%')
                      ->where('judul', 'not like', '%logo%')
                      ->where('judul', 'not like', '%smk%')
                      ->where('judul', 'not like', '%pplg%')
                      ->where('judul', 'not like', '%tkj%')
                      ->where('judul', 'not like', '%tpfl%')
                      ->where('judul', 'not like', '% to %')
                      ->where('judul', '!=', 'To')
                      ->where('judul', '!=', 'TO');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $kategoris = GaleriKategori::orderBy('nama')->get();
        
        // Pisahkan foto gedung dan galeri umum berdasarkan type
        $fotoGedung = $galeri->where('type', 'gedung');
        $galeriUmum = $galeri->where('type', 'galeri');
        
        return view('admin.galeri.index', compact('galeri', 'kategoris', 'fotoGedung', 'galeriUmum'));
    }

    public function create()
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu untuk membuat galeri baru.');
        }

        $kategoris = GaleriKategori::orderBy('nama')->get();
        return view('admin.galeri.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'kategori_id' => 'required|exists:galeri_kategoris,id',
        ]);

        $imageName = time().'.'.$request->gambar->extension();
        $request->gambar->move(public_path('images'), $imageName);

        Galeri::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => 'images/' . $imageName,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('admin.galeri')->with('success', 'Foto berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu untuk mengedit galeri.');
        }

        $galeri = Galeri::findOrFail($id);
        $kategoris = GaleriKategori::orderBy('nama')->get();
        return view('admin.galeri.edit', compact('galeri', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'kategori_id' => 'required|exists:galeri_kategoris,id',
        ]);

        $galeri = Galeri::findOrFail($id);
        $galeri->judul = $request->judul;
        $galeri->deskripsi = $request->deskripsi;
        $galeri->kategori_id = $request->kategori_id;

        if ($request->hasFile('gambar')) {
            $imageName = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('images'), $imageName);
            $galeri->gambar = 'images/' . $imageName;
        }

        $galeri->save();

        return redirect()->route('admin.galeri')->with('success', 'Foto berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hanya admin yang bisa akses
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $galeri = Galeri::findOrFail($id);

        // Hapus file gambar jika perlu
        if ($galeri->gambar && file_exists(public_path($galeri->gambar))) {
            unlink(public_path($galeri->gambar));
        }

        $galeri->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus!');
    }

    // Kategori Management
    public function kategoriIndex()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak!');
        }

        $kategoris = GaleriKategori::orderBy('nama')->get();
        return view('admin.galeri.kategori', compact('kategoris'));
    }

    public function kategoriStore(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|string',
            'icon' => 'required|string',
        ]);

        GaleriKategori::create($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|string',
            'icon' => 'required|string',
        ]);

        $kategori = GaleriKategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function kategoriDestroy($id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $kategori = GaleriKategori::findOrFail($id);
        
        // Update galeri yang menggunakan kategori ini
        Galeri::where('kategori_id', $id)->update(['kategori_id' => null]);
        
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    // Toggle like/dislike dengan authentication
    public function toggleLike(Request $request, $id)
    {
        // Check if user is logged in (admin or regular user)
        if (!Auth::guard('web')->check() && !session('admin_authenticated')) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu!'], 401);
        }

        $galeri = Galeri::findOrFail($id);
        
        // Use admin ID if admin is logged in, otherwise use user ID
        if (session('admin_authenticated')) {
            $userId = 1; // Admin user ID
        } else {
            $user = Auth::guard('web')->user();
            $userId = $user->id;
        }
        
        $existingLike = $galeri->galeriLikes()->where('user_id', $userId)->first();
        
        if ($existingLike) {
            if ($existingLike->type === 'like') {
                // Remove like
                $existingLike->delete();
                $galeri->decrement('likes');
            } else {
                // Change from dislike to like
                $existingLike->update(['type' => 'like']);
                $galeri->decrement('dislikes');
                $galeri->increment('likes');
            }
        } else {
            // Add new like
            $galeri->galeriLikes()->create([
                'user_id' => $userId,
                'type' => 'like'
            ]);
            $galeri->increment('likes');
        }
        
        return response()->json([
            'success' => true,
            'likes' => $galeri->fresh()->likes,
            'dislikes' => $galeri->fresh()->dislikes,
            'userLiked' => $galeri->fresh()->isLikedBy($userId),
            'userDisliked' => $galeri->fresh()->isDislikedBy($userId)
        ]);
    }

    public function toggleDislike(Request $request, $id)
    {
        // Check if user is logged in (admin or regular user)
        if (!Auth::guard('web')->check() && !session('admin_authenticated')) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu!'], 401);
        }

        $galeri = Galeri::findOrFail($id);
        
        // Use admin ID if admin is logged in, otherwise use user ID
        if (session('admin_authenticated')) {
            $userId = 1; // Admin user ID
        } else {
            $user = Auth::guard('web')->user();
            $userId = $user->id;
        }
        
        $existingLike = $galeri->galeriLikes()->where('user_id', $userId)->first();
        
        if ($existingLike) {
            if ($existingLike->type === 'dislike') {
                // Remove dislike
                $existingLike->delete();
                $galeri->decrement('dislikes');
            } else {
                // Change from like to dislike
                $existingLike->update(['type' => 'dislike']);
                $galeri->decrement('likes');
                $galeri->increment('dislikes');
            }
        } else {
            // Add new dislike
            $galeri->galeriLikes()->create([
                'user_id' => $userId,
                'type' => 'dislike'
            ]);
            $galeri->increment('dislikes');
        }
        
        return response()->json([
            'success' => true,
            'likes' => $galeri->fresh()->likes,
            'dislikes' => $galeri->fresh()->dislikes,
            'userLiked' => $galeri->fresh()->isLikedBy($userId),
            'userDisliked' => $galeri->fresh()->isDislikedBy($userId)
        ]);
    }

    public function addComment(Request $request, $id)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu!'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $galeri = Galeri::findOrFail($id);
        $user = Auth::guard('web')->user();
        
        $comment = $galeri->galeriComments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);
        
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => $user->name,
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }

    public function getComments($id)
    {
        $galeri = Galeri::findOrFail($id);
        $comments = $galeri->galeriComments;
        
        return response()->json([
            'success' => true,
            'comments' => $comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->diffForHumans()
                ];
            })
        ]);
    }

    public function download($id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu untuk download!');
        }

        $galeri = Galeri::findOrFail($id);
        $filePath = public_path($galeri->gambar);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }
        
        return response()->download($filePath);
    }
}
