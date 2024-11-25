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

// Periksa apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk mengambil data detail kartu keluarga
    $query = "SELECT * FROM kartu_keluarga WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit();
    }
} else {
    echo "ID tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kartu Keluarga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h5>Detail Kartu Keluarga</h5>
    <table class="table table-bordered">
        <tr>
            <th>Nama Kepala Keluarga</th>
            <td><?php echo $row['nama_kepala']; ?></td>
        </tr>
        <tr>
            <th>NIK Kepala Keluarga</th>
            <td><?php echo $row['nik_kepala']; ?></td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td><?php echo $row['alamat']; ?></td>
        </tr>
        <tr>
            <th>RT</th>
            <td><?php echo $row['rt']; ?></td>
        </tr>
        <tr>
            <th>RW</th>
            <td><?php echo $row['rw']; ?></td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><?php echo $row['kecamatan']; ?></td>
        </tr>
        <tr>
            <th>Kelurahan</th>
            <td><?php echo $row['kelurahan']; ?></td>
        </tr>
        <tr>
            <th>Nama Istri</th>
            <td><?php echo $row['nama_istri']; ?></td>
        </tr>
        <tr>
            <th>NIK Istri</th>
            <td><?php echo $row['nik_istri']; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $row['status']; ?></td>
        </tr>
    </table>
    <a href="dashboard.php" class="btn btn-primary">Kembali ke Daftar Permintaan</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
