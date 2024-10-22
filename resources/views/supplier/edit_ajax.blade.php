@empty($supplier)
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
            <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/supplier/'. $supplier->supplier_id.'/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input value="{{ $supplier->supplier_kode }}" type="text" name="supplier_kode" id="supplier_kode"
                    class="form-control" required>
                    <small id="error-supplier_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input value="{{ $supplier->supplier_nama }}" type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
                    <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Supplier</label>
                    <input value="{{ $supplier->supplier_alamat }}" type="text" name="supplier_alamat" id="supplier_alamat" class="form-control" required>
                    <small id="error-supplier_alamat" class="error-text form-text text-danger"></small>
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
                supplier_kode: { required: true, minlength: 3, maxlength: 20 },
                supplier_nama: { required: true, minlength: 3, maxlength: 100 },
                supplier_alamat: { required: true, minlength: 3, maxlength: 255 },
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
                    type: 'PUT',
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
                                if (typeof datasupplier !== 'undefined') {
                                    datasupplier.ajax.reload(null, false); // Reload DataTable tanpa refresh halaman
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
