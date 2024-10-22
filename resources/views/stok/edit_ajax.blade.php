<form id="formEditStok" method="POST" action="{{ url('stok/' . $stok->stok_id . '/update_ajax') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="barang_id">Barang</label>
        <select name="barang_id" id="barang_id" class="form-control" required>
            <option value="">Pilih Barang</option>
            @foreach($barang as $item)
                <option value="{{ $item->barang_id }}" {{ $stok->barang_id == $item->barang_id ? 'selected' : '' }}>
                    {{ $item->barang_nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="stok_tanggal">Tanggal</label>
        <input type="datetime-local" class="form-control" id="stok_tanggal" name="stok_tanggal"
               value="{{ date('Y-m-d\TH:i', strtotime($stok->stok_tanggal)) }}" required>
    </div>

    <div class="form-group">
        <label for="stok_jumlah">Jumlah</label>
        <input type="number" class="form-control" id="stok_jumlah" name="stok_jumlah"
               value="{{ $stok->stok_jumlah }}" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

<script>
    $('#formEditStok').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.status) {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success('Data berhasil diupdate');
                } else {
                    toastr.error('Gagal mengupdate data');
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Terjadi kesalahan sistem');
                }
            }
        });
    });
</script>
