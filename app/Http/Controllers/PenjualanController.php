<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualanModel;
use App\Models\PenjualanModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar transaksi penjualan dalam sistem'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
{
    try {
        $penjualan = PenjualanModel::with([
            'user:user_id,username',
            'details:detail_id,penjualan_id,barang_id,jumlah,harga',
            'details.barang:barang_id,barang_nama'
        ]);

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_items', function ($penjualan) {
                return $penjualan->details->sum('jumlah');
            })
            ->addColumn('total_harga', function ($penjualan) {
                return $penjualan->details->sum(function($detail) {
                    return $detail->jumlah * $detail->harga;
                });
            })
            ->editColumn('penjualan_tanggal', function ($penjualan) {
                return date('d/m/Y H:i', strtotime($penjualan->penjualan_tanggal));
            })
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<button onclick="showDetail(\'' . url("penjualan/$penjualan->penjualan_id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="editData(\'' . url("penjualan/$penjualan->penjualan_id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
    } catch (\Exception $e) {
        \Log::error('Error in list method: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
    }
}

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah transaksi penjualan baru'
        ];

        $barang = BarangModel::all();
        $activeMenu = 'penjualan';

        // Generate kode penjualan
        $lastKode = PenjualanModel::orderBy('penjualan_id', 'desc')->first();
        $newKode = 'PJ' . date('Ymd') . '001';
        if ($lastKode) {
            $lastNumber = intval(substr($lastKode->penjualan_kode, -3));
            $newKode = 'PJ' . date('Ymd') . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'kode' => $newKode,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|unique:t_penjualan,penjualan_kode,' . ($id ?? ''),
                'penjualan_tanggal' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.barang_id' => 'required|exists:m_barang,barang_id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create penjualan
            $penjualan = PenjualanModel::create([
                'user_id' => auth()->id(),
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal
            ]);

            // Create detail penjualan
            foreach ($request->items as $item) {
                DetailPenjualanModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga']
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::beginTransaction();
            try {
                $validator = Validator::make($request->all(), [
                    'pembeli' => 'required|string|max:50',
                    'penjualan_tanggal' => 'required|date',
                    'items' => 'required|array|min:1',
                    'items.*.barang_id' => 'required|exists:m_barang,barang_id',
                    'items.*.jumlah' => 'required|integer|min:1',
                    'items.*.harga' => 'required|numeric|min:0'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // Generate kode penjualan
                $lastKode = PenjualanModel::orderBy('penjualan_id', 'desc')->first();
                $newKode = 'PJ' . date('Ymd') . '001';
                if ($lastKode) {
                    $lastNumber = intval(substr($lastKode->penjualan_kode, -3));
                    $newKode = 'PJ' . date('Ymd') . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                }

                // Create penjualan
                $penjualan = PenjualanModel::create([
                    'user_id' => auth()->id(),
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $newKode,
                    'penjualan_tanggal' => $request->penjualan_tanggal
                ]);

                // Create detail penjualan
                foreach ($request->items as $item) {
                    DetailPenjualanModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['harga']
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request method'
        ], 400);
    }
    public function show_ajax($id)
{
    try {
        $penjualan = PenjualanModel::with([
            'user:user_id,username',
            'details.barang:barang_id,barang_nama'
        ])->findOrFail($id);

        if (request()->ajax()) {
            return response()->view('penjualan.show_ajax', compact('penjualan'));
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request method'
        ], 400);
    } catch (\Exception $e) {
        \Log::error('Error in show_ajax: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'Gagal memuat data: ' . $e->getMessage()
        ], 500);
    }
}
    public function edit_ajax($id)
{
    $penjualan = PenjualanModel::with('details')->findOrFail($id);
    $barang = BarangModel::all();
    return view('penjualan.edit_ajax', compact('penjualan', 'barang'));
}

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::beginTransaction();
            try {
                $validator = Validator::make($request->all(), [
                    'pembeli' => 'required',
                    'penjualan_tanggal' => 'required|date',
                    'items' => 'required|array',
                    'items.*.barang_id' => 'required|exists:barang,id',
                    'items.*.jumlah' => 'required|integer|min:1',
                    'items.*.harga' => 'required|numeric|min:0',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $penjualan = new Penjualan();
                $penjualan->penjualan_kode = 'PJ' . date('YmdHis');
                $penjualan->pembeli = $request->pembeli;
                $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
                $penjualan->user_id = Auth::id();
                $penjualan->save();

                foreach ($request->items as $item) {
                    $detail = new PenjualanDetail();
                    $detail->penjualan_id = $penjualan->id;
                    $detail->barang_id = $item['barang_id'];
                    $detail->jumlah = $item['jumlah'];
                    $detail->harga = $item['harga'];
                    $detail->save();
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data penjualan: ' . $e->getMessage()
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
        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::find($id);
            if (!$penjualan) {
                return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
            }

            // Delete details first
            DetailPenjualanModel::where('penjualan_id', $id)->delete();

            // Delete penjualan
            $penjualan->delete();

            DB::commit();
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/penjualan')->with('error', 'Gagal menghapus data penjualan');
        }
    }

    public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $penjualan = Penjualan::findOrFail($id);
            $penjualan->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data penjualan: ' . $e->getMessage()
            ], 500);
        }
    }

    return response()->json([
        'status' => false,
        'message' => 'Invalid request method'
    ], 400);
}

    public function confirm_ajax($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }

    public function create_ajax()
{
    try {
        // Get active items with price
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')
                            ->orderBy('barang_nama')
                            ->get();

        // Generate new kode penjualan
        $lastKode = PenjualanModel::orderBy('penjualan_id', 'desc')->first();
        $newKode = 'PJ' . date('Ymd') . '001';
        if ($lastKode) {
            $lastNumber = intval(substr($lastKode->penjualan_kode, -3));
            $newKode = 'PJ' . date('Ymd') . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        if (request()->ajax()) {
            return response()->view('penjualan.create_ajax', [
                'barang' => $barang,
                'kode' => $newKode
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request method'
        ], 400);
    } catch (\Exception $e) {
        \Log::error('Error in create_ajax: ' . $e->getMessage());

        if (request()->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memuat form: ' . $e->getMessage()
            ], 500);
        }

        throw $e;
    }
}

public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024'] // validasi file harus xls atau xlsx, max 1MB
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_penjualan'); // ambil file dari request
        $reader = IOFactory::createReader('Xlsx'); // load reader file excel
        $reader->setReadDataOnly(true); // hanya membaca data
        $spreadsheet = $reader->load($file->getRealPath()); // load file excel
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $data = $sheet->toArray(null, false, true, true); // ambil data excel
        $insertPenjualan = [];
        $insertDetailPenjualan = [];

        if (count($data) > 1) { // jika data lebih dari 1 baris
            foreach ($data as $baris => $value) {
                if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                    // Buat data penjualan baru jika belum ada penjualan_id
                    $penjualan = PenjualanModel::create([
                        'user_id' => $value['A'], // kolom A adalah user_id
                        'pembeli' => $value['B'],  // kolom B adalah pembeli
                        'penjualan_kode' => $value['C'], // kolom C adalah penjualan_kode
                        'penjualan_tanggal' => $value['D'], // kolom D adalah penjualan_tanggal
                    ]);

                    // Masukkan detail transaksi penjualan
                    $insertDetailPenjualan[] = [
                        'penjualan_id' => $penjualan->penjualan_id, // dapatkan id penjualan yang baru dibuat
                        'barang_id' => $value['E'], // kolom E adalah barang_id
                        'jumlah' => $value['F'], // kolom F adalah jumlah
                        'harga' => $value['G'],  // kolom G adalah harga
                    ];
                }
            }

            // Masukkan data ke tabel detail penjualan
            if (count($insertDetailPenjualan) > 0) {
                DetailPenjualanModel::insertOrIgnore($insertDetailPenjualan);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diimport'
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

public function export_pdf()
{
    // Ambil data penjualan dengan relasi yang sesuai
    $penjualan = PenjualanModel::with(['user', 'details.barang']) // Muat relasi user dan detail penjualan dengan barang
        ->orderBy('penjualan_tanggal', 'desc') // Mengurutkan berdasarkan tanggal penjualan
        ->get();

    $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
    $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
    $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar
    $pdf->render();

    return $pdf->stream('Data_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
}


}
