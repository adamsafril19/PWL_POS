@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, Apa kabar!!!</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        Selamat datang semua, ini adalah halaman utama dari aplikasi ini

        @if(Auth::check())
            <!-- Debug info dalam card terpisah -->
            <div class="card mt-4">
                <div class="card-header bg-info">
                    <h3 class="card-title">Debug Information</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">User ID</dt>
                        <dd class="col-sm-9">{{ Auth::user()->user_id }}</dd>

                        <dt class="col-sm-3">Username</dt>
                        <dd class="col-sm-9">{{ Auth::user()->username }}</dd>

                        <dt class="col-sm-3">Level ID</dt>
                        <dd class="col-sm-9">{{ Auth::user()->level_id }}</dd>

                        <dt class="col-sm-3">Role Code</dt>
                        <dd class="col-sm-9">{{ Auth::user()->getRole() }}</dd>

                        <dt class="col-sm-3">Role Name</dt>
                        <dd class="col-sm-9">{{ Auth::user()->getRoleName() }}</dd>

                        <dt class="col-sm-3">Level Details</dt>
                        <dd class="col-sm-9">
                            @if(Auth::user()->level)
                                <ul class="list-unstyled">
                                    <li>Level ID: {{ Auth::user()->level->level_id }}</li>
                                    <li>Level Kode: {{ Auth::user()->level->level_kode }}</li>
                                    <li>Level Nama: {{ Auth::user()->level->level_nama }}</li>
                                </ul>
                            @else
                                <span class="text-danger">No Level Data Found</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Script untuk logging ke console -->
            <script>
                console.log('User Authentication Details:', {
                    user_id: '{{ Auth::user()->user_id }}',
                    username: '{{ Auth::user()->username }}',
                    level_id: '{{ Auth::user()->level_id }}',
                    role: '{{ Auth::user()->getRole() }}',
                    role_name: '{{ Auth::user()->getRoleName() }}',
                    level: @json(Auth::user()->level)
                });
            </script>
        @else
            <div class="alert alert-warning">
                User tidak terautentikasi
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Additional debugging information
    $(document).ready(function() {
        @if(Auth::check())
            // Check if user has admin role
            const isAdmin = '{{ Auth::user()->getRole() }}' === 'ADM';
            console.log('Is Admin:', isAdmin);

            // Log current user permissions
            $.get('/check-user/{{ Auth::user()->username }}', function(response) {
                console.log('User Details from API:', response);
            }).fail(function(error) {
                console.error('Error fetching user details:', error);
            });
        @endif
    });
</script>
@endpush
