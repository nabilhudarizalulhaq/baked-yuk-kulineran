<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .error {
            border-color: red !important;
        }

        .success {
            border-color: green !important;
        }

        form {
            max-width: 500px;
            margin: auto;
            padding: 2rem;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            background: #f8f9fa;
        }

        .form-title {
            margin-bottom: 2rem;
            text-align: center;
        }
    </style>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="form-title">Verifikasi Akun</h2>
            <form method="POST" action="{{ route('activation') }}">
                @csrf <!-- Tambahkan token CSRF untuk keamanan -->
                <h4>Silahkan tekan tombol di bawah ini untuk mengverifikasi akun anda!</h4>
                <input type="hidden" id="id" name="id" value="{{$id}}">

                <!-- Uncomment these if you want to add them back
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" required class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" required class="form-control" id="password" name="password" placeholder="Password" oninput="checkPassword()">
                    <div id="passwordMessage" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="rePassword" class="form-label">Retype Password</label>
                    <input type="password" required class="form-control" id="rePassword" name="rePassword" placeholder="Retype Password" oninput="checkPassword()">
                </div>
                -->

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script>
    @if (session('status'))
        Swal.fire({
            title: "Success!",
            text: "{{ session('status') }}",
            icon: "success",
            button: "OK",
        });
    @endif
</script>

</body>
</html>
