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

// Query untuk mengambil data permintaan dari tabel kartu_keluarga dengan status 'pending'
$query = "SELECT id, nama_kepala, nik_kepala, alamat, status FROM kartu_keluarga WHERE status = 'pending'";
$data = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Kartu Keluarga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h5>Permintaan Kartu Keluarga</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kepala Keluarga</th>
                <th>NIK Kepala Keluarga</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Aksi</th>
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
                            <td>{$row['status']}</td>
                            <td>
                                <form action='user_actions.php' method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='kartu_keluarga_id' value='{$row['id']}'>
                                    <button type='submit' class='btn btn-success btn-sm' name='action' value='approve'>Approve</button>
                                    <button type='submit' class='btn btn-danger btn-sm' name='action' value='reject'>Reject</button>
                                </form>
                                <a href='detail_kartu_keluarga.php?id={$row['id']}' class='btn btn-info btn-sm'>Detail</a>
                            </td>
                        </tr>";
                    $no++;
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">Tidak ada permintaan kartu keluarga yang statusnya pending.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
