<?php
session_start();
include '../config/connection.php'; // Koneksi database
include '../vendor/autoload.php'; // Autoload PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses ditolak! Anda harus login sebagai admin.');
        window.location.href = '../login.php';
    </script>";
    exit();
}

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    // Ambil data pengguna berdasarkan ID
    $stmt = $conn->prepare("SELECT email, nama_lengkap, nik FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>
            alert('Pengguna tidak ditemukan.');
            window.location.href = 'dashboard.php';
        </script>";
        exit();
    }

    $email = $user['email'];
    $nama_lengkap = $user['nama_lengkap'];
    $nik = $user['nik'];

    // Buat instance PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'imuh6500@gmail.com'; // Ganti dengan email Anda
        $mail->Password = 'gqdn rkcd hsan gdov'; // Ganti dengan app password Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('imuh6500@gmail.com', 'Admin Siak'); // Ganti dengan email Anda
        $mail->addAddress($email);

        $mail->isHTML(true);

        if ($action === 'approve') {
            // Update status menjadi 'approved'
            $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                // Tambahkan ke tabel penduduk
                $stmtPenduduk = $conn->prepare("
                    INSERT INTO penduduk (user_id, nama_lengkap, nik, hubungan_keluarga) 
                    VALUES (?, ?, ?, 'kepala')
                ");
                $stmtPenduduk->bind_param("iss", $user_id, $nama_lengkap, $nik);
                if ($stmtPenduduk->execute()) {
                    $mail->Subject = 'Akun Anda Telah Disetujui';
                    $mail->Body = "Halo {$nama_lengkap},<br>Akun Anda telah disetujui. Anda sekarang dapat login ke sistem menggunakan email dan password yang Anda daftarkan.";

                    $mail->send();

                    echo "<script>
                        alert('Pengguna berhasil disetujui dan ditambahkan ke data penduduk.');
                        window.location.href = 'dashboard.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('Gagal menambahkan ke tabel penduduk.');
                        window.location.href = 'dashboard.php';
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Terjadi kesalahan saat menyetujui pengguna.');
                    window.location.href = 'dashboard.php';
                </script>";
            }
        } elseif ($action === 'reject') {
            // Update status menjadi 'rejected'
            $stmt = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $mail->Subject = 'Akun Anda Ditolak';
                $mail->Body = "Halo {$nama_lengkap},<br>Maaf, pendaftaran akun Anda telah ditolak. Silakan hubungi admin untuk informasi lebih lanjut.";

                $mail->send();

                echo "<script>
                    alert('Pengguna berhasil ditolak.');
                    window.location.href = 'dashboard.php';
                </script>";
            } else {
                echo "<script>
                    alert('Terjadi kesalahan saat menolak pengguna.');
                    window.location.href = 'dashboard.php';
                </script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Gagal mengirim email: {$mail->ErrorInfo}');
            window.location.href = 'dashboard.php';
        </script>";
    }

    $stmt->close();
    if (isset($stmtPenduduk)) {
        $stmtPenduduk->close();
    }
}
$conn->close();
?>
