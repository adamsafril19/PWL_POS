<?php

namespace App\Http\Controllers;

use App\Models\kategoriModel;
use Illuminate\Http\Request;
use App\Models\LevelModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori' ]

        ];

        $page = (object) [
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];
        $activeMenu = 'Kategori'; // set menu yang sedang aktif

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
{
    try {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                // Gunakan URL langsung untuk menghindari masalah dengan route
                $btn = '<a href="kategori/' . $kategori->kategori_id . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="kategori/' . $kategori->kategori_id . '/edit" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="kategori/' . $kategori->kategori_id . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    } catch (\Exception $e) {
        \Log::error('Error in kategori list: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
    }
}


    public function create(){

        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah' ]
        ];

        $page = (object) [
            'title' => 'Tambah Kategori baru'
        ];
        $activeMenu = 'Kategori'; // set menu yang sedang aktif

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);

        try {
            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
            ]);

            return redirect('/kategori')->with('success', 'Data Kategori berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage());
        }
    }

    public function show(string $id){
        try {
            $kategori = KategoriModel::findOrFail($id);  // Menggunakan findOrFail untuk handling jika data tidak ditemukan

            $breadcrumb = (object) [
                'title' => 'Detail Kategori',
                'list' => ['Home', 'Kategori', 'Detail']
            ];

            $page = (object) [
                'title' => 'Detail Kategori'
            ];

            $activeMenu = 'Kategori';

            return view('Kategori.show', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'kategori' => $kategori,
                'activeMenu' => $activeMenu
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect('/kategori')->with('error', 'Data Kategori tidak ditemukan');
        }
    }

    // Menampilkan halaman form edit kategori
        public function edit(string $id)
        {
            $kategori = KategoriModel::find($id);

            $breadcrumb = (object) [
                'title' => 'Edit Kategori',
                'list' => ['Home', 'Kategori', 'Edit']
            ];

            $page = (object) [
                'title' => 'Edit Kategori'
            ];

            $activeMenu = 'Kategori'; // set menu yang sedang aktif

            return view('kategori.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'kategori' => $kategori,
                'activeMenu' => $activeMenu
            ]);
        }

        // Menyimpan perubahan data kategori
        public function update(Request $request, string $id)
        {
            // Validasi input
            $request->validate([
                // username harus diisi, berupa string, minimal 3 karakter,
                // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
                'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                ]);

            // Update data user
            kategoriModel::find($id)->update([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
            ]);

            // Redirect setelah update
            return redirect('/kategori')->with('success', 'Data user berhasil diubah');
        }

        public function destroy(string $id)
        {
            // Mengecek apakah data kategori dengan id yang dimaksud ada atau tidak
            $check = kategoriModel::find($id);

            if (!$check) {
                // Jika data kategori tidak ditemukan, redirect ke halaman dengan pesan error
                return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
            }

            try {
                // Hapus data kategori
                kategoriModel::destroy($id);

                // Redirect dengan pesan sukses jika data berhasil dihapus
                return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error saat penghapusan, misalnya karena ada relasi ke tabel lain,
                // redirect dengan pesan error
                return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }
}
