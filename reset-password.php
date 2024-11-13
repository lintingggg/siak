<?php
session_start();
include 'config/connection.php'; // Koneksi ke database

// Cek apakah email sudah ada di session
if (!isset($_SESSION['reset_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    if ($newPassword === $confirmPassword) {
        // Enkripsi password baru
        // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password di database
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_code_expiry = NULL WHERE email = ?");
        $stmt->bind_param("ss", $newPassword, $email);

        if ($stmt->execute()) {
            // Hapus email dari session
            unset($_SESSION['reset_email']);

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Berhasil Direset!',
                    text: 'Silakan login dengan password baru Anda.',
                    confirmButtonText: 'Login'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Reset Password',
                    text: 'Terjadi kesalahan, silakan coba lagi.',
                    confirmButtonText: 'Coba Lagi'
                });
            });
            </script>";
        }
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Cocok',
                text: 'Pastikan kedua password yang Anda masukkan sama.',
                confirmButtonText: 'Coba Lagi'
            });
        });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form action="reset-password.php" method="POST">
        <label for="new_password">Password Baru:</label>
        <input type="password" name="new_password" required><br><br>

        <label for="confirm_password">Konfirmasi Password Baru:</label>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
