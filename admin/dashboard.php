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

// Fungsi untuk mengambil data tabel secara aman
function fetchData($conn, $query)
{
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kependudukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; background-color: #343a40; color: white; padding-top: 20px; }
        .sidebar .nav-link { color: #ddd; font-size: 14px; }
        .sidebar .nav-link.active { background-color: #495057; color: white; }
        .submenu { padding-left: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd; }
        .table-container { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <div class="d-flex align-items-center mb-4">
                <img src="https://via.placeholder.com/40" alt="Admin" class="rounded-circle me-2">
                <div>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <span class="badge bg-success">Administrator</span>
                </div>
            </div>
            <nav class="nav flex-column">
                <a href="?" class="nav-link <?php echo !isset($_GET['page']) ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#kelolaData" role="button"
                   aria-expanded="false" aria-controls="kelolaData">
                    <i class="bi bi-grid me-2"></i> Kelola Data
                </a>
                <div class="collapse <?php echo isset($_GET['page']) && in_array($_GET['page'], ['dataPenduduk', 'dataKK']) ? 'show' : ''; ?>" id="kelolaData">
                    <nav class="nav flex-column submenu">
                        <a href="?page=dataPenduduk" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'dataPenduduk' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Data Penduduk
                        </a>
                        <a href="?page=dataKK" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'dataKK' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Data KK
                        </a>
                    </nav>
                </div>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#Request" role="button"
                   aria-expanded="false" aria-controls="Request">
                    <i class="bi bi-gear me-2"></i> Regist Akun
                </a>
                <div class="collapse <?php echo isset($_GET['page']) && in_array($_GET['page'], ['permintaanRegis', 'data_akun']) ? 'show' : ''; ?>" id="Request">
                    <nav class="nav flex-column submenu">
                        <a href="?page=permintaanRegis" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'permintaanRegis' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Permintaan Regist
                        </a>
                        <a href="?page=data_akun" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'data_akun' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Akun Aktif
                        </a>
                    </nav>
                </div>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#Pengajuan" role="button" aria-expanded="false" aria-controls="Pengajuan"><i class="bi bi-file-earmark-plus me-2"></i> Pengajuan</a>
                <div class="collapse <?php echo isset($_GET['page']) && in_array($_GET['page'], ['kk_aproved', 'data_pengajuan']) ? 'show' : ''; ?>" id="Pengajuan">
                    <nav class="nav flex-column submenu">
                        <a href="?page=kk_aproved" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'kk_aproved' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Kartu Keluarga
                        </a>
                        <a href="?page=data_pengajuan" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'data_pengajuan' ? 'active' : ''; ?>">
                            <i class="bi bi-circle me-2"></i> Data Pengajuan
                        </a>
                    </nav>
                </div>

                <hr>
                <a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="header"><h5 class="mb-0">Sistem Informasi Administrasi Kependudukan</h5></div>
            <div class="container my-4">
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];

                    if ($page == 'dataPenduduk') {
                        $data = fetchData($conn, "SELECT * FROM users");
                        include "pages/data_penduduk.php";
                    } elseif ($page == 'dataKK') {
                        $data = fetchData($conn, "SELECT * FROM kartu_keluarga");
                        include "pages/data_kk.php";
                    } elseif ($page == 'permintaanRegis') {
                        $data = fetchData($conn, "SELECT id, nama_lengkap, email, role, status FROM users WHERE status = 'pending'");
                        include "pages/permintaan_regis.php";
                    } elseif ($page == 'data_akun') {
                        $data = fetchData($conn, "SELECT id, nama_lengkap, email, role, status FROM users WHERE status IN ('approved', 'rejected')");
                        include "pages/data_akun.php";
                    } elseif ($page == 'data_pengajuan') {
                        $data = fetchData($conn, "SELECT id, nama_kepala, nik_kepala, alamat, status FROM kartu_keluarga WHERE status = 'pending'");
                        include "pages/data_pengajuan.php";
                    } elseif ($page == 'kk_aproved') {
                        $data = fetchData($conn, "SELECT id, nama_kepala, nik_kepala, alamat, status FROM kartu_keluarga WHERE status = 'approved'");
                        include "pages/kk_aproved.php";
                    }
                    else {
                        echo '<p class="text-center">Halaman tidak ditemukan.</p>';
                    }
                } else {
                    echo '<p class="text-center">Selamat datang di Sistem Informasi Administrasi Kependudukan.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
