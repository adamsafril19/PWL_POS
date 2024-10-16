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
                <h5 class="modal-title" id="exampleModalLabel">Edit Data level</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="form-group">
                    <label>Kode</label>
                    <input value="{{ $level->level_kode }}" type="text" name="level_kode" id="level_kode"
                    class="form-control" required>
                    <small id="error-level_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="{{ $level->level_nama }}" type="text" name="level_nama" id="level_nama" class="form-control" required>
                    <small id="error-level_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
@endempty

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                level_kode: {required: true, minlength: 3, maxlength: 20},
                level_nama: {required: true, minlength: 3, maxlength: 100},
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
                            $('#myModal').modal('hide');

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil diupdate',
                                showConfirmButton: true
                            }).then((result) => {
                                // Reload DataTable setelah level klik OK
                                if (typeof datalevel !== 'undefined') {
                                    datalevel.ajax.reload(null, false);
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
