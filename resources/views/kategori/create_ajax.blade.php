<div class="modal-header">
    <h5 class="modal-title">Tambah Data Kategori</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form action="{{ route('kategori.store.ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Kode Kategori</label>
            <input type="text" name="kategori_kode" id="kategori_kode" class="form-control" required>
            <small id="error-kategori_kode" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="kategori_nama" id="kategori_nama" class="form-control" required>
            <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-tambah").validate({
        rules: {
            kategori_kode: {
                required: true,
                minlength: 2,
                maxlength: 10
            },
            kategori_nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            }
        },
        submitHandler: function(form) {
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('.error-text').text('');
                },
                success: function(response) {
                    if(response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data kategori berhasil disimpan'
                        }).then((result) => {
                            if (typeof $dataKategori !== 'undefined') {
                                $dataKategori.ajax.reload(null, false);
                            }
                        });
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-'+prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Gagal menyimpan data kategori'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server: ' + error
                    });
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false);
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
