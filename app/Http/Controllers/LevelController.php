<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;

class LevelController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level' ]

        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level = LevelModel::select( 'level_id','level_kode', 'level_nama');


            \Log::info('level list method called');

        return DataTables::of($level)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/level', $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level', $level->level_id) . '">'
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
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah' ]
        ];

        $page = (object) [
            'title' => 'Tambah level baru'
        ];
        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ]);

        try {
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);

            return redirect('/level')->with('success', 'Data level berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage());
        }
    }

    public function show(string $id){
        try {
            $level = LevelModel::findOrFail($id);  // Menggunakan findOrFail untuk handling jika data tidak ditemukan

            $breadcrumb = (object) [
                'title' => 'Detail Level',
                'list' => ['Home', 'Level', 'Detail']
            ];

            $page = (object) [
                'title' => 'Detail Level'
            ];

            $activeMenu = 'level';

            return view('level.show', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'level' => $level,
                'activeMenu' => $activeMenu
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }
    }

    // Menampilkan halaman form edit level
        public function edit(string $id)
        {
            $level = LevelModel::find($id);

            $breadcrumb = (object) [
                'title' => 'Edit Level',
                'list' => ['Home', 'Level', 'Edit']
            ];

            $page = (object) [
                'title' => 'Edit Level'
            ];

            $activeMenu = 'level'; // set menu yang sedang aktif

            return view('level.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'level' => $level,
                'activeMenu' => $activeMenu
            ]);
        }

        // Menyimpan perubahan data level
        public function update(Request $request, string $id)
        {
            // Validasi input
            $request->validate([
                // username harus diisi, berupa string, minimal 3 karakter,
                // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                ]);

            // Update data user
            LevelModel::find($id)->update([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);

            // Redirect setelah update
            return redirect('/level')->with('success', 'Data user berhasil diubah');
        }

        public function destroy(string $id)
        {
            // Mengecek apakah data level dengan id yang dimaksud ada atau tidak
            $check = LevelModel::find($id);

            if (!$check) {
                // Jika data level tidak ditemukan, redirect ke halaman dengan pesan error
                return redirect('/level')->with('error', 'Data level tidak ditemukan');
            }

            try {
                // Hapus data level
                LevelModel::destroy($id);

                // Redirect dengan pesan sukses jika data berhasil dihapus
                return redirect('/level')->with('success', 'Data level berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error saat penghapusan, misalnya karena ada relasi ke tabel lain,
                // redirect dengan pesan error
                return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }
}
