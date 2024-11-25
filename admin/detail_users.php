<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/connection.php'; // Koneksi database

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses ditolak! Anda harus login sebagai admin.');
        window.location.href = '../login.php';
    </script>";
    exit();
}

// Periksa apakah `id` diberikan
if (!isset($_GET['id'])) {
    echo "<script>
        alert('ID pengguna tidak ditemukan.');
        window.location.href = 'permintaan_regis.php';
    </script>";
    exit();
}

// Ambil data pengguna berdasarkan ID
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>
        alert('Pengguna tidak ditemukan.');
        window.location.href = 'permintaan_regis.php';
    </script>";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h5>Detail Pengguna</h5>
    <table class="table table-bordered">
        <tr>
            <th>Nama Lengkap</th>
            <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
        </tr>
        <tr>
            <th>NIK</th>
            <td><?php echo htmlspecialchars($user['nik']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo htmlspecialchars($user['status']); ?></td>
        </tr>
        <tr>
            <th>Foto KTP</th>
            <td><img src="../uploads/<?php echo htmlspecialchars($user['foto_ktp']); ?>" alt="Foto KTP" class="img-thumbnail" style="max-width: 200px;"></td>
        </tr>
        <tr>
            <th>Foto Diri dengan KTP</th>
            <td><img src="../uploads/<?php echo htmlspecialchars($user['foto_diri_ktp']); ?>" alt="Foto Diri" class="img-thumbnail" style="max-width: 200px;"></td>
        </tr>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
