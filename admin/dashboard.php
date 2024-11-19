<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "siak_db");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mengambil data tabel
function fetchData($conn, $table)
{
    $result = $conn->query("SELECT * FROM $table");
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kependudukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #ddd;
            font-size: 14px;
        }

        .sidebar .nav-link.active {
            background-color: #495057;
            color: white;
        }

        .submenu {
            padding-left: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .card-icon {
            font-size: 40px;
            position: absolute;
            right: 10px;
            top: 10px;
            opacity: 0.2;
        }

        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <div class="d-flex align-items-center mb-4">
                <img src="https://via.placeholder.com/40" alt="Admin" class="rounded-circle me-2">
                <div>
                    <strong>Brodo</strong>
                    <span class="badge bg-success">Administrator</span>
                </div>
            </div>
            <nav class="nav flex-column">
                <a href="?" class="nav-link active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#kelolaData" role="button" aria-expanded="false" aria-controls="kelolaData">
                    <i class="bi bi-grid me-2"></i> Kelola Data
                </a>
                <div class="collapse" id="kelolaData">
                    <nav class="nav flex-column submenu">
                        <a href="?page=dataPenduduk" class="nav-link">
                            <i class="bi bi-circle me-2"></i> Data Penduduk
                        </a>
                        <a href="?page=dataKK" class="nav-link">
                            <i class="bi bi-circle me-2"></i> Data Kartu Keluarga
                        </a>
                    </nav>
                </div>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#sirkulasiPenduduk" role="button" aria-expanded="false" aria-controls="sirkulasiPenduduk">
                    <i class="bi bi-gear me-2"></i> Sirkulasi Penduduk
                </a>
                <div class="collapse" id="sirkulasiPenduduk">
                    <nav class="nav flex-column submenu">
                        <a href="#" class="nav-link">
                            <i class="bi bi-circle me-2"></i> Data Lahir
                        </a>
                        <a href="#" class="nav-link">
                            <i class="bi bi-circle me-2"></i> Data Meninggal
                        </a>
                    </nav>
                </div>
                <hr>
                <a href="#" class="nav-link">
                    <i class="bi bi-person-circle me-2"></i> Pengguna Sistem
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Header -->
            <div class="header">
                <h5 class="mb-0">Sistem Informasi Administrasi Kependudukan</h5>
            </div>

            <!-- Content -->
            <div class="container my-4">
                <?php
                // Menentukan halaman yang akan ditampilkan
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if ($page == 'dataPenduduk') {
                        // Menampilkan tabel data penduduk
                        $data = fetchData($conn, "data_penduduk");
                        echo '<div class="table-container">';
                        echo '<h5>Data Penduduk</h5>';
                        echo '<table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Alamat</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        if ($data->num_rows > 0) {
                            $no = 1;
                            while ($row = $data->fetch_assoc()) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['nik']}</td>
                                        <td>{$row['alamat']}</td>
                                        <td>{$row['jenis_kelamin']}</td>
                                        <td>
                                            <button class='btn btn-primary btn-sm'>Edit</button>
                                            <button class='btn btn-danger btn-sm'>Hapus</button>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                    } elseif ($page == 'dataKK') {
                        // Menampilkan tabel data kartu keluarga
                        $data = fetchData($conn, "data_kk");
                        echo '<div class="table-container">';
                        echo '<h5>Data Kartu Keluarga</h5>';
                        echo '<table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No KK</th>
                                        <th>Kepala Keluarga</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        if ($data->num_rows > 0) {
                            $no = 1;
                            while ($row = $data->fetch_assoc()) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td>{$row['no_kk']}</td>
                                        <td>{$row['kepala_keluarga']}</td>
                                        <td>{$row['alamat']}</td>
                                        <td>
                                            <button class='btn btn-primary btn-sm'>Edit</button>
                                            <button class='btn btn-danger btn-sm'>Hapus</button>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-center">Halaman tidak ditemukan.</p>';
                    }
                } else {
                    echo '<p class="text-center">Selamat datang di Sistem Informasi Administrasi Kependudukan.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
