@extends(session('admin_authenticated') ? 'layouts.admin' : 'layouts.public')

@section('content')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

<style>
    /* Consistent with Homepage Theme */
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-light: #3b82f6;
        --secondary-color: #64748b;
        --accent-color: #7c3aed;
        --success-color: #059669;
        --warning-color: #d97706;
        --danger-color: #dc2626;
        --light-color: #f8fafc;
        --dark-color: #0f172a;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-radius: 12px;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        
        /* Typography */
        --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --font-heading: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        
        /* Spacing Scale */
        --space-xs: 0.25rem;
        --space-sm: 0.5rem;
        --space-md: 1rem;
        --space-lg: 1.5rem;
        --space-xl: 2rem;
        --space-2xl: 3rem;
        --space-3xl: 4rem;
    }

    .galeri-page {
        background: var(--light-color);
        min-height: 100vh;
        padding: var(--space-2xl) 0;
        font-family: var(--font-primary);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Typography Hierarchy */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
        font-weight: 600;
        line-height: 1.2;
        color: var(--dark-color);
        margin-bottom: var(--space-md);
    }
    
    h1 { font-size: 2.5rem; font-weight: 700; }
    h2 { font-size: 2rem; font-weight: 600; }
    h3 { font-size: 1.75rem; font-weight: 600; }
    h4 { font-size: 1.5rem; font-weight: 500; }
    h5 { font-size: 1.25rem; font-weight: 500; }
    h6 { font-size: 1.125rem; font-weight: 500; }
    
    p {
        margin-bottom: var(--space-md);
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* Scroll Animation */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .page-header {
        background: white;
        border-radius: var(--border-radius);
        padding: var(--space-2xl) var(--space-xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow);
        text-align: center;
        border: 1px solid rgba(30, 41, 59, 0.05);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-color);
    }

    .page-title {
        font-family: var(--font-heading) !important;
        font-size: 2.8rem !important;
        font-weight: 800 !important;
        color: var(--text-primary) !important;
        margin-bottom: var(--space-sm) !important;
        letter-spacing: -0.025em !important;
        text-align: center !important;
        position: relative !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        line-height: 1.1 !important;
    }
    
    .page-title::after {
        content: '';
        display: block;
        width: 120px;
        height: 5px;
        background: var(--primary-color);
        margin: 1.5rem auto 0;
        border-radius: 3px;
    }

    .page-subtitle {
        font-family: var(--font-primary) !important;
        font-size: 1.4rem !important;
        color: var(--text-secondary) !important;
        margin-bottom: var(--space-xl) !important;
        font-weight: 500 !important;
        text-align: center !important;
        letter-spacing: 0.3px !important;
        line-height: 1.4 !important;
    }

    .galeri-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(44, 62, 80, 0.05);
        transition: var(--transition);
        min-width: 120px;
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-actions {
        margin-bottom: 3rem;
    }

    .admin-button {
        background: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        color: white;
        transition: var(--transition);
        box-shadow: 0 8px 25px rgba(0, 102, 204, 0.25);
        font-size: 1.1rem;
    }

    .admin-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 102, 204, 0.35);
        background: var(--primary-dark);
        color: white;
    }

    
    /* Fix untuk grid alignment - pastikan semua card sama tinggi */
    .row.scroll-animate > [class*='col-'] {
        display: flex;
    }
    
    .galeri-card {
        height: 100% !important;
    }
    .galeri-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid rgba(44, 62, 80, 0.06);
        position: relative;
        display: flex;
        flex-direction: column;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        height: 100%;
    }
    
    /* Pastikan kolom juga flex untuk alignment */
    .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }

    .galeri-image {
        position: relative;
        overflow: hidden;
        background: #f3f4f6;
        height: 250px; /* same as homepage gallery */
    }

    .galeri-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* fill box uniformly */
        background: transparent;
        transition: var(--transition);
        display: block;
        cursor: pointer;
    }

    .galeri-card:hover .galeri-image img {
        transform: scale(1.05);
    }

    .galeri-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 102, 204, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .galeri-card:hover .galeri-overlay {
        opacity: 1;
    }

    .galeri-actions {
        display: flex;
        gap: 0.5rem;
    }

    .galeri-actions .btn {
        border-radius: 50px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .galeri-content {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .galeri-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .galeri-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .galeri-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .galeri-meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .alert {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: var(--shadow);
        border-left: 4px solid var(--primary-color);
        margin-bottom: 2rem;
    }

    .alert-success {
        border-left-color: var(--success-color);
    }

    .alert-danger {
        border-left-color: var(--danger-color);
    }

    .category-filter {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow);
        border: 1px solid rgba(44, 62, 80, 0.05);
    }

    .filter-buttons {
        gap: 0.75rem;
    }

    .filter-btn {
        border-radius: 50px;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        transition: var(--transition);
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: white;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .filter-btn.active {
        background: var(--primary-color);
        color: white !important;
        border-color: var(--primary-color);
    }

    /* Dropdown styling */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 12px;
        padding: 0.5rem 0;
        min-width: 200px;
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        transition: var(--transition);
        border-radius: 8px;
        margin: 0 0.5rem;
    }

    .dropdown-item:hover {
        background-color: var(--primary-color);
        color: white !important;
        transform: translateX(5px);
    }

    .dropdown-item.active {
        background-color: var(--primary-color);
        color: white !important;
    }

    .dropdown-item i {
        width: 16px;
        text-align: center;
    }

    /* Secondary accent (yellow) for small highlights */
    .galeri-meta i { color: var(--warning-color); }

    .galeri-card[data-category] {
        transition: var(--transition);
    }

    .galeri-card.hidden {
        display: none !important;
    }

    /* Prestasi Siswa Section Styling */
    .prestasi-section {
        margin-top: var(--space-2xl);
    }

    .badge {
        font-family: var(--font-primary);
        font-weight: 500;
        padding: var(--space-sm) var(--space-md);
        border-radius: 50px;
        font-size: 0.85rem;
    }

    /* Ensure proper grid layout */
    .row.g-4.scroll-animate {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.75rem;
    }

    .row.g-4.scroll-animate > [class*="col-"] {
        padding: 0 0.75rem;
        margin-bottom: 1.5rem;
    }

    /* Smooth filtering animation */
    
    /* Fix untuk grid alignment - pastikan semua card sama tinggi */
    .row.scroll-animate > [class*='col-'] {
        display: flex;
    }
    
    .galeri-card {
        height: 100% !important;
    }
    .galeri-card {
        transition: opacity 0.3s ease, transform 0.3s ease, display 0.3s ease;
    }

    .category-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        z-index: 2;
    }

    /* Make columns same height so cards align neatly */
    .row.g-4 > .col-md-6,
    .row.g-4 > .col-lg-4 {
        display: flex;
    }

    .row.g-4 > .col-md-6 > .galeri-card,
    .row.g-4 > .col-lg-4 > 
    /* Fix untuk grid alignment - pastikan semua card sama tinggi */
    .row.scroll-animate > [class*='col-'] {
        display: flex;
    }
    
    .galeri-card {
        height: 100% !important;
    }
    .galeri-card {
        width: 100%;
    }

    /* Ensure grid starts from the left and spacing is consistent */
    /* Responsive auto-fit grid to avoid large empty space on the right */
    .row.g-4 > [class^='col-'],
    .row.g-4 > [class*=' col-'] {
        width: 100% !important; /* let CSS grid control widths */
        display: flex;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
        }

        .galeri-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .stat-item {
            min-width: auto;
        }
    }
    



    /* Responsive Design */
    @media (max-width: 1024px) {
        .page-header {
            padding: var(--space-xl) var(--space-lg);
        }
        
        .page-title {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .galeri-page {
            padding: var(--space-lg) 0;
        }
        
        .page-title {
            font-size: 2rem;
        }

        .page-header {
            padding: var(--space-xl) var(--space-lg);
        }
        
        .galeri-stats {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .stat-item {
            min-width: auto;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.75rem;
        }
        
        .page-header {
            padding: var(--space-lg) var(--space-md);
        }
        
        .page-subtitle {
            font-size: 1.2rem !important;
        }
    }

    /* Fix untuk card yang terpotong */
    .card.h-100 {
        height: 100% !important;
        display: flex;
        flex-direction: column;
    }
    
    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Pastikan row tidak overflow */
    .row.scroll-animate {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .row.scroll-animate > [class*='col-'] {
        padding-left: 12px;
        padding-right: 12px;
        margin-bottom: 24px;
    }

    /* CSS khusus untuk admin view */
    body.admin-layout .galeri-page {
        padding: 0 !important;
        background: transparent !important;
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    body.admin-layout .main-content {
        margin-left: 220px !important;
        padding: 20px !important;
        width: calc(100% - 220px) !important;
    }
    
    body.admin-layout .container {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Fix untuk card di admin panel */
    .card.galeri-card {
        min-height: 400px;
    }
    
    .card.galeri-card .card-img-top {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }

    /* FINAL FIX - Untuk tampilan di admin panel */
    @media (min-width: 768px) {
        .main-content .container {
            max-width: 100% !important;
        }
        
        .main-content .row {
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .main-content .row > [class*='col-'] {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
    
    /* Pastikan card tidak overflow */
    .col-md-3 {
        margin-bottom: 2rem;
    }
    /* Admin Panel Specific Styles */
    body.admin-layout .galeri-page {
        padding: 0 !important;
        background: transparent !important;
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    body.admin-layout .main-content {
        margin-left: 220px !important;
        padding: 20px !important;
        width: calc(100% - 220px) !important;
    }
    
    body.admin-layout .page-header {
        background: white;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    body.admin-layout .page-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    body.admin-layout .page-subtitle {
        font-size: 0.9rem;
    }
    
    body.admin-layout .container {
        max-width: 100%;
        padding: 0;
    }
    
    body.admin-layout .galeri-card {
        height: auto !important;
        min-height: auto !important;
    }
    
    body.admin-layout .card-img-top {
        height: 200px !important;
    }
    
    /* FIX untuk Admin Panel - Hindari bentrok dengan sidebar */
    @if(session('admin_authenticated'))
    .galeri-page {
        padding: 1rem 0 !important;
        background: #f8f9fa !important;
    }
    
    .page-header {
        background: white !important;
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        border-radius: 8px !important;
    }
    
    .page-title {
        font-size: 1.75rem !important;
    }
    
    .container {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .row.scroll-animate {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .row.scroll-animate > [class*='col-'] {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    
    .galeri-card {
        margin-bottom: 1.5rem !important;
    }
    
    .admin-actions {
        margin-bottom: 1.5rem !important;
    }
    @endif
</style>

<div class="galeri-page">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header scroll-animate">
            <div class="text-center mb-3">
                <h1 class="page-title">📸 Galeri Foto</h1>
                <p class="page-subtitle">Kumpulan foto kegiatan dan prestasi SMK Negeri 4 Kota Bogor</p>
            </div>
            <div class="galeri-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $galeri->count() }}</div>
                    <div class="stat-label">Total Foto</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $kategoris->count() }}</div>
                    <div class="stat-label">Kategori</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2025</div>
                    <div class="stat-label">Tahun Aktif</div>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="category-filter mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="filter-buttons d-flex flex-wrap justify-content-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle filter-btn active" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-th-large me-2"></i>Kategori
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                    <li><a class="dropdown-item filter-option active" href="#" data-category="all">
                                        <i class="fas fa-th-large me-2"></i>Semua Kategori
                                    </a></li>
                                    @foreach($kategoris as $kategori)
                                    <li><a class="dropdown-item filter-option" href="#" data-category="{{ $kategori->id }}" style="color: {{ $kategori->warna }};">
                                        <i class="{{ $kategori->icon }} me-2"></i>{{ $kategori->nama }}
                                    </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        @if(session('admin_authenticated'))
        <div class="admin-actions">
            <div class="container">
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn admin-button" data-bs-toggle="modal" data-bs-target="#galeriManagerModal">
                        <i class="fas fa-cogs me-2"></i>Kelola Galeri Sekolah
                    </button>
                    <a href="{{ route('admin.galeri.kategori') }}" class="btn btn-outline-primary">
                        <i class="fas fa-tags me-2"></i>Kelola Kategori
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        <div class="container">
            <div class="row scroll-animate">
                @if($galeri->count() > 0)
                    @foreach($galeri as $item)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 galeri-card" data-category="{{ $item->kategori_id ?? 'all' }}">
                                <div class="position-relative">
                                    @php
                                        $imagePath = $item->gambar;
                                        // Pastikan path dimulai dengan 'images/'
                                        if (!Str::startsWith($imagePath, 'images/')) {
                                            $imagePath = 'images/' . ltrim($imagePath, '/');
                                        }
                                        // Pastikan file ada
                                        $imageExists = file_exists(public_path($imagePath));
                                    @endphp
                                    <a href="{{ asset($imagePath) }}?v={{ optional($item->updated_at)->timestamp ?? time() }}" class="glightbox" data-gallery="gallery" data-title="{{ $item->judul }}" data-description="{{ $item->deskripsi }}" data-photo-id="{{ $item->id }}">
                                        <img src="{{ $imageExists ? asset($imagePath) : asset('images/placeholder.jpg') }}?v={{ optional($item->updated_at)->timestamp ?? time() }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 250px; object-fit: cover;" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">
                                    </a>
                                    @if($item->views > 0)
                                    <div class="position-absolute top-0 start-0 p-2">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-eye me-1"></i>{{ $item->views }} views
                                        </span>
                                    </div>
                                    @endif
                                    @if(session('admin_authenticated'))
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <a href="{{ route('admin.galeri.edit', $item->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    @if($item->deskripsi)
                                        <p class="card-text">{{ Str::limit($item->deskripsi, 80) }}</p>
                                    @endif
                                    <div class="galeri-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $item->created_at->format('d M Y') }}
                                        </small>
                                        @if($item->kategori)
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $item->kategori->nama }}
                                        </small>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2 mt-3 justify-content-center flex-wrap">
                                        <button class="btn btn-sm btn-outline-success like-btn" data-photo-id="{{ $item->id }}" onclick="toggleLike({{ $item->id }})" title="Like foto ini">
                                            <i class="fas fa-thumbs-up me-1"></i>
                                            <span class="like-count">{{ $item->likes ?? 0 }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger dislike-btn" data-photo-id="{{ $item->id }}" onclick="toggleDislike({{ $item->id }})" title="Dislike foto ini">
                                            <i class="fas fa-thumbs-down me-1"></i>
                                            <span class="dislike-count">{{ $item->dislikes ?? 0 }}</span>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadPhoto({{ $item->id }})" title="Download foto ini">
                                            <i class="fas fa-download me-1"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="showComments({{ $item->id }})" title="Lihat & tulis komentar">
                                            <i class="fas fa-comments me-1"></i>
                                            <span class="comment-count">{{ $item->galeriComments->count() }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Logo SMK dan Foto Jurusan tidak ditampilkan di galeri publik -->
            </div>
        </div>

        @if($galeri->count() == 0)
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-images fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada foto di galeri</h4>
                    <p class="text-muted">Admin dapat menambahkan foto melalui tombol "Kelola Galeri Sekolah"</p>
                </div>
            </div>
        @endif

    
    @if(!session('admin_authenticated'))
    <div class="text-center mt-5 mb-4">
        <a href="/" class="btn btn-primary btn-lg">
            <i class="fas fa-home me-2"></i>Kembali ke Beranda
        </a>
    </div>
    @endif
</div>
    
</div>

<!-- Modal View Image -->
<div class="modal fade" id="viewImageModal" tabindex="-1" aria-labelledby="viewImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewImageModalLabel">View Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Galeri Manager -->
@if(session('admin_authenticated'))
<div class="modal fade" id="galeriManagerModal" tabindex="-1" aria-labelledby="galeriManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galeriManagerModalLabel">Kelola Galeri Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="galeriTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="true">
                            <i class="fas fa-upload me-2"></i>Upload Foto Baru
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage" type="button" role="tab" aria-controls="manage" aria-selected="false">
                            <i class="fas fa-edit me-2"></i>Kelola Foto
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="galeriTabsContent">
                    <!-- Upload Tab -->
                    <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                        <div class="p-4">
                            <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Foto</label>
                                    <input type="text" class="form-control" id="judul" name="judul" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Pilih Foto</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload Foto</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Manage Tab -->
                    <div class="tab-pane fade" id="manage" role="tabpanel" aria-labelledby="manage-tab">
                        <div class="p-4">
                            <div class="row g-3">
                                @foreach($galeri as $item)
                                <div class="col-md-6">
                                    <div class="card">
                                        <img src="{{ asset($item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $item->judul }}</h6>
                                            <p class="card-text small">{{ Str::limit($item->deskripsi, 50) }}</p>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-warning btn-sm" onclick="editFoto({{ $item->id }}, `{{ addslashes($item->judul) }}` , `{{ addslashes($item->deskripsi ?? '') }}` , {{ $item->kategori_id ?? 'null' }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('galeri.destroy', $item->id) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button class="btn btn-danger btn-sm" onclick="deleteFoto({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Edit Foto -->
@if(session('admin_authenticated'))
<div class="modal fade" id="editFotoModal" tabindex="-1" aria-labelledby="editFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFotoModalLabel">Edit Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFotoForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control" id="edit_kategori_id" name="kategori_id">
                            <option value="">Pilih Kategori (Opsional)</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto (opsional)</label>
                        <input type="file" class="form-control" name="gambar" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endif

<script>
function viewImage(src, title) {
    document.getElementById('modalImage').src = src;
    document.getElementById('modalImage').alt = title;
    document.getElementById('viewImageModalLabel').textContent = title;
    new bootstrap.Modal(document.getElementById('viewImageModal')).show();
}

function editFoto(id, judul, deskripsi, kategori_id) {
    const form = document.getElementById('editFotoForm');
    form.action = `/public/galeri/${id}`; // matches route('galeri.update')
    document.getElementById('edit_judul').value = judul || '';
    document.getElementById('edit_deskripsi').value = deskripsi || '';
    document.getElementById('edit_kategori_id').value = kategori_id || '';
    new bootstrap.Modal(document.getElementById('editFotoModal')).show();
}

function deleteFoto(id) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        const form = document.getElementById(`delete-form-${id}`);
        if (form) form.submit();
    }
}

// Category Filtering
document.addEventListener('DOMContentLoaded', function() {
    const filterOptions = document.querySelectorAll('.filter-option');
    const galeriCards = document.querySelectorAll('.galeri-card');
    const dropdownButton = document.getElementById('categoryDropdown');
    const galeriContainer = document.querySelector('.row.scroll-animate');

    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.getAttribute('data-category');
            const categoryName = this.textContent.trim();
            
            // Update active option
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Update dropdown button text
            const icon = this.querySelector('i').outerHTML;
            dropdownButton.innerHTML = icon + ' ' + categoryName;
            
            // Filter and reorder cards
            filterAndReorderCards(category);
        });
    });

    function filterAndReorderCards(category) {
        const visibleCards = [];
        const hiddenCards = [];
        
        // Separate visible and hidden cards
        galeriCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            
            if (category === 'all' || cardCategory === category) {
                visibleCards.push(card);
                card.style.display = 'block';
                card.classList.remove('hidden');
            } else {
                hiddenCards.push(card);
                card.style.display = 'none';
                card.classList.add('hidden');
            }
        });
        
        // Reorder visible cards in the container
        if (galeriContainer) {
            // Clear container
            galeriContainer.innerHTML = '';
            
            // Add visible cards back in order with proper column structure
            visibleCards.forEach(card => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-3 mb-4';
                colDiv.appendChild(card);
                galeriContainer.appendChild(colDiv);
            });
            
            // Add hidden cards at the end (but keep them hidden)
            hiddenCards.forEach(card => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-3 mb-4';
                colDiv.appendChild(card);
                galeriContainer.appendChild(colDiv);
            });
        }
        
        // Add smooth transition effect
        visibleCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
});

// Scroll Animation
function handleScrollAnimation() {
    const elements = document.querySelectorAll('.scroll-animate');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate');
        }
    });
}

// Static foto functions
function editStaticFoto(id, judul, deskripsi) {
    const newJudul = prompt('Edit Judul:', judul);
    if (newJudul !== null && newJudul !== judul) {
        // Update the title in the DOM
        const card = document.querySelector(`img[alt="${judul}"]`).closest('.card');
        const titleElement = card.querySelector('.card-title');
        titleElement.textContent = newJudul;
        
        // Show success message
        alert('Judul berhasil diubah!');
    }
}

function deleteStaticFoto(id) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        const card = document.querySelector(`img[alt*="${id}"]`).closest('.col-md-4');
        if (card) {
            card.style.transition = 'opacity 0.3s ease';
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
            }, 300);
        }
    }
}

// Run scroll animation on page load and scroll
document.addEventListener('DOMContentLoaded', handleScrollAnimation);
window.addEventListener('scroll', handleScrollAnimation);

// Like and Dislike functions
function likePhoto(photoId) {
    fetch(`/public/galeri/${photoId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the like count
            const likeBtn = document.querySelector(`.like-btn[data-photo-id="${photoId}"] .like-count`);
            if (likeBtn) {
                likeBtn.textContent = data.likes;
            }
            
            // Add animation
            const btn = document.querySelector(`.like-btn[data-photo-id="${photoId}"]`);
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-success');
            setTimeout(() => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }, 300);
        }
    })
    .catch(error => console.log('Like error:', error));
}

function dislikePhoto(photoId) {
    fetch(`/public/galeri/${photoId}/dislike`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the dislike count
            const dislikeBtn = document.querySelector(`.dislike-btn[data-photo-id="${photoId}"] .dislike-count`);
            if (dislikeBtn) {
                dislikeBtn.textContent = data.dislikes;
            }
            
            // Add animation
            const btn = document.querySelector(`.dislike-btn[data-photo-id="${photoId}"]`);
            btn.classList.add('btn-danger');
            btn.classList.remove('btn-outline-danger');
            setTimeout(() => {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }, 300);
        }
    })
    .catch(error => console.log('Dislike error:', error));
}
</script>

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
// Initialize GLightbox
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
        closeButton: true,
        closeOnOutsideClick: true,
        zoomable: true,
        draggable: true,
        dragToleranceX: 40,
        dragToleranceY: 65,
        dragAutoSnap: false,
        preload: true,
        onOpen: function() {
            // Track view when lightbox opens (first slide)
            const currentSlide = this.activeSlide;
            if (currentSlide) {
                const photoId = currentSlide.slideConfig.photoId;
                if (photoId) {
                    fetch(`/public/galeri/${photoId}/view`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    }).catch(error => console.log('View tracking error:', error));
                }
            }
        },
        onSlideChanged: ({ current }) => {
            // Track view when navigating to another slide
            if (current && current.slideConfig && current.slideConfig.photoId) {
                const photoId = current.slideConfig.photoId;
                fetch(`/public/galeri/${photoId}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                }).catch(error => console.log('View tracking error:', error));
            }
        }
    });

    // Add photo ID to slide config
    document.querySelectorAll('.glightbox').forEach(function(element) {
        const photoId = element.getAttribute('data-photo-id');
        if (photoId) {
            element.setAttribute('data-glightbox', `photoId: ${photoId}`);
        }
    });
});

// Fallback: track view when user clicks thumbnail
(function(){
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    document.querySelectorAll('.glightbox').forEach(function(el){
        el.addEventListener('click', function(){
            const id = el.getAttribute('data-photo-id');
            if (id) {
                fetch(`/public/galeri/${id}/view`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }
                }).catch(function(err){ console.log('View tracking error:', err); });
            }
        });
    });
})();

// ========================================
// USER INTERACTION FUNCTIONS
// ========================================

// Check if user is authenticated
function checkAuth(event, action) {
    @if(!$user && !session('admin_authenticated'))
        event.preventDefault();
        showLoginModal(action);
        return false;
    @endif
    return true;
}

// Toggle Like
function toggleLike(galeriId) {
    @if(!$user && !session('admin_authenticated'))
        showLoginModal('like');
        return;
    @endif
    
    // Handle static photos (no server interaction needed)
    if (typeof galeriId === 'string' && galeriId.startsWith('static-')) {
        const likeBtn = document.querySelector(`.like-btn[data-photo-id="${galeriId}"]`);
        const likeCount = likeBtn.querySelector('.like-count');
        const currentCount = parseInt(likeCount.textContent);
        
        if (likeBtn.classList.contains('active')) {
            likeBtn.classList.remove('active');
            likeCount.textContent = currentCount - 1;
        } else {
            likeBtn.classList.add('active');
            likeCount.textContent = currentCount + 1;
        }
        return;
    }
    
    console.log('Toggling like for galeri:', galeriId);
    
    fetch(`/public/galeri/${galeriId}/toggle-like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            if (response.status === 401) {
                showLoginModal('like');
                throw new Error('Unauthorized');
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const likeBtn = document.querySelector(`.like-btn[data-photo-id="${galeriId}"]`);
            const dislikeBtn = document.querySelector(`.dislike-btn[data-photo-id="${galeriId}"]`);
            
            if (likeBtn && dislikeBtn) {
                likeBtn.querySelector('.like-count').textContent = data.likes;
                dislikeBtn.querySelector('.dislike-count').textContent = data.dislikes;
                
                if (data.userLiked) {
                    likeBtn.classList.add('active');
                    dislikeBtn.classList.remove('active');
                } else {
                    likeBtn.classList.remove('active');
                }
            }
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Like error:', error);
        if (error.message !== 'Unauthorized') {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}

// Toggle Dislike
function toggleDislike(galeriId) {
    @if(!$user && !session('admin_authenticated'))
        showLoginModal('dislike');
        return;
    @endif
    
    // Handle static photos (no server interaction needed)
    if (typeof galeriId === 'string' && galeriId.startsWith('static-')) {
        const dislikeBtn = document.querySelector(`.dislike-btn[data-photo-id="${galeriId}"]`);
        const dislikeCount = dislikeBtn.querySelector('.dislike-count');
        const currentCount = parseInt(dislikeCount.textContent);
        
        if (dislikeBtn.classList.contains('active')) {
            dislikeBtn.classList.remove('active');
            dislikeCount.textContent = currentCount - 1;
        } else {
            dislikeBtn.classList.add('active');
            dislikeCount.textContent = currentCount + 1;
        }
        return;
    }
    
    console.log('Toggling dislike for galeri:', galeriId);
    
    fetch(`/public/galeri/${galeriId}/toggle-dislike`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            if (response.status === 401) {
                showLoginModal('dislike');
                throw new Error('Unauthorized');
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const likeBtn = document.querySelector(`.like-btn[data-photo-id="${galeriId}"]`);
            const dislikeBtn = document.querySelector(`.dislike-btn[data-photo-id="${galeriId}"]`);
            
            if (likeBtn && dislikeBtn) {
                likeBtn.querySelector('.like-count').textContent = data.likes;
                dislikeBtn.querySelector('.dislike-count').textContent = data.dislikes;
                
                if (data.userDisliked) {
                    dislikeBtn.classList.add('active');
                    likeBtn.classList.remove('active');
                } else {
                    dislikeBtn.classList.remove('active');
                }
            }
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Dislike error:', error);
        if (error.message !== 'Unauthorized') {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}

// Download Photo
function downloadPhoto(galeriId) {
    @if(!$user && !session('admin_authenticated'))
        showLoginModal('download');
        return;
    @endif
    
    // Redirect to download URL
    window.location.href = `/public/galeri/${galeriId}/download`;
}

// Download Static Photo
function downloadStaticPhoto(filename, title) {
    @if(!$user && !session('admin_authenticated'))
        showLoginModal('download');
        return;
    @endif
    
    // Create download link for static photos
    const link = document.createElement('a');
    link.href = `/images/${filename}`;
    link.download = `${title}.jpg`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Show Comments Modal
function showComments(galeriId) {
    @if(!$user && !session('admin_authenticated'))
        showLoginModal('comment');
        return;
    @endif
    
    // Handle static photos (show empty comments)
    if (typeof galeriId === 'string' && galeriId.startsWith('static-')) {
        const modal = new bootstrap.Modal(document.getElementById('commentModal'));
        document.getElementById('commentGaleriId').value = galeriId;
        
        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = '<p class="text-muted text-center">Belum ada komentar untuk foto ini</p>';
        
        modal.show();
        return;
    }
    
    // Load comments
    fetch(`/public/galeri/${galeriId}/comments`)
        .then(response => response.json())
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('commentModal'));
            document.getElementById('commentGaleriId').value = galeriId;
            
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '';
            
            if (data.comments && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentHtml = `
                        <div class="comment-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>${comment.user_name}</strong>
                                <small class="text-muted">${comment.created_at}</small>
                            </div>
                            <p class="mb-0 mt-2">${comment.comment}</p>
                        </div>
                    `;
                    commentsList.innerHTML += commentHtml;
                });
            } else {
                commentsList.innerHTML = '<p class="text-muted text-center">Belum ada komentar</p>';
            }
            
            modal.show();
        });
}

// Submit Comment
function submitComment() {
    const galeriId = document.getElementById('commentGaleriId').value;
    const comment = document.getElementById('commentText').value;
    const submitBtn = document.querySelector('button[onclick="submitComment()"]');
    
    if (!comment.trim()) {
        alert('Komentar tidak boleh kosong!');
        return;
    }
    
    // Prevent double submit
    if (submitBtn.disabled) {
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    
    fetch(`/public/galeri/${galeriId}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ comment: comment })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear comment text
            document.getElementById('commentText').value = '';
            
            // Show success feedback
            const submitBtn = document.querySelector('button[onclick="submitComment()"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Berhasil!';
            submitBtn.classList.add('btn-success');
            submitBtn.classList.remove('btn-primary');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
                submitBtn.disabled = false;
            }, 2000);
            
            // Update comment count
            const commentBtn = document.querySelector(`button[onclick="showComments(${galeriId})"]`);
            if (commentBtn) {
                const countSpan = commentBtn.querySelector('.comment-count');
                countSpan.textContent = parseInt(countSpan.textContent) + 1;
            }
            
            // Reload comments without reopening modal
            fetch(`/public/galeri/${galeriId}/comments`)
                .then(response => response.json())
                .then(data => {
                    const commentsList = document.getElementById('commentsList');
                    commentsList.innerHTML = '';
                    
                    if (data.comments && data.comments.length > 0) {
                        data.comments.forEach(comment => {
                            const commentHtml = `
                                <div class="comment-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between">
                                        <strong>${comment.user_name}</strong>
                                        <small class="text-muted">${comment.created_at}</small>
                                    </div>
                                    <p class="mb-0 mt-2">${comment.comment}</p>
                                </div>
                            `;
                            commentsList.innerHTML += commentHtml;
                        });
                    } else {
                        commentsList.innerHTML = '<p class="text-muted text-center">Belum ada komentar</p>';
                    }
                })
                .catch(error => console.error('Error loading comments:', error));
        } else {
            alert('Gagal mengirim komentar. Silakan coba lagi.');
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Show Login Modal
function showLoginModal(action) {
    const actionText = {
        'like': 'menyukai foto',
        'dislike': 'tidak menyukai foto',
        'comment': 'berkomentar',
        'download': 'mendownload foto'
    };
    
    document.getElementById('loginModalAction').textContent = actionText[action] || 'melakukan aksi ini';
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}
</script>

<!-- Login Required Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Login Diperlukan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                <h5>Anda harus login terlebih dahulu!</h5>
                <p class="text-muted">Untuk <span id="loginModalAction">melakukan aksi ini</span>, silakan login atau daftar akun terlebih dahulu.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('user.login.form') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('user.register.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-2"></i>Daftar Akun
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-comments me-2"></i>Komentar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="commentsList" class="mb-4" style="max-height: 400px; overflow-y: auto;">
                    <!-- Comments will be loaded here -->
                </div>
                <div class="border-top pt-3">
                    <h6>Tulis Komentar</h6>
                    <textarea id="commentText" class="form-control mb-2" rows="3" placeholder="Tulis komentar Anda..."></textarea>
                    <input type="hidden" id="commentGaleriId">
                    <button class="btn btn-primary" onclick="submitComment()">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.like-btn.active, .dislike-btn.active {
    background-color: var(--primary-color) !important;
    color: white !important;
    border-color: var(--primary-color) !important;
}

.like-btn.active {
    background-color: var(--success-color) !important;
    border-color: var(--success-color) !important;
}

.dislike-btn.active {
    background-color: var(--danger-color) !important;
    border-color: var(--danger-color) !important;
}

.comment-item {
    background: var(--light-color);
    transition: var(--transition);
}

.comment-item:hover {
    background: white;
    box-shadow: var(--shadow);
}

    /* Fix untuk card yang terpotong */
    .card.h-100 {
        height: 100% !important;
        display: flex;
        flex-direction: column;
    }
    
    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Pastikan row tidak overflow */
    .row.scroll-animate {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .row.scroll-animate > [class*='col-'] {
        padding-left: 12px;
        padding-right: 12px;
        margin-bottom: 24px;
    }

    /* CSS khusus untuk admin view */
    body.admin-layout .galeri-page {
        padding: 0 !important;
        background: transparent !important;
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    body.admin-layout .main-content {
        margin-left: 220px !important;
        padding: 20px !important;
        width: calc(100% - 220px) !important;
    }
    
    body.admin-layout .container {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Fix untuk card di admin panel */
    .card.galeri-card {
        min-height: 400px;
    }
    
    .card.galeri-card .card-img-top {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }

    /* FINAL FIX - Untuk tampilan di admin panel */
    @media (min-width: 768px) {
        .main-content .container {
            max-width: 100% !important;
        }
        
        .main-content .row {
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .main-content .row > [class*='col-'] {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
    
    /* Pastikan card tidak overflow */
    .col-md-4 {
        margin-bottom: 2rem;
    }
    /* Admin Panel Specific Styles */
    body.admin-layout .galeri-page {
        padding: 0 !important;
        background: transparent !important;
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    body.admin-layout .main-content {
        margin-left: 220px !important;
        padding: 20px !important;
        width: calc(100% - 220px) !important;
    }
    
    body.admin-layout .page-header {
        background: white;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    body.admin-layout .page-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    body.admin-layout .page-subtitle {
        font-size: 0.9rem;
    }
    
    body.admin-layout .container {
        max-width: 100%;
        padding: 0;
    }
    
    body.admin-layout .galeri-card {
        height: auto !important;
        min-height: auto !important;
    }
    
    body.admin-layout .card-img-top {
        height: 200px !important;
    }
    
    /* FIX untuk Admin Panel - Hindari bentrok dengan sidebar */
    @if(session('admin_authenticated'))
    .galeri-page {
        padding: 1rem 0 !important;
        background: #f8f9fa !important;
    }
    
    .page-header {
        background: white !important;
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        border-radius: 8px !important;
    }
    
    .page-title {
        font-size: 1.75rem !important;
    }
    
    .container {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .row.scroll-animate {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .row.scroll-animate > [class*='col-'] {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    
    .galeri-card {
        margin-bottom: 1.5rem !important;
    }
    
    .admin-actions {
        margin-bottom: 1.5rem !important;
    }
    @endif
</style>