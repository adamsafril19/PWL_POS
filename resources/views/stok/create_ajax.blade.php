<form id="formStok" method="POST" action="{{ url('stok') }}">
    @csrf
    <div class="form-group">
        <label for="barang_id">Barang</label>
        <select name="barang_id" id="barang_id" class="form-control" required>
            <option value="">Pilih Barang</option>
            @foreach($barang as $item)
                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="stok_tanggal">Tanggal</label>
        <input type="datetime-local" class="form-control" id="stok_tanggal" name="stok_tanggal" required>
    </div>

    <div class="form-group">
        <label for="stok_jumlah">Jumlah</label>
        <input type="number" class="form-control" id="stok_jumlah" name="stok_jumlah" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $('#formStok').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.status) {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success('Data berhasil disimpan');
                } else {
                    toastr.error('Gagal menyimpan data');
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
