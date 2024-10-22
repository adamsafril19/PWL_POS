<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Point of Sale</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>POINT OF SALE</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new account</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" id="form-register">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('nama') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <select class="form-control @error('level_id') is-invalid @enderror" id="level_id" name="level_id">
                            <option value="">Pilih Role</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->level_id }}" {{ old('level_id') == $level->level_id ? 'selected' : '' }}>
                                    {{ $level->level_kode }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-tag"></span>
                            </div>
                        </div>
                        @error('level_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <a href="{{ route('login') }}" class="text-center">Sudah punya akun</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="adminlte/dist/js/adminlte.min.js"></script>

    <script>
    $(document).ready(function() {
    // Debug untuk memastikan level options
    console.log('Available levels:', $('#level_id option').map(function() {
        return { value: $(this).val(), text: $(this).text() };
    }).get());

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Tambahkan error handler global untuk AJAX
    $(document).ajaxError(function(event, xhr, settings) {
        if (xhr.status === 419) { // CSRF token mismatch
            Swal.fire({
                icon: 'error',
                title: 'Session Expired',
                text: 'Halaman akan dimuat ulang untuk memperbarui session.',
                showCancelButton: false,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        }
    });

    // Tambahkan event listener untuk select
    $('#level_id').on('change', function() {
        console.log('Selected level:', $(this).val());
        $(this).valid();
    });

    $("#form-register").validate({
        rules: {
            username: {
                required: true,
                minlength: 4,
                maxlength: 20
            },
            nama: {
                required: true,
                minlength: 3
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
            level_id: {
                required: true
            }
        },
        messages: {
            username: {
                required: "Username wajib diisi",
                minlength: "Username minimal 4 karakter",
                maxlength: "Username maksimal 20 karakter"
            },
            nama: {
                required: "Nama lengkap wajib diisi",
                minlength: "Nama minimal 3 karakter"
            },
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 6 karakter",
                maxlength: "Password maksimal 20 karakter"
            },
            password_confirmation: {
                required: "Konfirmasi password wajib diisi",
                equalTo: "Konfirmasi password tidak sama"
            },
            level_id: {
                required: "Role wajib dipilih"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            if (element.prop('type') === 'select-one') {
                error.insertAfter(element.closest('.input-group'));
            } else {
                element.closest('.input-group').append(error);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        ssubmitHandler: function(form) {
            // Tambahkan CSRF token ke form data
            var formData = new FormData(form);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: $(form).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: response.message,
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message || 'An error occurred during registration.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 419) { // CSRF token mismatch
                        Swal.fire({
                            icon: 'error',
                            title: 'Session Expired',
                            text: 'Halaman akan dimuat ulang untuk memperbarui session.',
                        }).then(() => {
                            window.location.reload();
                        });
                        return;
                    }

                    console.log('Error response:', xhr.responseJSON);
                    var errorMessage = 'An unexpected error occurred. Please try again later.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    // Tampilkan error validasi jika ada
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errorList = '<ul>';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorList += '<li>' + value[0] + '</li>';
                        });
                        errorList += '</ul>';
                        errorMessage = errorList;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Error',
                        html: errorMessage
                    });
                }
            });
            return false;
        }
    });
});
    </script>
</body>
</html>
