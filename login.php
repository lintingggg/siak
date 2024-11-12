<?php
session_start(); // Memulai sesi
include 'config/connection.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan email dan password
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Cek apakah pengguna aktif
        if ($user['status_aktif'] == 1) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nama_lengkap'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Jika login berhasil
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: 'Selamat datang, " . $user['nama_lengkap'] . "!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'user/dashboard.html';
                    });
                });
            </script>";
        } else {
            // Jika akun tidak aktif
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akun Tidak Aktif',
                        text: 'Silakan hubungi admin.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }
    } else {
        // Jika email atau password salah
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email atau password salah!',
                    confirmButtonText: 'Coba Lagi'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>";
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
    <title>Login</title>
    <link rel="stylesheet" href="src\css/login.css">
    <!-- Tambahkan link SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="login-box">
    <div class="login-header">
        <header>Login</header>
    </div>
    <form action="login.php" method="POST">
        <div class="input-box">
            <input type="email" name="email" class="input-field" required autocomplete="off">
            <label for="email">Email</label>
        </div>
        <div class="input-box">
            <input type="password" name="password" class="input-field" required autocomplete="off">
            <label for="password">Password</label>
        </div>
        <div class="forgot">
            <section>
                <input type="checkbox" id="check">
                <label for="check">Remember me</label>
            </section>
            <section>
                <a href="#" class="forgot-link">Forgot password?</a>
            </section>
        </div>
        <div class="input-box">
            <input type="submit" class="input-submit" value="Login">
        </div>
        <div class="sign-up">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </form>
</div>
</body>
</html>


