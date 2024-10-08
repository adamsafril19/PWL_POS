<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\supplierModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;

class supplierController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar supplier',
            'list' => ['Home', 'supplier' ]

        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];
        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $supplier = supplierModel::select( 'supplier_id','supplier_kode', 'supplier_nama', 'supplier_alamat');


            \Log::info('supplier list method called');

        return DataTables::of($supplier)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/supplier', $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier', $supplier->supplier_id) . '">'
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
            'title' => 'Tambah supplier',
            'list' => ['Home', 'supplier', 'Tambah' ]
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];
        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:255',
        ]);

        try {
            // Menyimpan data ke dalam database
            supplierModel::create([
                'supplier_kode' => $request->supplier_kode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat, // Menyimpan alamat
            ]);

            // Redirect ke halaman supplier dengan pesan sukses
            return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
        } catch (\Exception $e) {
            // Mengembalikan ke halaman sebelumnya dengan pesan error
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage());
        }
    }

    public function show(string $id){
        try {
            $supplier = supplierModel::findOrFail($id);  // Menggunakan findOrFail untuk handling jika data tidak ditemukan

            $breadcrumb = (object) [
                'title' => 'Detail supplier',
                'list' => ['Home', 'supplier', 'Detail']
            ];

            $page = (object) [
                'title' => 'Detail supplier'
            ];

            $activeMenu = 'supplier';

            return view('supplier.show', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'supplier' => $supplier,
                'activeMenu' => $activeMenu
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }
    }

    // Menampilkan halaman form edit supplier
        public function edit(string $id)
        {
            $supplier = supplierModel::find($id);

            $breadcrumb = (object) [
                'title' => 'Edit supplier',
                'list' => ['Home', 'supplier', 'Edit']
            ];

            $page = (object) [
                'title' => 'Edit supplier'
            ];

            $activeMenu = 'supplier'; // set menu yang sedang aktif

            return view('supplier.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'supplier' => $supplier,
                'activeMenu' => $activeMenu
            ]);
        }

        // Menyimpan perubahan data supplier
        public function update(Request $request, string $id)
{
    // Validasi input dengan pengecualian untuk id yang sedang diedit
    $request->validate([
        'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
        'supplier_nama' => 'required|string|max:100',
        'supplier_alamat' => 'required|string|max:255',
    ]);

    try {
        // Cari data berdasarkan ID
        $supplier = supplierModel::findOrFail($id);

        // Update data supplier
        $supplier->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
        ]);

        // Redirect setelah update berhasil
        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    } catch (\Exception $e) {
        // Redirect jika terjadi kesalahan dengan pesan error
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat mengubah data. ' . $e->getMessage());
    }
}


        public function destroy(string $id)
        {
            // Mengecek apakah data supplier dengan id yang dimaksud ada atau tidak
            $check = supplierModel::find($id);

            if (!$check) {
                // Jika data supplier tidak ditemukan, redirect ke halaman dengan pesan error
                return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
            }

            try {
                // Hapus data supplier
                supplierModel::destroy($id);

                // Redirect dengan pesan sukses jika data berhasil dihapus
                return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error saat penghapusan, misalnya karena ada relasi ke tabel lain,
                // redirect dengan pesan error
                return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }
}
