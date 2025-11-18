@extends(session('admin_authenticated') ? 'layouts.admin' : 'layouts.public')

@section('content')
<style>
    /* Consistent with Homepage Theme */
    :root {
        --primary-color: #0066cc;
        --primary-dark: #004499;
        --secondary-color: #6c757d;
        --accent-color: #17a2b8;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --text-primary: #2c3e50;
        --text-secondary: #6c757d;
        --border-radius: 12px;
        --shadow: 0 8px 32px rgba(0,0,0,0.1);
        --shadow-hover: 0 12px 40px rgba(0,0,0,0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .informasi-page {
        background: var(--light-color);
        min-height: 100vh;
        padding: 3rem 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow);
        text-align: center;
        border: 1px solid rgba(44, 62, 80, 0.05);
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
        font-size: 2.8rem !important;
        font-weight: 800 !important;
        color: var(--text-primary) !important;
        margin-bottom: 0.5rem !important;
        letter-spacing: -1px !important;
        text-align: center !important;
        position: relative !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
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
        font-size: 1.4rem !important;
        color: var(--text-secondary) !important;
        margin-bottom: 2rem !important;
        font-weight: 500 !important;
        text-align: center !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        letter-spacing: 0.3px !important;
        line-height: 1.4 !important;
    }

    .edit-button {
        background: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        color: white;
        transition: var(--transition);
        box-shadow: 0 8px 25px rgba(0, 102, 204, 0.3);
        font-size: 1.1rem;
    }

    .edit-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 102, 204, 0.4);
        background: var(--primary-dark);
        color: white;
    }

    .info-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
        border: 1px solid rgba(44, 62, 80, 0.05);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-color);
    }

    .card-header {
        background: var(--primary-color);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .info-section {
        margin-bottom: 1.25rem;
    }

    .info-label {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: var(--border-radius);
        border-left: 4px solid var(--primary-color);
    }

    .facility-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .facility-list li {
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid rgba(44, 62, 80, 0.1);
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .facility-list li:hover {
        background: #f8f9fa;
        transform: translateX(5px);
        border-left: 4px solid var(--primary-color);
    }

    .facility-list i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .news-item {
        background: white;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: var(--border-radius);
        border: 1px solid rgba(44, 62, 80, 0.1);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .news-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(0, 102, 204, 0.05);
        transition: var(--transition);
    }

    .news-item:hover::before {
        left: 100%;
    }

    .news-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
        border-color: var(--primary-color);
    }

    .news-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .news-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .news-description {
        color: var(--text-secondary);
        line-height: 1.6;
        font-weight: 500;
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

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .page-header {
            padding: 1.5rem 1rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .info-label {
            font-size: 0.8rem;
        }
    }
</style>

<div class="informasi-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header scroll-animate">
            <div class="text-center mb-3">
                <h1 class="page-title">ℹ️ Informasi Sekolah</h1>
                <p class="page-subtitle">Informasi lengkap tentang SMKN 4 Bogor</p>
            </div>
            @if(session('admin_authenticated'))
                <div class="text-center">
                    <button class="btn edit-button" data-bs-toggle="modal" data-bs-target="#editInformasiModal">
                        <i class="fas fa-edit me-2"></i>Edit Informasi
                    </button>
                </div>
            @endif
        </div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Tentang & Visi Misi (dipindahkan dari halaman Tentang) -->
<div class="card mb-4" id="tentang" style="scroll-margin-top: 100px; max-width: 1100px; margin-left: auto; margin-right: auto;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tentang SMKN 4 Bogor</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <p class="lead">SMKN 4 Bogor merupakan sekolah kejuruan yang berfokus pada pengembangan teknologi dan karakter siswa.</p>
                <p>Dengan fasilitas modern dan tenaga pengajar yang berpengalaman, kami berkomitmen untuk menghasilkan lulusan yang siap menghadapi tantangan dunia kerja di era digital.</p>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Fasilitas Modern</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Guru Berpengalaman</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Kurikulum Terkini</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Lingkungan Kondusif</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/4.JPG') }}" alt="SMKN 4 Bogor" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>


<!-- Informasi Umum -->
<div class="card mb-4" id="jurusan" style="scroll-margin-top: 100px;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Informasi Umum SMKN 4</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="info-section scroll-animate">
                    <div class="info-label">
                        <i class="fas fa-graduation-cap me-2"></i>JURUSAN
                    </div>
                    <div class="jurusan-photos">
                        <div class="row g-4">
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('images/jurusan/pplg.jpeg') }}?v={{ time() }}" class="card-img-top" alt="PPLG" style="height: 200px; object-fit: contain; background-color: #f8f9fa; padding: 10px;" loading="lazy" onerror="this.src='{{ asset('images/pplg.jpeg') }}'">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">PPLG</h5>
                                        <p class="card-text" style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0;">Pengembangan Perangkat Lunak dan Gim</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('images/jurusan/tkj.jpeg') }}?v={{ time() }}" class="card-img-top" alt="TKJ" style="height: 200px; object-fit: contain; background-color: #f8f9fa; padding: 10px;" loading="lazy" onerror="this.src='{{ asset('images/tkj.jpeg') }}'">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">TKJ</h5>
                                        <p class="card-text" style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0;">Teknik Komputer dan Jaringan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('images/jurusan/to.jpeg') }}?v={{ time() }}" class="card-img-top" alt="TO" style="height: 200px; object-fit: contain; background-color: #f8f9fa; padding: 10px;" loading="lazy" onerror="this.src='{{ asset('images/to.jpeg') }}'">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">TO</h5>
                                        <p class="card-text" style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0;">Teknik Otomotif</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('images/jurusan/tpfl.jpeg') }}?v={{ time() }}" class="card-img-top" alt="TPFL" style="height: 200px; object-fit: contain; background-color: #f8f9fa; padding: 10px;" loading="lazy" onerror="this.src='{{ asset('images/tpfl.jpeg') }}'">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">TPFL</h5>
                                        <p class="card-text" style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0;">Teknik Pengelasan dan Fabrikasi Logam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Berita Terbaru -->
<div class="card mb-4" id="berita" style="scroll-margin-top: 100px; max-width: 1100px; margin-left: auto; margin-right: auto;">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Berita Terbaru</h5>
    </div>
    <div class="card-body">
        @if(isset($news) && count($news) > 0)
            <div class="news-scroll-container" style="max-height: 500px; overflow-y: auto; overflow-x: hidden;">
                @foreach($news as $item)
                    <div class="news-item mb-3">
                        <div class="news-title">
                            <i class="fas fa-newspaper text-primary me-2"></i>{{ $item->judul }}
                        </div>
                        <div class="news-date">
                            <i class="fas fa-calendar-alt me-2"></i>{{ $item->tanggal->format('d F Y') }}
                        </div>
                        <div class="news-description">
                            {{ Str::limit($item->deskripsi, 150) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-newspaper fa-3x mb-3"></i>
                <p>Belum ada berita terbaru</p>
                @if(session('admin_authenticated'))
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Berita Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Agenda Sekolah -->
<div class="card mb-4" id="agenda" style="scroll-margin-top: 100px; max-width: 1100px; margin-left: auto; margin-right: auto;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Agenda Sekolah</h5>
    </div>
    <div class="card-body">
        @php
            // Use $agendas from controller, with fallback to empty collection
            $agendas = $agendas ?? collect([]);
        @endphp
        
        @php
            $agendaData = [];
            if (isset($agendas) && $agendas->count() > 0) {
                $agendaData = $agendas->map(function($a) {
                    return [
                        'tanggal' => $a->tanggal,
                        'judul' => $a->judul,
                        'deskripsi' => $a->deskripsi
                    ];
                })->toArray();
            } else {
                $agendaData = session('agenda_data', []);
            }
        @endphp

        @if(is_array($agendaData) && count($agendaData))
            <div class="timeline-container">
                @php
                    $dotColors = [
                        'var(--primary-color)',
                        'var(--success-color)',
                        'var(--warning-color)',
                        'var(--accent-color)'
                    ];
                @endphp

                @foreach($agendaData as $idx => $item)
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: {{ $dotColors[$idx % count($dotColors)] }};"></div>
                        <div class="timeline-content">
                            <div class="timeline-date">
                                {{ \Carbon\Carbon::parse($item['tanggal'] ?? now())->format('d M Y') }}
                            </div>
                            <div class="timeline-title">{{ $item['judul'] ?? '-' }}</div>
                            <div class="timeline-description">
                                {{ $item['deskripsi'] ?? '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Default contoh agenda (fallback saat belum ada data) -->
            <div class="timeline-container">
                <div class="timeline-item">
                    <div class="timeline-dot" style="background: var(--primary-color);"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">15 Desember 2024</div>
                        <div class="timeline-title">📚 Ujian Akhir Semester</div>
                        <div class="timeline-description">
                            Pelaksanaan ujian akhir semester untuk semua jurusan. Siswa diharap hadir tepat waktu dan membawa perlengkapan ujian yang diperlukan.
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" style="background: var(--success-color);"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">20 Desember 2024</div>
                        <div class="timeline-title">🎓 Wisuda Kelas XII</div>
                        <div class="timeline-description">
                            Acara wisuda untuk siswa kelas XII semua jurusan. Keluarga diundang untuk menghadiri acara penting ini.
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" style="background: var(--warning-color);"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">25 Desember 2024</div>
                        <div class="timeline-title">🎄 Libur Natal & Tahun Baru</div>
                        <div class="timeline-description">
                            Libur semester dimulai. Selamat berlibur dan jangan lupa untuk tetap belajar di rumah.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Edit Informasi Lengkap -->
@if(session('admin_authenticated'))
<div class="modal fade" id="editInformasiModal" tabindex="-1" aria-labelledby="editInformasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editInformasiModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Informasi Sekolah Lengkap
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="editInfoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="jurusan-tab" data-bs-toggle="tab" data-bs-target="#jurusan-content" type="button" role="tab">
                            <i class="fas fa-graduation-cap me-2"></i>Jurusan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="berita-tab" data-bs-toggle="tab" data-bs-target="#berita-content" type="button" role="tab">
                            <i class="fas fa-newspaper me-2"></i>Berita
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="agenda-tab" data-bs-toggle="tab" data-bs-target="#agenda-content" type="button" role="tab">
                            <i class="fas fa-calendar-alt me-2"></i>Agenda
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="editInfoTabsContent">
                    <!-- Tab Jurusan -->
                    <div class="tab-pane fade show active" id="jurusan-content" role="tabpanel">
                        <h6 class="mb-3"><i class="fas fa-laptop me-2"></i>Kelola Jurusan</h6>
                        <form action="{{ route('informasi.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="jurusan-items">
                                @php
                                    $jurusanList = [
                                        ['nama' => 'PPLG', 'deskripsi' => 'Pengembangan Perangkat Lunak dan Gim', 'image' => 'jurusan/pplg.jpeg'],
                                        ['nama' => 'TKJ', 'deskripsi' => 'Teknik Komputer dan Jaringan', 'image' => 'jurusan/tkj.jpeg'],
                                        ['nama' => 'TO', 'deskripsi' => 'Teknik Otomotif', 'image' => 'jurusan/to.jpeg'],
                                        ['nama' => 'TPFL', 'deskripsi' => 'Teknik Pengelasan dan Fabrikasi Logam', 'image' => 'jurusan/tpfl.jpeg'],
                                    ];
                                @endphp
                                
                                @foreach($jurusanList as $index => $jur)
                                    <div class="jurusan-item mb-4 p-3 border rounded bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 text-center">
                                                <label class="form-label fw-bold d-block">Preview</label>
                                                <img src="{{ asset('images/' . $jur['image']) }}" alt="{{ $jur['nama'] }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Nama Jurusan</label>
                                                <input type="text" name="jurusan_nama[]" value="{{ $jur['nama'] }}" class="form-control" placeholder="PPLG">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label fw-bold">Deskripsi</label>
                                                <input type="text" name="jurusan_deskripsi[]" value="{{ $jur['deskripsi'] }}" class="form-control" placeholder="Pengembangan Perangkat Lunak dan Gim">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Logo/Gambar</label>
                                                <input type="file" name="jurusan_image_{{ $index }}" class="form-control form-control-sm" accept="image/*">
                                                <small class="text-muted d-block mt-1">Current: {{ $jur['image'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Jurusan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Berita -->
                    <div class="tab-pane fade" id="berita-content" role="tabpanel">
                        <h6 class="mb-3"><i class="fas fa-newspaper me-2"></i>Kelola Berita</h6>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Daftar Berita Terbaru</strong><br>
                            Kelola semua berita sekolah di sini. Tambah, edit, atau hapus berita langsung dari tab ini.
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-center mb-3">
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Berita
                            </a>
                            <a href="{{ route('admin.news') }}" class="btn btn-info">
                                <i class="fas fa-list me-2"></i>Lihat Semua Berita
                            </a>
                        </div>
                        
                        @if(isset($news) && count($news) > 0)
                        <div class="mt-4">
                            <h6 class="mb-3">Daftar Berita ({{ $news->count() }} berita):</h6>
                            <div class="berita-scroll-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.5rem; background-color: #fff;">
                                <div class="list-group list-group-flush">
                                    @foreach($news as $item)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $item->judul }}</h6>
                                                <p class="mb-1 text-muted small">{{ Str::limit($item->deskripsi, 100) }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>{{ $item->tanggal->format('d M Y') }}
                                                </small>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center mt-4 py-4">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada berita. Klik tombol "Tambah Berita" di atas untuk menambahkan berita pertama!</p>
                        </div>
                        @endif
                        
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Tutup
                            </button>
                        </div>
                    </div>

                    <!-- Tab Agenda -->
                    <div class="tab-pane fade" id="agenda-content" role="tabpanel">
                        <h6 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Kelola Agenda</h6>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Daftar Agenda Sekolah</strong><br>
                            Kelola semua agenda dan jadwal kegiatan sekolah di sini. Tambah, edit, atau hapus agenda langsung dari tab ini.
                        </div>
                        
                        <form action="{{ route('agenda.update') }}" method="POST" id="agendaForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="agenda-items" id="agendaItems">
                                @php
                                    // Use $agendas from controller, with fallback to empty collection
                                    $agendas = $agendas ?? collect([]);
                                @endphp
                                
                                @if(isset($agendas) && $agendas->count() > 0)
                                    @foreach($agendas as $index => $agenda)
                                        <div class="agenda-item mb-3 p-3 border rounded bg-light" data-index="{{ $index }}">
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Tanggal</label>
                                                    <input type="date" name="agenda[{{ $index }}][tanggal]" value="{{ \Carbon\Carbon::parse($agenda->tanggal)->format('Y-m-d') }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Judul Agenda</label>
                                                    <input type="text" name="agenda[{{ $index }}][judul]" value="{{ $agenda->judul }}" class="form-control" placeholder="Masukkan judul agenda" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fw-bold">Aksi</label>
                                                    <div class="d-grid">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeAgenda({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                                                    <textarea name="agenda[{{ $index }}][deskripsi]" class="form-control" rows="2" placeholder="Masukkan deskripsi agenda">{{ $agenda->deskripsi }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada agenda. Klik tombol "Tambah Agenda" untuk menambahkan agenda pertama!</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" onclick="addAgenda()">
                                    <i class="fas fa-plus me-2"></i>Tambah Agenda
                                </button>
                            </div>
                            
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Agenda
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

<style>
/* CSS Variables for Timeline */
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
    --border-radius: 16px;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Spacing Scale */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    --space-3xl: 4rem;
}

/* Card Styling */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
    padding: 1rem 1.5rem;
}

.card-header h5 {
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

/* Info Section */
.info-section {
    margin-bottom: 1.25rem;
}

.info-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 3px solid #3498db;
    padding-bottom: 0.5rem;
    display: inline-block;
}

.facility-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.facility-list li {
    padding: 0.75rem 0;
    color: #6c757d;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.facility-list li:last-child {
    border-bottom: none;
}

.facility-list li:hover {
    color: #2c3e50;
    padding-left: 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 0.75rem;
    margin: 0 -0.5rem;
}

/* Foto Jurusan Styles */
.jurusan-photos {
    margin-top: 1rem;
}

.jurusan-photos .row {
    display: flex;
    flex-wrap: wrap;
}

.jurusan-photos .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.jurusan-photos .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.jurusan-photos .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.jurusan-photos .card-img-top {
    border-radius: 8px 8px 0 0;
    flex-shrink: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .jurusan-photos .col-lg-6 {
        margin-bottom: 1rem;
    }
}

    
    

/* News Items */
.news-item {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.news-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.news-item:hover {
    background: #f8f9fa;
    margin: 0 -1rem;
    padding: 1rem;
    border-radius: 12px;
}

.news-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.news-date {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.news-description {
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
}

/* Statistics */
.stat-item {
    padding: 1.5rem;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #dee2e6;
}

.stat-icon {
    margin-bottom: 1rem;
}

.stat-item h4 {
    font-weight: 700;
    margin-bottom: 0.5rem;
}

/* Modal Styling */
.modal-header.bg-primary {
    background: var(--primary-color) !important;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

/* Form Styling */
.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .stat-item {
        margin-bottom: 1rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease forwards;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }




@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }

    .page-header {
        padding: 2rem 1.5rem;
    }
}

/* Modal Tabs Styling */
.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 600;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    color: #0066cc;
    border-bottom-color: #0066cc;
    background-color: transparent;
}

.nav-tabs .nav-link.active {
    color: #0066cc;
    background-color: transparent;
    border-bottom-color: #0066cc;
}

.tab-content {
    padding: 1.5rem 0;
}

.jurusan-item {
    transition: all 0.3s ease;
}

.jurusan-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-label.fw-bold {
    color: #2c3e50;
    font-size: 0.9rem;
}

/* Berita Scroll Container (Modal) */
.berita-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #0066cc #f1f1f1;
}

.berita-scroll-container::-webkit-scrollbar {
    width: 8px;
}

.berita-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.berita-scroll-container::-webkit-scrollbar-thumb {
    background: #0066cc;
    border-radius: 10px;
}

.berita-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #004499;
}

.berita-scroll-container .list-group-item {
    transition: all 0.3s ease;
}

.berita-scroll-container .list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* News Scroll Container (Card Berita Terbaru) */
.news-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #17a2b8 #f1f1f1;
}

.news-scroll-container::-webkit-scrollbar {
    width: 8px;
}

.news-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.news-scroll-container::-webkit-scrollbar-thumb {
    background: #17a2b8;
    border-radius: 10px;
}

.news-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #138496;
}

.news-scroll-container .news-item {
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 8px;
}

.news-scroll-container .news-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* Agenda Items Styling */
.agenda-item {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef !important;
}

.agenda-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #0066cc !important;
}

.agenda-item .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.agenda-item .form-control:focus {
    border-color: #0066cc;
    box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
}

.agenda-item .btn-danger {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.agenda-item .btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Agenda Public Display Styling */
.agenda-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #28a745 #f1f1f1;
}

.agenda-scroll-container::-webkit-scrollbar {
    width: 8px;
}

.agenda-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.agenda-scroll-container::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 10px;
}

.agenda-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #218838;
}

.agenda-scroll-container .agenda-item {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef !important;
    background: white !important;
}

.agenda-scroll-container .agenda-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #28a745 !important;
}

.agenda-date {
    font-size: 0.9rem;
    color: #28a745;
    font-weight: 600;
}

.agenda-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.1rem;
}

.agenda-description {
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Timeline Styling - Same as Agenda Page */
.timeline-container {
    background: white;
    border-radius: var(--border-radius);
    padding: var(--space-lg);
    box-shadow: var(--shadow-lg);
    position: relative;
    border: 1px solid rgba(30, 41, 59, 0.05);
    transition: var(--transition);
    overflow: hidden;
}

.timeline-container:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 32px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--primary-color);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: var(--space-lg);
    padding-left: 50px;
    transition: var(--transition);
}

.timeline-item:hover {
    transform: translateX(8px);
}

.timeline-dot {
    position: absolute;
    left: 18px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--primary-color), var(--shadow);
    z-index: 2;
    transition: var(--transition);
}

.timeline-item:hover .timeline-dot {
    box-shadow: 0 0 0 4px var(--primary-color), var(--shadow-md);
    transform: scale(1.1);
}

.timeline-content {
    background: var(--light-color);
    border-radius: var(--border-radius);
    padding: var(--space-md) var(--space-lg);
    border-left: 3px solid var(--primary-color);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow);
    border: 1px solid rgba(30, 41, 59, 0.05);
}

.timeline-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(0, 102, 204, 0.05);
    transition: var(--transition);
}

.timeline-content:hover::before {
    left: 100%;
}

.timeline-date {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 0.75rem;
    margin-bottom: var(--space-xs);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.timeline-title {
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: var(--space-xs);
    line-height: 1.3;
}

.timeline-description {
    color: var(--text-secondary);
    line-height: 1.5;
    font-size: 0.85rem;
    margin: 0;
}

/* Responsive Timeline */
@media (max-width: 768px) {
    .timeline-container::before {
        left: 28px;
    }
    
    .timeline-item {
        padding-left: 45px;
        margin-bottom: var(--space-md);
    }
    
    .timeline-dot {
        left: 15px;
        width: 18px;
        height: 18px;
        border: 2px solid white;
    }

    .timeline-container {
        padding: var(--space-md);
    }
    
    .timeline-content {
        padding: var(--space-sm) var(--space-md);
    }
}

@media (max-width: 480px) {
    .timeline-container::before {
        left: 24px;
    }
    
    .timeline-item {
        padding-left: 38px;
        margin-bottom: var(--space-sm);
    }
    
    .timeline-dot {
        left: 12px;
        width: 16px;
        height: 16px;
        border: 2px solid white;
    }
    
    .timeline-content {
        padding: var(--space-sm);
    }
    
    .timeline-date {
        font-size: 0.7rem;
    }
    
    .timeline-title {
        font-size: 0.9rem;
    }
    
    .timeline-description {
        font-size: 0.8rem;
    }
    
    .timeline-container {
        padding: var(--space-sm);
    }
}
</style>

<script>
function addJurusan() {
    const container = document.querySelector('.jurusan-items');
    const newItem = document.createElement('div');
    newItem.className = 'jurusan-item mb-2 d-flex gap-2';
    newItem.innerHTML = '<input type="text" name="jurusan[]" class="form-control" placeholder="Masukkan jurusan baru">';
    container.appendChild(newItem);
}

// Agenda Management Functions
let agendaIndex = {{ isset($agendas) ? $agendas->count() : 0 }};

function addAgenda() {
    const container = document.getElementById('agendaItems');
    const newItem = document.createElement('div');
    newItem.className = 'agenda-item mb-3 p-3 border rounded bg-light';
    newItem.setAttribute('data-index', agendaIndex);
    
    newItem.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="agenda[${agendaIndex}][tanggal]" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Judul Agenda</label>
                <input type="text" name="agenda[${agendaIndex}][judul]" class="form-control" placeholder="Masukkan judul agenda" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Aksi</label>
                <div class="d-grid">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAgenda(${agendaIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                <textarea name="agenda[${agendaIndex}][deskripsi]" class="form-control" rows="2" placeholder="Masukkan deskripsi agenda"></textarea>
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    agendaIndex++;
    
    // Scroll to the new item
    newItem.scrollIntoView({ behavior: 'smooth' });
}

function removeAgenda(index) {
    const item = document.querySelector(`[data-index="${index}"]`);
    if (item) {
        item.remove();
    }
}

</script>

<!-- Footer -->

<script>
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

// Run scroll animation on page load and scroll
document.addEventListener('DOMContentLoaded', handleScrollAnimation);
window.addEventListener('scroll', handleScrollAnimation);
</script>

@if(!session('admin_authenticated'))
<div class="text-center mt-5 mb-4">
    <a href="/" class="btn btn-primary btn-lg">
        <i class="fas fa-home me-2"></i>Kembali ke Beranda
    </a>
</div>
@endif
@endsection