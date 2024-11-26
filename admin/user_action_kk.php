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

// Periksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kartu_keluarga_id = $_POST['kartu_keluarga_id'];
    $action = $_POST['action'];

    // Validasi data yang diterima
    if (empty($kartu_keluarga_id) || empty($action)) {
        echo "<script>
            alert('Data tidak valid.');
            window.location.href = 'permintaan_kartu_keluarga.php';
        </script>";
        exit();
    }

    // Tentukan status berdasarkan tindakan
    if ($action === 'approve') {
        $new_status = 'approved';
    } elseif ($action === 'reject') {
        $new_status = 'rejected';
    } elseif ($action === 'selesai') {
        $new_status = 'selesai';
    } elseif ($action === 'delete') {
        // Hapus data jika aksi adalah 'delete'
        $stmtDelete = $conn->prepare("DELETE FROM kartu_keluarga WHERE id = ?");
        $stmtDelete->bind_param("i", $kartu_keluarga_id);

        if ($stmtDelete->execute()) {
            echo "<script>
                alert('Data kartu keluarga berhasil dihapus.');
                window.location.href = 'permintaan_kartu_keluarga.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data.');
                window.location.href = 'permintaan_kartu_keluarga.php';
            </script>";
        }
        $stmtDelete->close();
        exit(); // Stop the script execution after deleting
    } else {
        echo "<script>
            alert('Tindakan tidak valid.');
            window.location.href = 'permintaan_kartu_keluarga.php';
        </script>";
        exit();
    }

    // Jika tindakan bukan 'delete', update status di database
    if ($action !== 'delete') {
        // Update status di database
        $stmt = $conn->prepare("UPDATE kartu_keluarga SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $kartu_keluarga_id);

        if ($stmt->execute()) {
            // Jika berhasil, beri notifikasi dan kembali ke halaman sebelumnya
            echo "<script>
                alert('Status berhasil diupdate menjadi " . strtoupper($new_status) . ".' );
                window.location.href = 'dashboard.php';
            </script>";
        } else {
            // Jika gagal, tampilkan pesan error
            echo "<script>
                alert('Gagal mengupdate status.');
                window.location.href = 'dashboard.php';
            </script>";
        }

        $stmt->close();
    }

    $conn->close();
} else {
    // Jika akses tidak valid, kembalikan ke halaman utama
    echo "<script>
        alert('Akses tidak valid.');
        window.location.href = 'dashboard.php';
    </script>";
    exit();
}
?>
