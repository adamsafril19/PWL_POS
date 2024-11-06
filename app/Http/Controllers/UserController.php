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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


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
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if($request->level_id){
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level_nama', function ($user) {
                return $user->level ? $user->level->level_nama : '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url("user/$user->user_id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("user/$user->user_id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url("/user/' . $user->user_id . '/delete_ajax").'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
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
            'password' => Hash::make($request->password), // Password dienkripsi sebelum disimpan
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

    public function show_ajax($id)
    {
        try {
            // Ambil data user beserta relasi level
            $user = UserModel::with('level')->find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan'
                ], 404);
            }

            // Ambil data level untuk dropdown jika diperlukan
            $level = LevelModel::all();

            return view('user.show_ajax', [
                'user' => $user,
                'level' => $level
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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

        // Menampilkan halaman form edit user
        public function edit_ajax(string $id)
        {
            $user = UserModel::find($id);
            $level = LevelModel::select('level_id', 'level_nama')->get();

            return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
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
                'password' => $request->password ? Hash::make($request->password) : UserModel::find($id)->password,
                'level_id' => $request->level_id
            ]);

            // Redirect setelah update
            return redirect('/user')->with('success', 'Data user berhasil diubah');
        }

        public function update_ajax(Request $request, $id)
        {
            if ($request->ajax() || $request->wantsJson()) {
                try {
                    // Validasi
                    $rules = [
                        'level_id' => 'required|integer',
                        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                        'nama' => 'required|string|max:100',
                        'password' => 'nullable|min:6'
                    ];

                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Validasi gagal',
                            'msgField' => $validator->errors()
                        ]);
                    }

                    // Ambil user yang akan diupdate
                    $user = UserModel::find($id);

                    if (!$user) {
                        return response()->json([
                            'status' => false,
                            'message' => 'User tidak ditemukan'
                        ]);
                    }

                    // Update data
                    $user->username = $request->username;
                    $user->nama = $request->nama;
                    $user->level_id = $request->level_id;

                    // Update password jika diisi
                    if ($request->filled('password')) {
                        $user->password = Hash::make($request->password);
                    }

                    $user->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data user berhasil diupdate'
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal mengupdate data: ' . $e->getMessage()
                    ], 500);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Invalid request method'
            ], 400);
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

        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari Ajax atau menginginkan JSON response
            if ($request->ajax() || $request->wantsJson()) {
                // Cari user berdasarkan ID
                $user = UserModel::find($id);
                if ($user) {
                    // Hapus data user
                    $user->delete();
                    // Kirim respon berhasil
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus',
                    ]);
                } else {
                    // Kirim respon gagal jika data tidak ditemukan
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan',
                    ]);
                }
            }

            // Redirect jika request tidak berasal dari Ajax
            return redirect('/');
        }


        public function confirm_ajax(String $id){
            $user = UserModel::find($id);
            return view('user/confirm_ajax', ['user' => $user]);
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

        public function import()
        {
            return view('user.import');
        }
        public function download_template()
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Username');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Level');
            $sheet->setCellValue('D1', 'Password');
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            $sheet->setCellValue('A2', 'admin');
            $sheet->setCellValue('B2', 'Administrator');
            $sheet->setCellValue('C2', 'ADM');
            $sheet->setCellValue('D2', '');
            $sheet->setCellValue('A3', 'manager');
            $sheet->setCellValue('B3', 'Manager');
            $sheet->setCellValue('C3', 'MNG');
            $sheet->setCellValue('D3', '');
            $sheet->setCellValue('A4', 'staff');
            $sheet->setCellValue('B4', 'Staff Member');
            $sheet->setCellValue('C4', 'STF');
            $sheet->setCellValue('D4', '');
            foreach (range('A', 'D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet->setTitle('Template User');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'template_user.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }
        public function import_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'file_user' => ['required', 'mimes:xlsx', 'max:1024']
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }
                $file = $request->file('file_user');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);
                $insert = [];
                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) {
                            $insert[] = [
                                'username' => $value['A'],
                                'nama' => $value['B'],
                                'password' => bcrypt($value['C']),
                                'level_id' => $value['D'],
                            ];
                        }
                    }
                    if (count($insert) > 0) {
                        UserModel::insertOrIgnore($insert);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
            }
            return redirect('/user');
        }
        public function export_excel()
        {
            $users = UserModel::select('username', 'nama', 'level_id')
                ->orderBy('level_id')
                ->with('level')
                ->get();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Username');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Level');
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            $no = 1;
            $baris = 2;
            foreach ($users as $key => $value) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->username);
                $sheet->setCellValue('C' . $baris, $value->nama);
                $sheet->setCellValue('D' . $baris, $value->level->level_nama);
                $baris++;
                $no++;
            }
            foreach (range('A', 'D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet->setTitle('Data User');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }
        public function export_pdf()
        {
            $users = UserModel::select('username', 'nama', 'level_id')
                ->orderBy('level_id')
                ->with('level')
                ->get();
            $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
            $pdf->setPaper('a4', 'potrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->render();
            return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
        }

        public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Anda',
            'list'  => ['Home', 'Profile']
        ];
        $activeMenu = 'profile';
        return view('profil.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
    public function showChangePhotoForm()
    {
        return view('profil.change_photo');
    }
    public function showManageProfileForm()
    {
        return view('profil.manage');
    }

    public function updateProfile(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        'phone_number' => 'nullable|string|max:15',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
    ]);

    // Ambil user yang sedang login
    $user = auth()->user();

    // Periksa apakah ada file avatar yang diunggah
    if ($request->hasFile('avatar')) {
        // Hapus gambar profil lama jika ada
        if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        // Simpan file gambar baru
        $file = $request->file('avatar');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/avatars', $filename);

        // Update avatar user
        $user->avatar = $filename;
    }

    // Update data profil lainnya
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->phone_number = $validatedData['phone_number'];

    // Simpan perubahan
    $user->save();

    return back()->with('success', 'Profile updated successfully');
}

}
