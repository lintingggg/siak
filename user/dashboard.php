<?php
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Ambil data session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Jika ingin menampilkan nama pengguna

// Cek nilai id_user
echo "ID User dari session: " . $user_id . "<br>";
echo "Nama User dari session: " . $username . "<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- NAVBAR START -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dinas Pencatatan Sipil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><i class="fas fa-home" style="color: #74C0FC;"></i> Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa-solid fa-list" style="color: #74C0FC;"></i> Permohonan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notif.php"><i class="fa-regular fa-bell" style="color: #74C0FC;"></i> Notifikasi</a>
                    </li>
                </ul>
                <div class="nav-item ms-auto">
                    <a href="../logout.php" class="btn btn-danger btn-sm ms-3">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->
    
        <div class="kata-awal">
            <p>
            Dinas Pencatatan Sipil kini hadir dengan layanan cetak Kartu Keluarga yang lebih mudah, 
            cepat, dan terpercaya.
            </p>
        </div>
    
    <!-- MENU START -->
    <div class="container content-container">
        <!-- PENCATATAN SIPIL Section -->
        <div class="menu-section">
            <div class="menu-title">
                <i class="fa-solid fa-list"></i> PENCATATAN SIPIL
            </div>
            <div class="row">
                <div class="col-md-3 menu-item">
                    <a href="akta_kelahiran.html">
                        <img src="https://png.pngtree.com/png-clipart/20220124/original/pngtree-cartoon-baby-sitting-png-image_7189201.png" alt="AKTA KELAHIRAN">
                        <p>AKTA KELAHIRAN</p>
                    </a>
                </div>
                <div class="col-md-3 menu-item">
                    <a href="akta_kematian.html">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcST8BU6F2ikTo9WIcCgW_zTvvAWHhf7lhdmdA&s" alt="AKTA KEMATIAN">
                        <p>AKTA KEMATIAN</p>
                    </a>
                </div>
                <div class="col-md-3 menu-item">
                    <a href="akta_pengesahan_anak.html">
                        <img src="https://img.freepik.com/premium-vector/illustration-cartoon-portrait-four-members-happy-family-raising-up-hands-parents-with-kids_283146-409.jpg" alt="Akta Pengesahan Anak">
                        <p>Akta Pengesahan Anak</p>
                    </a>
                </div>
                <div class="col-md-3 menu-item">
                    <a href="akta_penceraian.html">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/heartbreaking-situation-child-witnessing-marital-separation-illustration-download-in-svg-png-gif-file-formats--parents-divorce-relationship-pack-illustrations-7033304.png?f=webp" alt="Akta Perceraian">
                        <p>Akta Perceraian</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- PENDAFTARAN PENDUDUK Section -->
        <div class="menu-section">
            <div class="menu-title">
                <i class="fa-solid fa-list"></i> PENDAFTARAN PENDUDUK
            </div>
            <div class="row justify-content-center">
                <div class="col-md-3 menu-item">
                    <a href="cetak_kartu_keluarga.php">
                        <img src="https://img.freepik.com/premium-vector/cute-document-cartoon-character_274619-1305.jpg" alt="Cetak Ulang Kartu Keluarga">
                        <p>CETAK KARTU KELUARGA</p>
                    </a>
                </div>
                <div class="col-md-3 menu-item">
                    <a href="#">
                        <img src="https://images.tokopedia.net/img/cache/700/VqbcmM/2022/10/10/e2ae8396-2df5-4f6c-9c20-83a77058d3fa.jpg" alt="Pindah Keluar">
                        <p>PINDAH KELUAR</p>
                    </a>
                </div>
                <div class="col-md-3 menu-item">
                    <a href="#">
                        <img src="https://st3.depositphotos.com/11956860/17348/v/450/depositphotos_173486446-stock-illustration-icon-logo-for-business-administration.jpg" alt="Perubahan Biodata">
                        <p>Perubahan Biodata</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- MENU END -->
</body>
</html>