<?php
session_start();
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Ambil data dari form
    $nama_kepala = $_POST['nama_kepala'];
    $nik_kepala = $_POST['nik_kepala'];
    $alamat = $_POST['alamat'];
    $rt = $_POST['rt'];
    $rw = $_POST['rw'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $nama_istri = $_POST['nama_istri'];
    $nik_istri = $_POST['nik_istri'];

    // Upload file
    $uploadDir = 'uploads/';
    $bukuNikahPath = $uploadDir . basename($_FILES['buku_nikah']['name']);
    move_uploaded_file($_FILES['buku_nikah']['tmp_name'], $bukuNikahPath);

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Masukkan data ke tabel kartu_keluarga dengan foreign key id_user
        $stmt = $conn->prepare("
            INSERT INTO kartu_keluarga 
            (id_user, nama_kepala, nik_kepala, alamat, rt, rw, kecamatan, kelurahan, nama_istri, nik_istri, buku_nikah, status) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $status = 'pending'; // Set default status to 'pending'
        $stmt->bind_param(
            "isssssssssss", 
            $id_user, 
            $nama_kepala, 
            $nik_kepala, 
            $alamat, 
            $rt, 
            $rw, 
            $kecamatan, 
            $kelurahan, 
            $nama_istri, 
            $nik_istri, 
            $bukuNikahPath, 
            $status
        );
        $stmt->execute();

        // Ambil ID dari kartu_keluarga yang baru dimasukkan
        $kartuKeluargaId = $conn->insert_id;

        // Masukkan data akte_anak jika ada
        if (isset($_FILES['akte_anak']) && !empty($_FILES['akte_anak']['name'][0])) {
            $stmtAkte = $conn->prepare("
                INSERT INTO akte_anak (kartu_keluarga_id, file_akte) 
                VALUES (?, ?)
            ");

            foreach ($_FILES['akte_anak']['name'] as $index => $fileName) {
                $filePath = $uploadDir . basename($fileName);
                move_uploaded_file($_FILES['akte_anak']['tmp_name'][$index], $filePath);
                $stmtAkte->bind_param("is", $kartuKeluargaId, $filePath);
                $stmtAkte->execute();
            }
        }

        // Commit transaksi
        $conn->commit();
        echo "Data berhasil disimpan.";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Gagal menyimpan data: " . $e->getMessage();
    }
}
?>
