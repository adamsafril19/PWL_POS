@extends('layouts.template')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{ asset('images/pfp/logo.jpg' . auth()->user()->avatar) }}"
                class="img-thumbnail mb-3"
                alt="Profile Picture"
                style="width: 150px; height: 150px; object-fit: cover;"
                id="profile-pic">
        </div>
        <div class="col-md-9">
            <form>
                <div class="form-group row">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" value="{{ auth()->user()->username }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" value="{{ auth()->user()->nama }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" value="********" readonly>
                    </div>
                </div>
            </form>
            <button class="btn btn-primary mt-3" onclick="changeProfilePic()">
                Ubah Foto Profil
            </button>
            <button class="btn btn-primary mt-3" onclick="manageProfile()">
                Kelola Profil
            </button>
        </div>
    </div>
</div>
<div id="profile-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
</div>
@endsection
@push('js')
<script>
    function changeProfilePic() {
        $('#profile-modal').load('{{ url("/profile/change-photo") }}', function() {
            $('#profile-modal').modal('show');
        });
    }
    function manageProfile() {
        $('#profile-modal').load('{{ url("/profile/manage") }}', function() {
            $('#profile-modal').modal('show');
        });
    }
</script>
@endpush
