<form action="{{ route('supplier.store_ajax') }}" method="POST" id="form-tambah-supplier">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- supplier_kode -->
                <div class="form-group">
                    <label for="supplier_kode">Kode Supplier</label>
                    <input type="text" name="supplier_kode" id="supplier_kode" class="form-control" required>
                    <small id="error-supplier_kode" class="error-text form-text text-danger"></small>
                </div>

                <!-- supplier_nama -->
                <div class="form-group">
                    <label for="supplier_nama">Nama Supplier</label>
                    <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
                    <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                </div>

                <!-- supplier_alamat -->
                <div class="form-group">
                    <label for="supplier_alamat">Alamat Supplier</label>
                    <textarea name="supplier_alamat" id="supplier_alamat" class="form-control" required></textarea>
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

<script>
    $(document).ready(function() {
        var dataTable; // Declare dataTable variable in a wider scope

        // Initialize DataTable
        function initializeDataTable() {
            dataTable = $('#table-supplier').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('supplier.list') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'supplier_kode', name: 'supplier_kode' },
                    { data: 'supplier_nama', name: 'supplier_nama' },
                    { data: 'supplier_alamat', name: 'supplier_alamat' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });
        }

        // Call the function to initialize DataTable
        initializeDataTable();

        $("#form-tambah-supplier").validate({
            rules: {
                supplier_kode: { required: true, minlength: 3, maxlength: 20 },
                supplier_nama: { required: true, minlength: 3, maxlength: 100 },
                supplier_alamat: { required: true, minlength: 5 }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            form.reset();
                            $('#myModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });

                            // Refresh DataTable
                            if (dataTable) {
                                dataTable.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server'
                        });
                    },
                    complete: function() {
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
