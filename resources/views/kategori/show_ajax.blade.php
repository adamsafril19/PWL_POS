@empty($kategori)
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
                Data kategori yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/kategori') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Kategori</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Kode Kategori</th>
                        <td>{{ $kategori->kategori_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kategori</th>
                        <td>{{ $kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ \Carbon\Carbon::parse($kategori->created_at)->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ \Carbon\Carbon::parse($kategori->updated_at)->format('d-m-Y H:i:s') }}</td>
                    </tr>
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

    // Optional: Tambahkan efek hover pada baris tabel
    $('.table-bordered tr').hover(
        function() {
            $(this).addClass('table-active');
        },
        function() {
            $(this).removeClass('table-active');
        }
    );
});
</script>
