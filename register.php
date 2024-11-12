<?php
include 'config/connection.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['nama_lengkap']);
    $nik = htmlspecialchars($_POST['nik']);
    $email = htmlspecialchars($_POST['email']);
    $no_telepon = htmlspecialchars($_POST['no_telepon']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi apakah password dan confirm password sama
    if ($password !== $confirm_password) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Gagal',
                    text: 'Password tidak cocok!',
                    confirmButtonText: 'Coba Lagi'
                }).then(() => {
                    window.location.href = 'register.php';
                });
            });
        </script>";
        exit();
    }

    // Periksa apakah email sudah terdaftar
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        // Jika email sudah terdaftar
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Registrasi Gagal',
                    text: 'Email sudah terdaftar!',
                    confirmButtonText: 'Coba Lagi'
                }).then(() => {
                    window.location.href = 'register.php';
                });
            });
        </script>";
    } else {
        // Simpan data pengguna baru
        $stmt = $conn->prepare("INSERT INTO users (nik, nama_lengkap, email, no_telepon, password, role, status_aktif) VALUES (?, ?, ?, ?, ?, 'user', 1)");
        $stmt->bind_param("sssss", $nik, $username, $email, $no_telepon, $password);

        if ($stmt->execute()) {
            // Jika registrasi berhasil
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi Berhasil',
                        text: 'Akun Anda berhasil dibuat!',
                        confirmButtonText: 'Login Sekarang'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
            </script>";
        } else {
            // Jika registrasi gagal
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registrasi Gagal',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                        confirmButtonText: 'Coba Lagi'
                    }).then(() => {
                        window.location.href = 'register.php';
                    });
                });
            </script>";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="src/css/register.css">
    <!-- Tambahkan link SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form action="register.php" method="POST">
        <div class="register-box">
            <div class="register-header">
                <header>Register</header>
            </div>
            <div class="input-box">
                <input type="text" name="nama_lengkap" class="input-field" required autocomplete="off">
                <label for="nama_lengkap">Nama Lengkap</label>
            </div>
            <div class="input-box">
                <input type="number" name="nik" class="input-field" required autocomplete="off">
                <label for="nik">NIK</label>
            </div>
            <div class="input-box">
                <input type="email" name="email" class="input-field" required autocomplete="off">
                <label for="email">Email</label>
            </div>
            <div class="input-box">
                <input type="text" name="no_telepon" class="input-field" required autocomplete="off">
                <label for="no_telepon">No Telepon</label>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" required autocomplete="off">
                <label for="password">Password</label>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" class="input-field" required autocomplete="off">
                <label for="confirm_password">Confirm Password</label>
            </div>
            <div class="input-box">
                <input type="submit" class="input-submit" value="Register">
            </div>
            <div class="sign-up">
                <p>Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </form>

    <!-- Validasi form menggunakan SweetAlert -->
    <script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

        if (password !== confirmPassword) {
            event.preventDefault(); // Mencegah form terkirim
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Cocok',
                text: 'Harap pastikan password dan konfirmasi password sama!',
                confirmButtonText: 'Coba Lagi'
            });
        }
    });
    </script>
</body>
</html>

