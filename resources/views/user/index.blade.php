@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-info" onclick="modalAction('{{ url('/user/import/') }}')">
                <i class="fa fa-file-import"></i> Import User
            </button>
            <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Ajax
            </button>
            <a href="{{ url('/user/export_excel') }}" class="btn btn-primary">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning">
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
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm"
    id="m_user">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Level Pengguna</th>
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
    // Deklarasikan dataUser sebagai variabel global
    var dataUser;

    function modalAction(url = ''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        // Tambahkan setup untuk CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi dataUser sebagai variabel global
        dataUser = $('#m_user').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('user.list') }}",
                type: "POST",
                data: function(d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "username",
                    name: "username"
                },
                {
                    data: "nama",
                    name: "nama"
                },
                {
                    data: "level.level_nama",
                    name: "level.level_nama"
                },
                {
                    data: "aksi",
                    name: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#level_id').on('change', function(){
            dataUser.ajax.reload();
        });
    });
</script>
@endpush
