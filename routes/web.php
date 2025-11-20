<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\GaleriReportController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ==========================
// HALAMAN UTAMA
// ==========================

// Halaman homepage (bisa diakses langsung tanpa login)
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// ==========================
// MENU UTAMA UNTUK SEMUA PENGUNJUNG (TANPA LOGIN)
// ==========================

// Halaman yang bisa diakses semua pengunjung
Route::get('/public/tentang', function () {
    return view('tentang');
})->name('tentang.public');


Route::get('/public/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.public');

Route::get('/public/galeri', [GaleriController::class, 'publicIndex'])->name('galeri.public');
Route::post('/public/galeri/{id}/view', [GaleriController::class, 'incrementView'])->name('galeri.view');

// User Authentication Routes
Route::get('/user/register', [UserAuthController::class, 'showRegister'])->name('user.register.form');
Route::post('/user/register', [UserAuthController::class, 'register'])->name('user.register');
Route::get('/user/login', [UserAuthController::class, 'showLogin'])->name('user.login.form');
Route::post('/user/login', [UserAuthController::class, 'login'])->name('user.login');
Route::post('/user/logout', [UserAuthController::class, 'logout'])->name('user.logout');

// Galeri Interaction Routes (Require User Login)
Route::post('/public/galeri/{id}/toggle-like', [GaleriController::class, 'toggleLike'])->name('galeri.toggleLike');
Route::post('/public/galeri/{id}/toggle-dislike', [GaleriController::class, 'toggleDislike'])->name('galeri.toggleDislike');
Route::post('/public/galeri/{id}/comment', [GaleriController::class, 'addComment'])->name('galeri.addComment');
Route::get('/public/galeri/{id}/comments', [GaleriController::class, 'getComments'])->name('galeri.getComments');
Route::get('/public/galeri/{id}/download', [GaleriController::class, 'download'])->name('galeri.download');

// Content update routes (admin only)
Route::post('/public/agenda/update', [ContentController::class, 'updateAgenda'])->name('agenda.update');
Route::post('/public/informasi/update', [ContentController::class, 'updateInformasi'])->name('informasi.update');
Route::post('/public/informasi/jurusan/add', [ContentController::class, 'addJurusan'])->name('informasi.jurusan.add');
Route::delete('/public/informasi/jurusan/{id}', [ContentController::class, 'deleteJurusan'])->name('informasi.jurusan.delete');
Route::post('/public/informasi/akreditasi/add', [ContentController::class, 'addAkreditasi'])->name('informasi.akreditasi.add');
Route::delete('/public/informasi/akreditasi/{id}', [ContentController::class, 'deleteAkreditasi'])->name('informasi.akreditasi.delete');

// Galeri routes (admin only)
Route::post('/public/galeri/store', [GaleriController::class, 'store'])->name('galeri.store');
Route::put('/public/galeri/{id}', [GaleriController::class, 'update'])->name('galeri.update');
Route::delete('/public/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');



// ==========================
// ADMIN AUTHENTICATION
// ==========================

// Redirect /admin ke login jika belum login
Route::get('/admin', function () {
    if (session('admin_authenticated')) {
        return redirect('/dashboard');
    }
    return redirect()->route('admin.login');
});

// Redirect login-choice langsung ke halaman login admin
Route::redirect('/login-choice', '/admin/login')->name('login.choice');

// Login form untuk admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'authenticate'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ==========================
// RUTE YANG MEMERLUKAN LOGIN ADMIN
// ==========================

Route::middleware(['admin.auth'])->group(function () {
    // Dashboard setelah login admin
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Halaman ganti password
    Route::get('/admin/change-password', [AdminAuthController::class, 'showChangePasswordForm'])->name('admin.password.change');
    Route::post('/admin/update-password', [AdminAuthController::class, 'updatePassword'])->name('admin.update-password');
    
    // Halaman lain yang memerlukan login
    Route::get('/tentang', function () {
        return view('tentang');
    })->name('tentang');
    
    Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi');
    Route::put('/informasi/update', [App\Http\Controllers\InformasiController::class, 'updateInformasi'])->name('admin.informasi.update');
    Route::put('/prestasi/update', [App\Http\Controllers\InformasiController::class, 'updatePrestasi'])->name('prestasi.update');
    Route::put('/agenda/update', [App\Http\Controllers\InformasiController::class, 'updateAgenda'])->name('admin.agenda.update');
    
    // Admin Users Management
    Route::get('/admin/users', [UserAuthController::class, 'adminIndex'])->name('admin.users');
    
    // Admin panel routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Rute admin galeri untuk mengelola konten
        Route::get('/galeri', [GaleriController::class, 'adminGaleri'])->name('galeri');
        Route::get('/galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
        Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
        Route::get('/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('galeri.edit');
        Route::put('/galeri/{id}', [GaleriController::class, 'update'])->name('galeri.update');
        Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
        
        // Rute admin kategori galeri
        Route::get('/galeri/kategori', [GaleriController::class, 'kategoriIndex'])->name('galeri.kategori');
        Route::post('/galeri/kategori', [GaleriController::class, 'kategoriStore'])->name('galeri.kategori.store');
        Route::put('/galeri/kategori/{id}', [GaleriController::class, 'kategoriUpdate'])->name('galeri.kategori.update');
        Route::delete('/galeri/kategori/{id}', [GaleriController::class, 'kategoriDestroy'])->name('galeri.kategori.destroy');
        
        // Rute laporan statistik galeri
        Route::get('/galeri/report', [GaleriReportController::class, 'index'])->name('galeri.report');
        Route::get('/galeri/report/pdf', [GaleriReportController::class, 'generatePDF'])->name('galeri.report.pdf');
        
        // Rute admin berita untuk mengelola berita
        Route::get('/news', [NewsController::class, 'index'])->name('news');
        
        // Rute admin management
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('kelola-admin');
        Route::get('/admin/create', [App\Http\Controllers\AdminController::class, 'create'])->name('create');
        Route::post('/admin', [App\Http\Controllers\AdminController::class, 'store'])->name('store');
        Route::get('/admin/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('edit');
        Route::put('/admin/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('update');
        Route::delete('/admin/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('destroy');
        Route::post('/admin/{id}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    });
});

// ==========================
// DEBUG ROUTES (Hapus di production!)
// ==========================

// Test database connection
Route::get('/test-db', function() {
    try {
        $pdo = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        
        // Test query
        $result = DB::select('SELECT 1 as test');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database connected successfully!',
            'database' => $dbName,
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'test_query' => $result[0]->test ?? null,
        ], 200);
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed!',
            'error' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'config' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username'),
                'password_set' => !empty(config('database.connections.mysql.password')),
            ],
            'hint' => 'Check Railway dashboard: Database service status, Environment variables (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)',
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unexpected error!',
            'error' => $e->getMessage(),
        ], 500);
    }
})->name('test.db');


