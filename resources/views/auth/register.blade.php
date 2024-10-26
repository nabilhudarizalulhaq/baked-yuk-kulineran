<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Backend - Yuk Kulineran</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="img/logo.png" rel="icon" media="(prefers-color-scheme: light)">
  <link href="img/logo white.png" rel="icon" media="(prefers-color-scheme: dark)">
  <link href="img/logo.png" rel="apple-touch-icon" media="(prefers-color-scheme: light)">
  <link href="img/logo white.png" rel="apple-touch-icon" media="(prefers-color-scheme: dark)">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="css/adminlte.min.css">
  <style>
    .btn-primary {
      background-color: #236170 !important;
    }
    .card-primary.card-outline {
      border-top: 3px solid #236170;
    }
  </style>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <a href="img/logo.png"><b>Backend</b> Yuk Kulineran</a>
    </div>
    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Pendaftaran anggota baru</p>
        <form id="registerForm">
          @csrf
          <div class="input-group mb-3">
            <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus placeholder="Masukkan username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input id="full_name" type="text" class="form-control" name="full_name" required autocomplete="full_name" placeholder="Masukkan nama lengkap">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input id="email" type="email" class="form-control" name="email" required autocomplete="email" placeholder="Masukkan email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="Masukkan password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Masukkan konfirmasi password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input id="phone_number" type="text" class="form-control" name="phone_number" required autocomplete="phone_number" placeholder="Masukkan nomor handphone">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6 mx-auto">
              <button type="submit" class="btn btn-primary">Register</button>
            </div>
          </div>
        </form>
        <a href="{{ url('/login') }}" class="text-center">Saya sudah mempunyai akun</a>
      </div>
    </div>
  </div>
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/adminlte.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          url: '{{ route('register') }}',
          method: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            Swal.fire({
              title: 'Success!',
              text: response.message,
              icon: 'success',
              confirmButtonText: 'OK'
            });
            $('#registerForm')[0].reset();
          },
          error: function(response) {
            var errors = response.responseJSON.errors;
            $.each(errors, function(key, value) {
              $('#' + key).addClass('is-invalid');
              $('#' + key).after('<span class="invalid-feedback" role="alert"><strong>' + value[0] + '</strong></span>');
            });
          }
        });
      });
    });

    // JavaScript validation to allow only numbers in the phone_number field
    $(document).on('input', 'input[name="phone_number"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
  </script>
</body>
</html>
