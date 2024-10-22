<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok barang dalam sistem'
        ];

        $activeMenu = 'stok';
        $barang = BarangModel::all();

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['barang', 'user']);

        if($request->barang_id) {
            $stok->where('barang_id', $request->barang_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('barang_nama', function ($stok) {
                return $stok->barang ? $stok->barang->barang_nama : '-';
            })
            ->addColumn('username', function ($stok) {
                return $stok->user ? $stok->user->username : '-';
            })
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url("stok/$stok->stok_id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("stok/$stok->stok_id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok', $stok->stok_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah stok baru'
        ];

        $barang = BarangModel::all();
        $activeMenu = 'stok';

        return view('stok.create_ajax', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer'
        ]);

        StokModel::create([
            'barang_id' => $request->barang_id,
            'user_id' => auth()->id(),
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show_ajax($id)
    {
        try {
            $stok = StokModel::with(['barang', 'user'])->find($id);

            if (!$stok) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data stok tidak ditemukan'
                ], 404);
            }

            return view('stok.show_ajax', ['stok' => $stok]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit_ajax($id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::all();

        return view('stok.edit_ajax', [
            'stok' => $stok,
            'barang' => $barang
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $rules = [
                    'barang_id' => 'required|integer',
                    'stok_tanggal' => 'required|date',
                    'stok_jumlah' => 'required|integer'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                $stok = StokModel::find($id);

                if (!$stok) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok tidak ditemukan'
                    ]);
                }

                $stok->update([
                    'barang_id' => $request->barang_id,
                    'stok_tanggal' => $request->stok_tanggal,
                    'stok_jumlah' => $request->stok_jumlah
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diupdate'
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

    public function destroy($id)
    {
        $stok = StokModel::find($id);

        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            $stok->delete();
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus');
        }
    }

    public function import()
    {
        return view('stok.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_stok'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $tanggalExcel = $value['D'];
                        if (is_numeric($tanggalExcel)) {
                            // Jika berbentuk serial number, konversi ke tanggal
                            $tanggal = Date::excelToDateTimeObject($tanggalExcel)->format('Y-m-d h:i:s');
                        } else {
                            // Jika berbentuk string, asumsikan sudah dalam format Y-m-d atau format lain yang valid
                            $tanggal = date('Y-m-d h:i:s', strtotime($tanggalExcel));
                        }
                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'user_id' => $value['C'],
                            'stok_tanggal' => $tanggal,
                            'stok_jumlah' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    StokModel::insertOrIgnore($insert);
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
        return redirect('/');
    }
    public function export_excel()
    {
        $stok = Stokmodel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif
        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama supplier');
        $sheet->setCellValue('C1', 'Nama barang');
        $sheet->setCellValue('D1', 'Nama user');
        $sheet->setCellValue('E1', 'Tanggal stok');
        $sheet->setCellValue('F1', 'Jumlah stok');
        // Buat header menjadi bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier->supplier_nama);
            $sheet->setCellValue('C' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->user->username);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $baris++;
            $no++;
        }
        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        // Set judul sheet
        $sheet->setTitle('Data stok');
        // Buat writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data stok ' . date('Y-m-d H:i:s') . '.xlsx';
        // Atur Header untuk Download File Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        // Simpan file dan kirim ke output
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
{
    $stok = StokModel::with(['barang', 'user']) // Menggunakan relasi untuk mengambil data barang dan user
        ->get();

    $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
    $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
    $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar
    $pdf->render();

    return $pdf->stream('Data_stok_' . date('Y-m-d_H-i-s') . '.pdf');
}

}
