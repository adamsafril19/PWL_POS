@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-info" onclick="modalAction('{{ url('/kategori/import') }}')">
                <i class="fa fa-file-import"></i> Import
            </button>
            <button onclick="modalAction('{{ route('kategori.create.ajax') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Ajax
            </button>
            <a href="{{ url('/kategori/export_excel') }}" class="btn btn-primary">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/kategori/export_pdf') }}" class="btn btn-warning">
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

        <table class="table table-bordered table-striped table-hover table-sm" id="m_kategori">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
var dataKategori;

function modalAction(url = '') {
    $('#myModal').load(url,function(){
        $('#myModal').modal('show');
    });
}

$(document).ready(function() {
    dataKategori = $('#m_kategori').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ route('kategori.list') }}",
            "type": "POST",
            "data": function(d) {
                d._token = "{{ csrf_token() }}";
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'kategori_kode',
                name: 'kategori_kode'
            },
            {
                data: 'kategori_nama',
                name: 'kategori_nama'
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>
@endpush
