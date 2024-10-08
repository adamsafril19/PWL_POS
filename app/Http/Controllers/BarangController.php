<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BarangController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang' ]

        ];

        $page = (object) [
            'title' => 'Daftar Barang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'Barang'; // set menu yang sedang aktif

        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    //ambil data barang dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual')
            ->with('kategori');

            // Filter data barang berdasarkan kategori_id
            if($request->kategori_id){
                $barang->where('kategori_id', $request->kategori_id);
            }

            \Log::info('barang list method called');

        return DataTables::of($barang)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang', $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang', $barang->barang_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->toJson();
            // ->make(true);
    }


    public function create(){

        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah' ]
        ];

        $page = (object) [
            'title' => 'Tambah Barang Baru'
        ];
        $kategori = kategoriModel :: all(); // ambil data kategori untuk ditampilkan di form
        $activeMenu = 'Barang'; // set menu yang sedang aktif

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kategori_id' => 'required|integer',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        try {
            // Create data barang
            BarangModel::create([
                'kategori_id' => $request->kategori_id,
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);

            return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(string $id){
        $barang = barangModel::with('kategori')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail barang',
            'list' => ['Home', 'barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);

    }

    // Menampilkan halaman form edit barang
        public function edit(string $id)
        {
            $barang = BarangModel::find($id);
            $kategori = KategoriModel::all();

            $breadcrumb = (object) [
                'title' => 'Edit barang',
                'list' => ['Home', 'barang', 'Edit']
            ];

            $page = (object) [
                'title' => 'Edit barang'
            ];

            $activeMenu = 'barang'; // set menu yang sedang aktif

            return view('barang.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'barang' => $barang,
                'kategori' => $kategori,
                'activeMenu' => $activeMenu
            ]);
        }

        // Menyimpan perubahan data barang
        public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'kategori_id' => 'required|integer',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode,'.$id.',barang_id',
            'barang_nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        try {
            $barang = BarangModel::findOrFail($id);

            // Update data barang
            $barang->update([
                'kategori_id' => $request->kategori_id,
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);

            return redirect('/barang')->with('success', 'Data barang berhasil diubah');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengubah data: ' . $e->getMessage());
        }
    }

        public function destroy(string $id)
        {
            // Mengecek apakah data barang dengan id yang dimaksud ada atau tidak
            $check = barangModel::find($id);

            if (!$check) {
                // Jika data barang tidak ditemukan, redirect ke halaman dengan pesan error
                return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
            }

            try {
                // Hapus data barang
                barangModel::destroy($id);

                // Redirect dengan pesan sukses jika data berhasil dihapus
                return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error saat penghapusan, misalnya karena ada relasi ke tabel lain,
                // redirect dengan pesan error
                return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }

}
