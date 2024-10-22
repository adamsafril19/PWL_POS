@empty($barang)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/barang/' . $barang->barang_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input value=" {{ $barang->barang_kode }}" type="text" name="barang_kode" id="barang_kode"
                            class="form-control" required>
                        <small id="error-barang_kode" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input value="{{ $barang->barang_nama }}" type="text" name="barang_nama" id="barang_nama"
                            class="form-control" required>
                        <small id="error-barang_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kategori Barang</label>
                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                            @foreach ($kategori as $l)
                            <option {{ $l->kategori_id == $barang->kategori_id ? 'selected' : '' }}
                                value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-level_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input value="{{ $barang->harga_beli }}" type="number" name="harga_beli" id="harga_beli" class="form-control"
                            required>
                        <small id="error-harga_beli" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <input value="{{ $barang->harga_jual }}" type="number" name="harga_jual" id="harga_jual" class="form-control"
                            required>
                        <small id="error-harga_jual" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    kategori_id: {
                        required: true,
                        number: true
                    },
                    barang_kode: {
                        required: true,
                        minlength: 3,
                        maxlength: 10
                    },
                    barang_nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    harga_beli: {
                        required: true,
                        number: true
                    },
                    harga_jual: {
                        required: true,
                        number: true
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
                            $('#myModal').modal('hide');

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil diupdate',
                                showConfirmButton: true
                            }).then((result) => {
                                // Reload DataTable setelah level klik OK
                                if (typeof tableBarang !== 'undefined') {
                                    tableBarang.ajax.reload(null, false); // Reload DataTable tanpa refresh halaman
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
