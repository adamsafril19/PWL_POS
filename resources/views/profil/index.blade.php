@extends('layouts.template')
@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Profile Picture Section -->
                <div class="col-md-3 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('images/pp/cob.jpg' . auth()->user()->avatar) }}"
                            class="img-thumbnail rounded-circle mb-3"
                            alt="Profile Picture"
                            style="width: 200px; height: 200px; object-fit: cover;"
                            id="profile-pic">
                        <button class="btn btn-sm btn-primary position-absolute"
                                onclick="changeProfilePic()"
                                style="bottom: 20px; right: 20px;">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4 class="mt-2">{{ auth()->user()->nama ?? 'Nama Pengguna' }}</h4>
                    <p class="text-muted">{{ '@'.auth()->user()->username }}</p>
                    <button class="btn btn-outline-primary btn-block" onclick="manageProfile()">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </button>
                </div>

                <!-- Profile Information Section -->
                <div class="col-md-9">
                    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                                <i class="fas fa-user mr-2"></i>Informasi Pribadi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab">
                                <i class="fas fa-shield-alt mr-2"></i>Keamanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab">
                                <i class="fas fa-history mr-2"></i>Aktivitas
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <form>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Username</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control-plaintext" value="{{ auth()->user()->username }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control-plaintext" value="{{ auth()->user()->nama ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control-plaintext" value="{{ auth()->user()->email ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">No. Telepon</label>
                                            <div class="col-sm-9">
                                                <input type="tel" class="form-control-plaintext" value="{{ auth()->user()->phone ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Bergabung Sejak</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control-plaintext"
                                                    value="{{ auth()->user()->created_at ? auth()->user()->created_at->format('d F Y') : '-' }}" readonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Pengaturan Keamanan</h5>

                                    <div class="mb-4 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Password</h6>
                                            <p class="text-muted small mb-0">Terakhir diubah: {{ auth()->user()->password_changed_at ? auth()->user()->password_changed_at->diffForHumans() : 'Belum pernah' }}</p>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm" onclick="changePassword()">
                                            Ubah Password
                                        </button>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Verifikasi Dua Faktor</h6>
                                            <p class="text-muted small mb-0">Tingkatkan keamanan akun Anda</p>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="twoFactorToggle"
                                                {{ auth()->user()->two_factor_enabled ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="twoFactorToggle"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Riwayat Aktivitas</h5>
                                    <div class="timeline">
                                        @if(isset($lastLogin))
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Login Terakhir</h6>
                                                <p class="text-muted small mb-0">{{ $lastLogin->format('d F Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if(auth()->user()->updated_at && auth()->user()->updated_at != auth()->user()->created_at)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Profil Diperbarui</h6>
                                                <p class="text-muted small mb-0">{{ auth()->user()->updated_at->format('d F Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Akun Dibuat</h6>
                                                <p class="text-muted small mb-0">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d F Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="profile-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
<div id="password-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    .timeline {
        position: relative;
        padding: 1rem 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        top: 4px;
    }

    .timeline-content {
        border-left: 2px solid #e9ecef;
        padding-left: 1rem;
    }

    .custom-switch {
        padding-left: 2.25rem;
    }

    .custom-control-input {
        position: absolute;
        left: 0;
        z-index: -1;
        width: 1.75rem;
        height: 1.75rem;
    }
</style>
@endpush

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

    function changePassword() {
        $('#password-modal').load('{{ url("/profile/change-password") }}', function() {
            $('#password-modal').modal('show');
        });
    }

    // Handle two factor authentication toggle
    $('#twoFactorToggle').change(function() {
        if (this.checked) {
            alert('Fitur ini akan segera hadir!');
            $(this).prop('checked', false);
        }
    });

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
