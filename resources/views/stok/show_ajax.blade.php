<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th width="200">ID Stok</th>
            <td>{{ $stok->stok_id }}</td>
        </tr>
        <tr>
            <th>Barang</th>
            <td>{{ $stok->barang->barang_nama }}</td>
        </tr>
        <tr>
            <th>User</th>
            <td>{{ $stok->user->username }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ date('d/m/Y H:i', strtotime($stok->stok_tanggal)) }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $stok->stok_jumlah }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $stok->created_at ? date('d/m/Y H:i', strtotime($stok->created_at)) : '-' }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $stok->updated_at ? date('d/m/Y H:i', strtotime($stok->updated_at)) : '-' }}</td>
        </tr>
    </table>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
