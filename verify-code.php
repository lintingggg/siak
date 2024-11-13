<?php
session_start();
include 'config/connection.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $resetCode = $_POST['reset_code'];

    // Pastikan timezone sudah diset
    date_default_timezone_set('Asia/Jakarta');
    $currentTime = date("Y-m-d H:i:s");

    // Query untuk mengecek kode reset dan waktu kadaluarsa
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_code = ?");
    $stmt->bind_param("ss", $email, $resetCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Periksa apakah kode reset masih berlaku
        if ($currentTime <= $user['reset_code_expiry']) {
            // Simpan email ke session untuk digunakan di halaman reset-password.php
            $_SESSION['reset_email'] = $email;

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                icon: 'success',
                title: 'Kode Verifikasi Berhasil!',
                text: 'Silakan masukkan password baru Anda.',
                confirmButtonText: 'Lanjut'
            }).then(() => {
                window.location.href = 'reset-password.php';
            });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Kode Kadaluarsa!',
                    text: 'Kode reset sudah tidak berlaku. Silakan minta kode baru.',
                    confirmButtonText: 'OK'
                });
            });
            </script>";
        }
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Kode Salah!',
                text: 'Kode reset salah atau email tidak ditemukan.',
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
    <title>Verifikasi Kode</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form action="verify-code.php" method="POST">
        <label for="email">Email Anda:</label>
        <input type="email" name="email" required><br><br>

        <label for="reset_code">Kode Reset Password:</label>
        <input type="text" name="reset_code" required><br><br>

        <button type="submit">Verifikasi Kode</button>
    </form>
</body>
</html>
