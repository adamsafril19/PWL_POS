<form action="{{ url('penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="penjualan_kode">Kode Penjualan</label>
                    <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode"
                        value="{{ $penjualan->penjualan_kode }}" readonly>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="pembeli">Pembeli</label>
                    <input type="text" class="form-control" id="pembeli" name="pembeli"
                        value="{{ $penjualan->pembeli }}">
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="penjualan_tanggal">Tanggal</label>
                    <input type="datetime-local" class="form-control" id="penjualan_tanggal"
                        name="penjualan_tanggal" value="{{ date('Y-m-d\TH:i', strtotime($penjualan->penjualan_tanggal)) }}">
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <div class="items-container">
                    <h5>Items</h5>
                    <div class="items" id="items">
                        @foreach($penjualan->details as $index => $detail)
                            <div class="item-row mb-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select name="items[{{ $index }}][barang_id]" class="form-control barang-select">
                                            <option value="">Pilih Barang</option>
                                            @foreach($barang as $item)
                                                <option value="{{ $item->barang_id }}"
                                                    {{ $detail->barang_id == $item->barang_id ? 'selected' : '' }}
                                                    data-harga="{{ $item->harga_jual }}">
                                                    {{ $item->barang_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small id="error-items.{{ $index }}.barang_id" class="error-text form-text text-danger"></small>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="items[{{ $index }}][jumlah]" class="form-control item-qty"
                                            value="{{ $detail->jumlah }}" placeholder="Jumlah" min="1">
                                        <small id="error-items.{{ $index }}.jumlah" class="error-text form-text text-danger"></small>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="items[{{ $index }}][harga]" class="form-control item-price"
                                            value="{{ $detail->harga }}" placeholder="Harga">
                                        <small id="error-items.{{ $index }}.harga" class="error-text form-text text-danger"></small>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addItem">Tambah Item</button>
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
    let itemIndex = {{ count($penjualan->details) - 1 }};

    // Add new item row
    $('#addItem').click(function() {
        itemIndex++;
        const newRow = `
            <div class="item-row mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <select name="items[${itemIndex}][barang_id]" class="form-control barang-select">
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">
                                    {{ $item->barang_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-items.${itemIndex}.barang_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control item-qty"
                            placeholder="Jumlah" min="1">
                        <small id="error-items.${itemIndex}.jumlah" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[${itemIndex}][harga]" class="form-control item-price"
                            placeholder="Harga">
                        <small id="error-items.${itemIndex}.harga" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>
                    </div>
                </div>
            </div>
        `;
        $('#items').append(newRow);
    });

    // Auto-fill price when item is selected
    $(document).on('change', '.barang-select', function() {
        const harga = $(this).find(':selected').data('harga');
        $(this).closest('.row').find('.item-price').val(harga);
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
        }
    });

    // Form validation and submission
    $("#form-edit").validate({
        rules: {
            pembeli: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            penjualan_tanggal: {
                required: true
            },
            'items[].barang_id': {
                required: true
            },
            'items[].jumlah': {
                required: true,
                min: 1
            },
            'items[].harga': {
                required: true,
                min: 0
            }
        },
        submitHandler: function(form) {
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
                            text: response.message || 'Data penjualan berhasil diupdate',
                            showConfirmButton: true
                        }).then((result) => {
                            if (typeof dataPenjualan !== 'undefined') {
                                dataPenjualan.ajax.reload(null, false);
                            }
                        });
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-'+prefix.replace(/\./g, '\\.')).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Gagal mengupdate data penjualan',
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
