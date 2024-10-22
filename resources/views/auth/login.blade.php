<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Pengguna</title>

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
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>POINT OF SALE</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <!-- Tambahkan error message display -->
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

                <!-- Debug info untuk user yang sudah login -->
                @if(Auth::check())
                    <div class="alert alert-info">
                        Debug Info:
                        <ul>
                            <li>User ID: {{ Auth::user()->user_id }}</li>
                            <li>Username: {{ Auth::user()->username }}</li>
                            <li>Level ID: {{ Auth::user()->level_id }}</li>
                            <li>Role: {{ Auth::user()->getRole() }}</li>
                            <li>Role Name: {{ Auth::user()->getRoleName() }}</li>
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
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
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                    <p class="mb-1">
                        <a href="{{ route('register') }}" class="text-center">Register akun baru</a>
                    </p>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#form-login").validate({
        rules: {
            username: {
                required: true,
                minlength: 4,
                maxlength: 20
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            }
        },
        messages: {
            username: {
                required: "Please enter a username",
                minlength: "Your username must be at least 4 characters long",
                maxlength: "Your username cannot be longer than 20 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                maxlength: "Your password cannot be longer than 20 characters"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: "POST",
                data: $(form).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        // Tambahkan debug info ke console
                        console.log('Login Response:', response);
                        if(response.user) {
                            console.log('User Info:', {
                                id: response.user.id,
                                username: response.user.username,
                                role: response.user.role,
                                role_name: response.user.role_name
                            });
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: response.message,
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    } else {
                        console.error('Login Failed:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: response.message || 'An error occurred during login.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Login Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });

                    var errorMessage = 'An unexpected error occurred. Please try again later.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Error',
                        text: errorMessage
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
