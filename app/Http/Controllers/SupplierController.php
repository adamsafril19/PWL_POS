<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\supplierModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
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
                $btn = '<button onclick="modalAction(\'' . url("supplier/$supplier->supplier_id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("supplier/$supplier->supplier_id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validation rules
        $rules = [
            'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:255',
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
                'supplier_kode' => $request->supplier_kode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
            ];

            // Save data
            supplierModel::create($data);

            // Success response
            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    return response()->json([
        'status' => false,
        'message' => 'Invalid request method'
    ], 400);
}


    public function create_ajax()
    {
        return view('supplier.create_ajax');
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

    public function show_ajax($id)
    {
        try {

            $supplier = SupplierModel::find($id);
            if (!$supplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan'
                ], 404);
            }

            // Ambil data level untuk dropdown jika diperlukan


            return view('supplier.show_ajax', [
                'supplier' => $supplier
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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

        public function edit_ajax(string $id)
        {
            $supplier = SupplierModel::find($id);

            return view('supplier.edit_ajax', ['supplier' => $supplier]);
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
public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        try {
            // Validasi input
            $rules = [
                'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil data supplier yang akan diupdate
            $supplier = SupplierModel::find($id);

            if (!$supplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier tidak ditemukan'
                ]);
            }

            // Update data supplier
            $supplier->supplier_kode = $request->supplier_kode;
            $supplier->supplier_nama = $request->supplier_nama;
            $supplier->supplier_alamat = $request->supplier_alamat;
            $supplier->save();

            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil diupdate',
                'data' => [
                    'supplier_kode' => $supplier->supplier_kode,
                    'supplier_nama' => $supplier->supplier_nama,
                    'supplier_alamat' => $supplier->supplier_alamat
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

        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari Ajax atau menginginkan JSON response
            if ($request->ajax() || $request->wantsJson()) {
                // Cari user berdasarkan ID
                $supplier = SupplierModel::find($id);
                if ($supplier) {
                    // Hapus data user
                    $supplier->delete();
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
            $supplier = SupplierModel::find($id);
            return view('supplier/confirm_ajax', ['supplier' => $supplier]);
        }

        public function import()
    {
        return view('supplier.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $file = $request->file('file_supplier');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $row => $value) {
                    if ($row > 1) {
                        $insert[] = [
                            'supplier_kode' => $value['A'],
                            'supplier_nama' => $value['B'],
                            'supplier_alamat' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport',
                ]);
            }
        }
        return redirect('/supplier');
    }
    public function export_excel()
    {
        $suppliers = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')
            ->orderBy('supplier_kode')
            ->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Supplier');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Alamat Supplier');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $row = 2;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $supplier->supplier_kode);
            $sheet->setCellValue('B' . $row, $supplier->supplier_nama);
            $sheet->setCellValue('C' . $row, $supplier->supplier_alamat);
            $row++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $filename = 'Data Supplier ' . date('Y-m-d H:i:s') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $suppliers = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')
            ->orderBy('supplier_kode')
            ->get();
        $pdf = Pdf::loadView('supplier.export_pdf', ['suppliers' => $suppliers]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Data Supplier ' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function template_supplier()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Supplier');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Alamat Supplier');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'SUP01');
        $sheet->setCellValue('B2', 'Supplier Satu');
        $sheet->setCellValue('C2', 'Alamat Supplier 1');
        $sheet->setCellValue('A3', 'SUP02');
        $sheet->setCellValue('B3', 'Supplier Dua');
        $sheet->setCellValue('C3', 'Alamat Supplier 2');
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $filename = 'template_supplier.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

}
