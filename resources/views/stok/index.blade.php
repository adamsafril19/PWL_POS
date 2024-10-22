@extends('layouts.template')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $page->title }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import Stok</button>
                    <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Stok</a>
                    <a href="{{ url('stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Stok</a>
                    <button onclick="modalAction('{{ url('stok/create_ajax') }}')" class="btn btn-success">Tambah Stok</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Filter Barang:</label>
                        <select class="form-control" id="filter-barang">
                            <option value="">- Semua -</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <table id="t_stok" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    let table;

    $(document).ready(function() {
        table = $('#t_stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stok.list') }}",
                type: "POST",
                data: function(d) {
                    d.barang_id = $('#filter-barang').val();
                }
            },
            columns: [
                {data: 'stok_id', name: 'stok_id'},
                {data: 'barang_nama', name: 'barang_nama'},
                {data: 'username', name: 'username'},
                {data: 'stok_tanggal', name: 'stok_tanggal'},
                {data: 'stok_jumlah', name: 'stok_jumlah'},
                {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
            ]
        });

        $('#filter-barang').change(function() {
            table.ajax.reload();
        });
    });

    function modalAction(url) {
        $.get(url, function(data) {
            $('#modalForm .modal-body').html(data);
            $('#modalForm').modal('show');
        });
    }
</script>
@endpush
