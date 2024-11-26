<?php
session_start(); // Mulai sesi

include '../config/connection.php'; // Koneksi ke database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = '../login.php';
    </script>";
    exit();
}

// Ambil id_user dari session
$id_user = $_SESSION['user_id'];

// Query untuk mengambil data pengajuan dari database berdasarkan id_user
$query = "SELECT status, created_at FROM kartu_keluarga WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

// Default data jika tidak ada pengajuan
$status_pengajuan = "tidak_ada";
$progress = 0;
$status_text = "Tidak ada pengajuan ditemukan.";
$progress_text = "Tidak Ada";
$tanggal_selesai = "";

// Jika ada data pengajuan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $status_pengajuan = $data['status'];
    $tanggal_selesai = $data['created_at'];

    // Tentukan progres dan teks berdasarkan status
    switch ($status_pengajuan) {
        case "pending":
            $progress = 25;
            $status_text = "Menunggu persetujuan admin.";
            $progress_text = "Pending";
            break;
        case "approved":
            $progress = 75;
            $status_text = "Pengajuan Anda disetujui dan sedang diproses.";
            $progress_text = "Approved";
            break;
        case "selesai":
            $progress = 100;
            $tanggal_pengambilan = date('Y-m-d', strtotime($tanggal_selesai . ' +3 days'));
            $status_text = "KK Anda sudah selesai. Silakan ambil maksimal tanggal $tanggal_pengambilan.";
            $progress_text = "Selesai";
            break;
        case "rejected":
            $progress = 0;
            $status_text = "Pengajuan Anda ditolak. Silakan hubungi admin.";
            $progress_text = "Rejected";
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Progres Pengajuan KK</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .progress-container {
        margin-top: 50px;
        max-width: 600px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .progress-title {
        font-weight: bold;
        color: #333;
    }
    .status-alert {
        font-size: 16px;
    }
    .progress-bar {
        font-weight: bold;
    }
    .btn-back {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center">
    <div class="progress-container p-4">
      <h1 class="text-center mb-4">Progres Pengajuan Kartu Keluarga</h1>
      
      <!-- Progress Bar -->
      <div class="mb-3">
        <h5 class="progress-title">Status Progres</h5>
        <div class="progress">
          <div 
            class="progress-bar 
              <?php echo $progress == 100 ? 'bg-success' : ($progress > 0 ? 'bg-warning' : 'bg-danger'); ?>" 
            role="progressbar" 
            style="width: <?php echo $progress; ?>%;" 
            aria-valuenow="<?php echo $progress; ?>" 
            aria-valuemin="0" 
            aria-valuemax="100">
            <?php echo $progress_text; ?>
          </div>
        </div>
      </div>
      
      <!-- Notifikasi Status -->
      <div class="alert 
          <?php echo $progress == 100 ? 'alert-success' : ($progress > 0 ? 'alert-info' : 'alert-danger'); ?>" 
          role="alert">
        <strong>Status:</strong> <?php echo $status_text; ?>
      </div>

      <!-- Informasi Tambahan -->
      <?php if ($status_pengajuan == "selesai") : ?>
        <div class="card border-info">
          <div class="card-body">
            <h5 class="card-title text-info">Informasi Pengambilan</h5>
            <p class="card-text">
              KK Anda telah selesai diproses. Silakan ambil sebelum tanggal <strong><?php echo $tanggal_pengambilan; ?></strong>.
            </p>
          </div>
        </div>
      <?php elseif ($status_pengajuan == "rejected") : ?>
        <div class="card border-danger">
          <div class="card-body">
            <h5 class="card-title text-danger">Pengajuan Ditolak</h5>
            <p class="card-text">
              Mohon hubungi admin untuk informasi lebih lanjut.
            </p>
          </div>
        </div>
      <?php endif; ?>
      
      <!-- Tombol Navigasi -->
      <div class="btn-back">
        <a href="dashboard.php" class="btn btn-primary btn-lg">Kembali ke Form Pengajuan</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
