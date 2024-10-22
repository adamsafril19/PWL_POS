<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

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

    //ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<button onclick="modalAction(\'' . url("level/$level->level_id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("level/$level->level_id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
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

    public function create_ajax()
    {
        return view('level.create_ajax');
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

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];

            // Validate input
            $validator = Validator::make($request->all(), $rules);

            // If validation fails, return response with error messages
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                // Prepare data to be saved
                $data = [
                    'level_kode' => $request->level_kode,
                    'level_nama' => $request->level_nama,
                ];

                // Save data
                LevelModel::create($data);

                // Success response
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil disimpan'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data level: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request method'
        ], 400);
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

    public function show_ajax($id)
    {
        try {
            $level = LevelModel::find($id);

            if (!$level) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan'
                ], 404);
            }

            return view('level.show_ajax', [
                'level' => $level
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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

        public function edit_ajax(string $id)
        {
            $level = LevelModel::find($id);

            return view('level.edit_ajax', ['level' => $level]);
        }


        // Menyimpan perubahan data level
        public function update(Request $request, string $id)
        {
            // Validasi input
            $request->validate([
                // levelname harus diisi, berupa string, minimal 3 karakter,
                // dan bernilai unik di tabel m_level kolom levelname kecuali untuk level dengan id yang sedang diedit
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                ]);

            // Update data level
            LevelModel::find($id)->update([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);

            // Redirect setelah update
            return redirect('/level')->with('success', 'Data level berhasil diubah');
        }

        public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        try {
            // Validasi input
            $rules = [
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil data level yang akan diupdate
            $level = LevelModel::find($id);

            if (!$level) {
                return response()->json([
                    'status' => false,
                    'message' => 'Level tidak ditemukan'
                ]);
            }

            // Update data level
            $level->level_kode = $request->level_kode;
            $level->level_nama = $request->level_nama;
            $level->save();

            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil diupdate',
                'data' => [
                    'level_kode' => $level->level_kode,
                    'level_nama' => $level->level_nama
                ]
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

        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari Ajax atau menginginkan JSON response
            if ($request->ajax() || $request->wantsJson()) {
                // Cari user berdasarkan ID
                $level = LevelModel::find($id);
                if ($level) {
                    // Hapus data user
                    $level->delete();
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
            $level = LevelModel::find($id);
            return view('level/confirm_ajax', ['level' => $level]);
        }

        public function import()
    {
        return view('level.import');
    }
    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_level');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if(count($data) > 1){
                foreach ($data as $row => $value) {
                    if($row > 1){
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if(count($insert) > 0){
                    LevelModel::insertOrIgnore($insert);
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
        return redirect('/level');
    }
    public function export_excel()
    {
        $level = LevelModel::select('level_kode', 'level_nama')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $row = 2;
        foreach ($level as $key => $value) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $value->level_kode);
            $sheet->setCellValue('C' . $row, $value->level_nama);
            $row++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data Level');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Level_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $level = LevelModel::select('level_kode', 'level_nama')->get();
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Data_Level_' . date('Y-m-d_H-i-s') . '.pdf');
    }
    public function template_excel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Level');
        $sheet->setCellValue('B1', 'Nama Level');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'ADM');
        $sheet->setCellValue('B2', 'Admin');
        $sheet->setCellValue('A3', 'MNG');
        $sheet->setCellValue('B3', 'Manager');
        $sheet->setCellValue('A4', 'STF');
        $sheet->setCellValue('B4', 'Staff');
        foreach (range('A', 'B') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_level.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return Response::download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
