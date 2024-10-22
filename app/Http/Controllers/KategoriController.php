<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $activeMenu = 'Kategori Barang'; // set menu yang sedang aktif

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
                $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
        $activeMenu = 'Kategori Barang'; // set menu yang sedang aktif

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
                // kategoriname harus diisi, berupa string, minimal 3 karakter,
                // dan bernilai unik di tabel m_kategori kolom kategoriname kecuali untuk kategori dengan id yang sedang diedit
                'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                ]);

            // Update data kategori
            kategoriModel::find($id)->update([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
            ]);

            // Redirect setelah update
            return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
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

        public function create_ajax() {
            return view('kategori.create_ajax');
       }

       public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }
    return redirect('/');
}
       public function edit_ajax(string $id) {
           $kategori = KategoriModel::where('kategori_id', $id)->first();
           return view('kategori.edit_ajax', ['kategori' => $kategori]);
       }
       public function update_ajax(Request $request, $id) {
           // cek apakah request dari ajax
           if ($request->ajax() || $request->wantsJson()) {
               $rules = [
                   'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode,'.$id.',kategori_id',
                   'kategori_nama' => 'required|max:100'
               ];
               // use Illuminate\Support\Facades\Validator;
               $validator = Validator::make($request->all(), $rules);
               if ($validator->fails()) {
                   return response()->json([
                       'status' => false, // respon json, true: berhasil, false: gagal
                       'message' => 'Validasi gagal.',
                       'msgField' => $validator->errors() // menunjukkan field mana yang error
                   ]);
               }
               $check = KategoriModel::find($id);
               if ($check) {
                   $check->update($request->all());
                   return response()->json([
                       'status' => true,
                       'message' => 'Data berhasil diupdate'
                   ]);
               } else{
                   return response()->json([
                       'status' => false,
                       'message' => 'Data tidak ditemukan'
                   ]);
               }
           }
           return redirect('/');
       }
       public function confirm_ajax(string $id) {
           $kategori = KategoriModel::find($id);
           return view('kategori.confirm_ajax', ['kategori' => $kategori]);
       }
       public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari Ajax atau menginginkan JSON response
            if ($request->ajax() || $request->wantsJson()) {
                // Cari user berdasarkan ID
                $kategori = KategoriModel::find($id);
                if ($kategori) {
                    // Hapus data user
                    $kategori->delete();
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
       public function show_ajax($id)
    {
        try {
            // Ambil data kategori beserta relasi level
            $kategori = KategoriModel::find($id);

            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kategori tidak ditemukan'
                ], 404);
            }

            return view('kategori.show_ajax', [
                'kategori' => $kategori,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import()
    {
        return view('kategori.import');
    }
    public function export_excel()
    {
        $kategori = KategoriModel::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $no = 1;
        $row = 2;
        foreach ($kategori as $item) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $item->kategori_kode);
            $sheet->setCellValue('C' . $row, $item->kategori_nama);
            $row++;
            $no++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $kategori = KategoriModel::all();
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Data Kategori ' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Kategori');
        $sheet->setCellValue('B1', 'Nama Kategori');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'KTG001');
        $sheet->setCellValue('B2', 'Kategori 1');
        $sheet->setCellValue('A3', 'KTG002');
        $sheet->setCellValue('B3', 'Kategori 2');
        foreach (range('A', 'B') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_kategori.xlsx';
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
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_kategori');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            foreach ($data as $row => $value) {
                if ($row > 1) {
                    $insert[] = [
                        'kategori_kode' => $value['A'],
                        'kategori_nama' => $value['B'],
                        'created_at' => now(),
                    ];
                }
            }
            if (count($insert) > 0) {
                KategoriModel::insertOrIgnore($insert);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        }
        return redirect('/kategori');
    }
}
