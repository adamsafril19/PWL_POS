@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Kode Penjualan</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $penjualan->user->username ?? 'N/A' }}</td>
                    </tr>
                </table>

                <h5 class="mt-4">Detail Items</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @forelse($penjualan->details as $index => $detail)
                            @php
                                $subtotal = $detail->jumlah * $detail->harga;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->barang->barang_nama ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data detail</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty

<script>
$(document).ready(function() {
    // Tambahkan efek animasi saat modal muncul
    $('.table-responsive').hide().fadeIn('slow');

    // Tambahkan efek hover pada baris tabel
    $('.table-bordered tr').hover(
        function() {
            $(this).addClass('table-active');
        },
        function() {
            $(this).removeClass('table-active');
        }
    );

    // Handle tombol tutup
    $('.modal-footer .btn-secondary, .close').on('click', function() {
        $('#myModal').modal('hide');
    });
});
</script>

<style>
/* Tambahan style untuk animasi hover */
.table-bordered tr {
    transition: all 0.3s ease;
}

.table-active {
    background-color: rgba(0,0,0,.075);
}

/* Style untuk modal header */
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

/* Style untuk tabel */
.table-responsive {
    margin-top: 15px;
}

.table th {
    background-color: #f8f9fa;
}

/* Style untuk footer */
.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
</style>
