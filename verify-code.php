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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Verifikasi Kode Reset Password</h3>
                        <form action="verify-code.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Anda:</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="mb-3">
                                <label for="reset_code" class="form-label">Kode Reset Password:</label>
                                <input type="text" name="reset_code" class="form-control" placeholder="Masukkan kode reset" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Verifikasi Kode</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

