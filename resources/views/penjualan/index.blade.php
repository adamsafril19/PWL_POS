@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-info" onclick="modalAction('{{ url('/penjualan/import/') }}')">
                <i class="fa fa-file-import"></i> Import
            </button>
            <button onclick="modalAction('{{ route('penjualan.create_ajax') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Ajax
            </button>
            <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-primary">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="t_penjualan">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Total Items</th>
                <th>Total Harga</th>
                <th>User</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    var dataPenjualan;

    function modalAction(url = '') {
    // Show loading state
    $('#myModal').modal('show');
    $('#myModal .modal-content').html(`
        <div class="modal-body text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat form...</p>
        </div>
    `);

    // Fetch the form
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#myModal .modal-content').html(response);

            // Initialize Select2 if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('.barang-select').select2({
                    theme: 'bootstrap',
                    width: '100%',
                    dropdownParent: $('#myModal'),
                    placeholder: 'Pilih Barang'
                });
            }

            // Set current date using native JavaScript
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            $('#penjualan_tanggal').val(`${year}-${month}-${day}`);
        },
        error: function(xhr, status, error) {
            let errorMessage = 'Gagal memuat form';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            $('#myModal .modal-content').html(`
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">${errorMessage}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            `);
        }
    });
}

    function showDetail(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    function editData(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        dataPenjualan = $('#t_penjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('penjualan.list') }}",
                type: 'POST'
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {data: 'penjualan_kode', name: 't_penjualan.penjualan_kode'},
                {data: 'penjualan_tanggal', name: 't_penjualan.penjualan_tanggal'},
                {data: 'pembeli', name: 't_penjualan.pembeli'},
                {data: 'total_items', name: 'total_items', orderable: false, searchable: false},
                {
                    data: 'total_harga',
                    name: 'total_harga',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(data);
                    }
                },
                {data: 'user.username', name: 'user.username'},
                {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
            ],
            order: [[1, 'desc']]
        });

        // Handle delete
        $(document).on('submit', 'form[method="DELETE"]', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            dataPenjualan.ajax.reload();
                            Swal.fire(
                                'Terhapus!',
                                'Data berhasil dihapus.',
                                'success'
                            );
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
