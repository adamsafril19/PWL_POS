@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-info" onclick="modalAction('{{ url('/level/import/') }}')">
                <i class="fa fa-file-import"></i> Import Data
            </button>
            <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Ajax
            </button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ url('/level/export_excel/') }}'">
                <i class="fa fa-file-excel"></i> Export to Excel
            </button>
            <button type="button" class="btn btn-warning" onclick="window.location.href='{{ url('/level/export_pdf/') }}'">
                <i class="fa fa-file-pdf"></i> Export to PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm"
    id="m_level">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModalLevel" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
     // Deklarasikan dataLevel sebagai variabel global
     var dataLevel;

    function modalAction(url = ''){
        $('#myModalLevel').load(url,function(){
            $('#myModalLevel').modal('show');
        });
    }

    $(document).ready(function() {
            dataLevel = $('#m_level').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ route('level.list') }}",
                "type": "POST",
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns:[
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "level_kode",
                    name: "level_kode",
                },
                {
                    data: "level_nama",
                    name: "level_nama"
                },
                {
                    data: "aksi",
                    name: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
