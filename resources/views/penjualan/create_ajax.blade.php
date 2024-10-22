<div class="modal-header">
    <h5 class="modal-title">Tambah Data Penjualan</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form id="form-tambah-penjualan" action="{{ route('penjualan.store_ajax') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="pembeli">Pembeli</label>
            <input type="text" name="pembeli" id="pembeli" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="penjualan_tanggal">Tanggal Penjualan</label>
            <input type="date" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>

        <div id="item-container">
            <div class="item-row">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label>Barang</label>
                        <select name="items[0][barang_id]" class="form-control barang-select" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}">
                                    {{ $b->barang_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="items[0][jumlah]" class="form-control jumlah-input" required min="1">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Harga</label>
                        <input type="number" name="items[0][harga]" class="form-control harga-input" required readonly>
                    </div>
                    <div class="form-group col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-remove-item">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success btn-sm" id="btn-add-item">
            <i class="fas fa-plus"></i> Tambah Item
        </button>

        <div class="form-group mt-3">
            <label for="total-harga">Total Harga</label>
            <input type="text" id="total-harga" class="form-control" readonly>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
$(document).ready(function() {

     // Initialize Select2 if available
     if (typeof $.fn.select2 !== 'undefined') {
        $('.barang-select').select2({
            theme: 'bootstrap',
            width: '100%',
            dropdownParent: $('#myModal'),
            placeholder: 'Pilih Barang'
        });
     }

    let itemCount = 0;

    // Handle barang selection change
    $(document).on('change', '.barang-select', function() {
        const selectedOption = $(this).find('option:selected');
        const harga = selectedOption.data('harga');
        $(this).closest('.item-row').find('.harga-input').val(harga);
        calculateTotal();
    });

    // Handle quantity change
    $(document).on('change keyup', '.jumlah-input', function() {
        calculateTotal();
    });

    // Add new item row
    $('#btn-add-item').click(function() {
        itemCount++;
        const newRow = $('.item-row:first').clone();

        // Reset values
        newRow.find('input').val('');
        newRow.find('select').val('');

        // Update input names
        newRow.find('[name]').each(function() {
            const oldName = $(this).attr('name');
            $(this).attr('name', oldName.replace('[0]', '[' + itemCount + ']'));
        });

        $('#item-container').append(newRow);
    });

    // Remove item row
    $(document).on('click', '.btn-remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        }
    });

    // Calculate total
    function calculateTotal() {
        let total = 0;
        $('.item-row').each(function() {
            const qty = parseFloat($(this).find('.jumlah-input').val()) || 0;
            const price = parseFloat($(this).find('.harga-input').val()) || 0;
            total += qty * price;
        });
        $('#total-harga').val(formatRupiah(total));
    }

    // Format currency
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(number);
    }

    // Form submission
    $('#form-tambah-penjualan').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500
                    });
                    dataPenjualan.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });
});
</script>
