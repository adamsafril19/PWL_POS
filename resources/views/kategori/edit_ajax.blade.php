
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
                Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/kategori') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/kategori/' . $kategori->kategori_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Kategori</label>
                    <input value="{{ $kategori->kategori_kode }}" type="text" name="kategori_kode" id="kategori_kode"
                        class="form-control" required>
                    <small id="error-kategori_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input value="{{ $kategori->kategori_nama }}" type="text" name="kategori_nama" id="kategori_nama"
                        class="form-control" required>
                    <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
        $("#form-edit").validate({
        rules: {
            kategori_kode: {
                required: true,
                minlength: 3,
                maxlength: 10
            },
            kategori_nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            }
        },
        messages: {
            kategori_kode: {
                required: "Kode kategori harus diisi",
                minlength: "Minimal 3 karakter",
                maxlength: "Maksimal 10 karakter"
            },
            kategori_nama: {
                required: "Nama kategori harus diisi",
                minlength: "Minimal 3 karakter",
                maxlength: "Maksimal 100 karakter"
            }
        },
        submitHandler: function(form) {
            // Tambahkan CSRF token ke header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true);
                    $('.error-text').text('');
                },
                success: function(response) {
                    if(response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil diupdate',
                            showConfirmButton: true
                        }).then((result) => {
                            if (typeof dataKategori !== 'undefined') {
                                dataKategori.ajax.reload(null, false);
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
                            text: response.message || 'Gagal mengupdate data',
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server: ' + error,
                        showConfirmButton: true
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
@endempty
