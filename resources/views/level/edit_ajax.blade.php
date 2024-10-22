@empty($level)
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
            <a href="{{ url('/level') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/level/' . $level->level_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Level</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi</h5>
                    Silakan perbarui data level di bawah ini.
                </div>
                <div class="form-group">
                    <label for="level_kode">Kode Level</label>
                    <input type="text" name="level_kode" id="level_kode" class="form-control" value="{{ $level->level_kode }}" required>
                </div>
                <div class="form-group">
                    <label for="level_nama">Nama Level</label>
                    <input type="text" name="level_nama" id="level_nama" class="form-control" value="{{ $level->level_nama }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit").validate({
        rules: {
            level_kode: {
                required: true,
                minlength: 3
            },
            level_nama: {
                required: true
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
                    dataType: 'json', // Tambahkan ini untuk memastikan response diparse sebagai JSON
                    beforeSend: function() {
                        // Disable submit button
                        $('button[type="submit"]').prop('disabled', true);
                        // Reset error messages
                        $('.error-text').text('');
                    },
                    success: function(response) {
                        if(response.status) {
                            // Hide modal
                            $('#myModalLevel').modal('hide');

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil diupdate',
                                showConfirmButton: true
                            }).then((result) => {
                                // Reload DataTable setelah level klik OK
                                if (typeof dataLevel !== 'undefined') {
                                    dataLevel.ajax.reload(null, false); // Reload DataTable tanpa refresh halaman
                                } else {
                                    console.error('DataTable untuk level tidak ditemukan');
                                }
                            });
                        } else {
                            // Clear previous errors
                            $('.error-text').text('');

                            // Show validation errors if any
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-'+prefix).text(val[0]);
                                });
                            }

                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message || 'Gagal mengupdate data',
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server: ' + error,
                            showConfirmButton: true
                        });
                    },
                    complete: function() {
                        // Enable submit button
                        $('button[type="submit"]').prop('disabled', false);
                    }
                });
                return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
