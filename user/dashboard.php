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
                    <a href="#"><i class="fa-solid fa-user-tie" style="font-size: 1.5rem;"></i></a>
                    
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
                        <img src="data:image/jpeg;base64,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" alt="AKTA KEMATIAN">
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