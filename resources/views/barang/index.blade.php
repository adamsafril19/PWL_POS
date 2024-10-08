@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id">
                            <option value="">- Semua -</option>
                            @foreach($kategori as $ktg)
                                <option value="{{ $ktg->kategori_id }}">{{ $ktg->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="m_barang">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kode Barang</th>
                <th>Nama</th>
                <th>Kategori Barang</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    var dataBarang = $('#m_barang').DataTable({
        serverSide: true,
        ajax: {
            url: "{{ route('barang.list') }}",
            type: "POST",
            data: function(d) {
                d._token = "{{ csrf_token() }}";
                d.kategori_id = $('#kategori_id').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'barang_kode', name: 'barang_kode'},
            {data: 'barang_nama', name: 'barang_nama'},
            {data: 'kategori.kategori_nama', name: 'kategori.kategori_nama'},
            {data: 'harga_beli', name: 'harga_beli'},
            {data: 'harga_jual', name: 'harga_jual'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ]
    });

    $('#kategori_id').on('change', function(){
        dataBarang.ajax.reload();
    });
});
</script>
@endpush
