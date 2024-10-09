<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;


class UserController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User' ]

        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = levelModel::all(); //ambil data level untuk filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    //ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
{
    // Select kolom yang dibutuhkan dari UserModel
    $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->with('level');

    // Filter data berdasarkan level_id jika ada permintaan
    if ($request->level_id) {
        $users->where('level_id', $request->level_id);
    }

    return DataTables::of($users)
        ->addIndexColumn() // Menambahkan kolom index (DT_RowIndex)
        ->addColumn('aksi', function ($user) {
            // Menambahkan kolom aksi (tombol Detail, Edit, Hapus)
            $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi']) // Menginformasikan bahwa kolom aksi mengandung HTML
        ->make(true);
}



    public function create(){

        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah' ]
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];
        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){

        $request->validate([
            // Username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            // Nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama' => 'required|string|max:100',
            // Password harus diisi dan minimal 5 karakter
            'password' => 'required|min:5',
            // Level_id harus diisi dan berupa angka
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // Password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');

    }

    public function show(string $id){
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);

    }

    // Menampilkan halaman form edit user
        public function edit(string $id)
        {
            $user = UserModel::find($id);
            $level = LevelModel::all();

            $breadcrumb = (object) [
                'title' => 'Edit User',
                'list' => ['Home', 'User', 'Edit']
            ];

            $page = (object) [
                'title' => 'Edit user'
            ];

            $activeMenu = 'user'; // set menu yang sedang aktif

            return view('user.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'user' => $user,
                'level' => $level,
                'activeMenu' => $activeMenu
            ]);
        }

        // Menyimpan perubahan data user
        public function update(Request $request, string $id)
        {
            // Validasi input
            $request->validate([
                // username harus diisi, berupa string, minimal 3 karakter,
                // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
                'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
            ]);

            // Update data user
            UserModel::find($id)->update([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
                'level_id' => $request->level_id
            ]);

            // Redirect setelah update
            return redirect('/user')->with('success', 'Data user berhasil diubah');
        }

        public function destroy(string $id)
        {
            // Mengecek apakah data user dengan id yang dimaksud ada atau tidak
            $check = UserModel::find($id);

            if (!$check) {
                // Jika data user tidak ditemukan, redirect ke halaman dengan pesan error
                return redirect('/user')->with('error', 'Data user tidak ditemukan');
            }

            try {
                // Hapus data user
                UserModel::destroy($id);

                // Redirect dengan pesan sukses jika data berhasil dihapus
                return redirect('/user')->with('success', 'Data user berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error saat penghapusan, misalnya karena ada relasi ke tabel lain,
                // redirect dengan pesan error
                return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }

        public function create_ajax()
        {

            $level = LevelModel::select('level_id', 'level_nama')->get();

            return view('user.create_ajax')
            ->with('level', $level);
        }


        public function store_ajax(Request $request)
        {

            if ($request->ajax() || $request->wantsJson()) {
                // Aturan validasi
                $rules = [
                    'level_id' => 'required|integer',
                    'username' => 'required|string|min:3|unique:m_user,username',
                    'nama' => 'required|string|max:100',
                    'password' => 'required|min:6'
                ];

                // Validasi input
                $validator = Validator::make($request->all(), $rules);

                // Jika validasi gagal, kembalikan response dengan pesan error
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                try {
                    // Siapkan data untuk disimpan
                    $data = [
                        'level_id' => $request->level_id,
                        'username' => $request->username,
                        'nama' => $request->nama,
                        'password' => Hash::make($request->password)
                    ];

                    // Simpan data
                    UserModel::create($data);

                    // Response sukses
                    return response()->json([
                        'status' => true,
                        'message' => 'Data user berhasil disimpan'
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan data user: ' . $e->getMessage()
                    ], 500);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Invalid request method'
            ], 400);
        }

}
