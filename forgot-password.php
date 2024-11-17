<?php
session_start();
include 'config/connection.php'; // Koneksi ke database

// Sertakan PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Pastikan PHPMailer sudah diinstal melalui Composer

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Periksa apakah email terdaftar
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Generate kode reset password acak (6 digit)
        $resetCode = rand(100000, 999999);

        // Set waktu kadaluarsa (misal 10 menit dari sekarang)
        $expiryTime = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Simpan kode dan kadaluarsa ke database
        $updateStmt = $conn->prepare("UPDATE users SET reset_code = ?, reset_code_expiry = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $resetCode, $expiryTime, $email);
        $updateStmt->execute();

        // Gunakan PHPMailer untuk mengirim email
        $mail = new PHPMailer(true);
        try {
            // Pengaturan server SMTP
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com'; // Gunakan server Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'imuh6500@gmail.com'; // Ganti dengan email Gmail Anda
            $mail->Password = 'gqdn rkcd hsan gdov'; // Gunakan App Password jika 2FA aktif
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Penerima email
            $mail->setFrom('imuh6500@gmail.com', 'Admin Siak');
            $mail->addAddress($email, $user['nama_lengkap']); // Email tujuan

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Request';
            $mail->Body    = "Halo, {$user['nama_lengkap']}!<br><br> 
                            Kode reset password Anda adalah: <strong>$resetCode</strong><br>
                            Kode ini berlaku hingga $expiryTime.<br><br>
                            Jika Anda tidak meminta reset password, abaikan email ini.";

            // Kirim email
            $mail->send();

            echo "<script>
                document,addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kode Terkirim!',
                        text: 'Kode reset password telah dikirim ke email Anda.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'verify-code.php';
                    });
                });              
            </script>";
        } catch (Exception $e) {
            echo "<script>
            document,addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim Email!',
                    text: 'Mailer Error: {$mail->ErrorInfo}',
                    confirmButtonText: 'Coba Lagi'
                });
            });
            </script>";
        }
    } else {
        echo "<script>
        document,addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Email Tidak Ditemukan!',
                text: 'Email tidak ditemukan di sistem kami.',
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
    <title>Forgot Password</title>
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
                        <h3 class="text-center mb-4">Forgot Password</h3>
                        <form action="forgot-password.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Anda:</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Kirim Kode Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
