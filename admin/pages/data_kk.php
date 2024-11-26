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

// Query untuk mengambil data KK dengan status 'selesai'
$query = "SELECT id, nama_kepala, nik_kepala, alamat, created_at FROM kartu_keluarga WHERE status = 'selesai'";
$data = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kartu Keluarga Selesai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h5>Data Kartu Keluarga yang Sudah Selesai</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kepala Keluarga</th>
                <th>NIK Kepala Keluarga</th>
                <th>Alamat</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($data && $data->num_rows > 0) {
                $no = 1;
                while ($row = $data->fetch_assoc()) {
                    echo "<tr>
                            <td>$no</td>
                            <td>{$row['nama_kepala']}</td>
                            <td>{$row['nik_kepala']}</td>
                            <td>{$row['alamat']}</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    $no++;
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">Tidak ada data KK yang selesai.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
