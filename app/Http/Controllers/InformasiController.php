<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiSekolah;
use App\Models\News;
use App\Models\Agenda;

class InformasiController extends Controller
{
    public function index()
    {
        try {
            $jurusan = InformasiSekolah::getJurusan();
            $akreditasi = InformasiSekolah::getAkreditasi();
            $news = News::where('status', 'published')->orderBy('tanggal', 'desc')->get();
            
            // Get agendas
            try {
                $agendas = Agenda::orderBy('tanggal')->orderBy('id')->get();
            } catch (\Exception $e) {
                \Log::error('Error loading agendas: ' . $e->getMessage());
                $agendas = collect([]);
            }
            
            return view('informasi', compact('jurusan', 'akreditasi', 'news', 'agendas'));
        } catch (\Exception $e) {
            \Log::error('Error loading informasi page: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return view with empty data instead of redirecting
            // Use try-catch for each to prevent cascading errors
            try {
                $jurusan = InformasiSekolah::getJurusan();
            } catch (\Exception $e2) {
                $jurusan = collect([]);
            }
            
            try {
                $akreditasi = InformasiSekolah::getAkreditasi();
            } catch (\Exception $e2) {
                $akreditasi = collect([]);
            }
            
            try {
                $news = News::where('status', 'published')->orderBy('tanggal', 'desc')->get();
            } catch (\Exception $e2) {
                $news = collect([]);
            }
            
            try {
                $agendas = Agenda::orderBy('tanggal')->orderBy('id')->get();
            } catch (\Exception $e2) {
                $agendas = collect([]);
            }
            
            return view('informasi', compact('jurusan', 'akreditasi', 'news', 'agendas'))->with('error', 'Terjadi kesalahan saat memuat halaman informasi.');
        }
    }

    public function updateInformasi(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        // Update jurusan
        if ($request->has('jurusan')) {
            InformasiSekolah::where('type', 'jurusan')->delete();
            foreach ($request->jurusan as $jurusan) {
                if (!empty($jurusan)) {
                    InformasiSekolah::create([
                        'type' => 'jurusan',
                        'value' => $jurusan
                    ]);
                }
            }
        }

        // Update akreditasi
        if ($request->has('akreditasi')) {
            InformasiSekolah::where('type', 'akreditasi')->delete();
            foreach ($request->akreditasi as $akreditasi) {
                if (!empty($akreditasi)) {
                    InformasiSekolah::create([
                        'type' => 'akreditasi',
                        'value' => $akreditasi
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Informasi sekolah berhasil diperbarui!');
    }

    public function updatePrestasi(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        // Simpan prestasi ke session untuk sementara
        $prestasi = [];
        if ($request->has('prestasi_icon')) {
            for ($i = 0; $i < count($request->prestasi_icon); $i++) {
                if (!empty($request->prestasi_judul[$i])) {
                    $prestasi[] = [
                        'icon' => $request->prestasi_icon[$i],
                        'judul' => $request->prestasi_judul[$i],
                        'subtitle' => $request->prestasi_subtitle[$i] ?? '',
                    ];
                }
            }
        }

        session(['prestasi_data' => $prestasi]);

        return redirect()->back()->with('success', 'Prestasi berhasil diperbarui!');
    }

    public function updateAgenda(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'agenda' => 'required|array',
            'agenda.*.tanggal' => 'required|date',
            'agenda.*.judul' => 'required|string|max:255',
            'agenda.*.deskripsi' => 'nullable|string',
        ]);

        // Hapus semua agenda yang ada
        Agenda::truncate();

        // Simpan agenda baru
        foreach ($request->agenda as $index => $item) {
            if (!empty($item['judul']) && !empty($item['tanggal'])) {
                Agenda::create([
                    'tanggal' => $item['tanggal'],
                    'judul' => $item['judul'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'order' => $index + 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Agenda berhasil diperbarui!');
    }
}
