<?php
session_start();
include '../config/connection.php'; // Koneksi database

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

    if ($action === 'approve') {
        // Update status menjadi 'approved'
        $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<script>
                alert('Pengguna berhasil di-approve.');
                window.location.href = 'dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Terjadi kesalahan saat approve pengguna.');
                window.location.href = 'dashboard.php';
            </script>";
        }
        $stmt->close();
    } elseif ($action === 'reject') {
        // Update status menjadi 'rejected'
        $stmt = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<script>
                alert('Pengguna berhasil di-reject.');
                window.location.href = 'dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Terjadi kesalahan saat reject pengguna.');
                window.location.href = 'dashboard.php';
            </script>";
        }
        $stmt->close();
    }
}
$conn->close();
?>
