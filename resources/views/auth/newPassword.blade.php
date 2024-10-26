<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        /* Ganti warna border menjadi merah saat ada kesalahan */
        .error {
            border-color: red !important;
        }

        /* Ganti warna border menjadi hijau saat benar */
        .success {
            border-color: green !important;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 2rem;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            background-color: #f8f9fa;
        }

        .form-title {
            margin-bottom: 2rem;
            text-align: center;
            color: #495057;
        }
    </style>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h2 class="form-title">Password Baru</h2>
                <form method="POST" action="{{ route('newPassword') }}">
                    @csrf <!-- Tambahkan token CSRF untuk keamanan -->
                    <input type="hidden" id="id" name="id" value="{{$id}}">

                    <!-- Uncomment if needed
                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" required class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                    </div>
                    -->

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" required class="form-control" id="password" name="password" placeholder="Password" oninput="checkPassword()">
                        <div id="passwordMessage" class="form-text text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="rePassword" class="form-label">Retype Password</label>
                        <input type="password" required class="form-control" id="rePassword" name="rePassword" placeholder="Retype Password" oninput="checkPassword()">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script>
    function checkPassword() {
        var password = document.getElementById("password").value;
        var rePassword = document.getElementById("rePassword").value;
        var submitButton = document.getElementById("submitButton");
        var passwordMessage = document.getElementById("passwordMessage");

        // Pengecekan panjang minimal 8 karakter
        if (password.length < 8) {
            passwordMessage.innerHTML = "Password harus memiliki minimal 8 karakter.";
            submitButton.disabled = true;
            return;
        } else {
            passwordMessage.innerHTML = "";
        }

        // Pengecekan apakah password memenuhi kriteria huruf, angka, dan karakter khusus
        // var regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
        // if (!regex.test(password)) {
        //     passwordMessage.innerHTML = "Password harus terdiri dari huruf, angka, dan karakter khusus.";
        //     submitButton.disabled = true;
        //     return;
        // } else {
        //     passwordMessage.innerHTML = "";
        // }

        // Jika password dan retype password sama, ubah border menjadi hijau
        if (password === rePassword) {
            document.getElementById("password").classList.remove("error");
            document.getElementById("rePassword").classList.remove("error");
            document.getElementById("password").classList.add("success");
            document.getElementById("rePassword").classList.add("success");
            submitButton.disabled = false;
        } else {
            document.getElementById("password").classList.remove("success");
            document.getElementById("rePassword").classList.remove("success");
            document.getElementById("password").classList.add("error");
            document.getElementById("rePassword").classList.add("error");
            submitButton.disabled = true;
        }
    }

    function validateForm() {
        var password = document.getElementById("password").value;
        var rePassword = document.getElementById("rePassword").value;

        if (password !== rePassword) {
            alert("Password dan Retype Password harus sama!");
            return false;
        }
        return true;
    }

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
