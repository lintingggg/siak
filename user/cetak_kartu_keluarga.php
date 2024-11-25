<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembuatan Kartu Keluarga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .add-button {
            background-color: #007BFF;
            margin-top: 10px;
        }
        .add-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function addChildCertificate() {
            const container = document.getElementById('child-certificates');
            const childIndex = container.children.length;

            const newDiv = document.createElement('div');
            newDiv.className = 'form-group';
            newDiv.innerHTML = `
                <label for="akte-anak-${childIndex}">Upload Akte Anak ${childIndex}:</label>
                <input type="file" id="akte-anak-${childIndex}" name="akte_anak[]" accept="image/*,application/pdf">
            `;
            container.appendChild(newDiv);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Form Pembuatan Kartu Keluarga</h2>
        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $tempat_lahir_istri = $_POST['tempat_lahir_istri'];
    $tanggal_lahir_istri = $_POST['tanggal_lahir_istri'];

    // Tentukan direktori upload
    $uploadDir = 'uploads/';

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Proses file 'Buku Nikah' jika ada
    if (isset($_FILES['buku_nikah']) && $_FILES['buku_nikah']['error'] == 0) {
        $buku_nikah = $_FILES['buku_nikah'];
        $bukuNikahPath = $uploadDir . basename($buku_nikah['name']);

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($buku_nikah['tmp_name'], $bukuNikahPath)) {
            echo "<p>Buku Nikah berhasil diunggah: {$buku_nikah['name']}</p>";
        } else {
            echo "<p>Gagal mengunggah Buku Nikah.</p>";
        }
    }

    // Proses setiap file akte anak jika ada
    if (isset($_FILES['akte_anak']) && !empty($_FILES['akte_anak']['name'][0])) {
        foreach ($_FILES['akte_anak']['name'] as $index => $fileName) {
            if ($_FILES['akte_anak']['error'][$index] == 0) {
                $akteAnakPath = $uploadDir . basename($fileName);

                // Pindahkan file ke folder uploads
                if (move_uploaded_file($_FILES['akte_anak']['tmp_name'][$index], $akteAnakPath)) {
                    echo "<p>Akte Anak " . ($index + 1) . " berhasil diunggah: $fileName</p>";
                } else {
                    echo "<p>Gagal mengunggah Akte Anak " . ($index + 1) . "</p>";
                }
            }
        }
    }

    // Tampilkan pesan berhasil
    echo "<p>Data berhasil disimpan.</p>";
}
?>

        <form action="proses_kartu_keluarga.php" method="post" enctype="multipart/form-data">
            <!-- Informasi Kepala Keluarga -->
            <div class="form-group">
                <label for="nama-kepala">Nama Kepala Keluarga:</label>
                <input type="text" id="nama-kepala" name="nama_kepala" required>
            </div>
            <div class="form-group">
                <label for="nik-kepala">NIK Kepala Keluarga:</label>
                <input type="text" id="nik-kepala" name="nik_kepala" required>
            </div>

            <!-- Informasi Alamat -->
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="rt">RT:</label>
                <input type="number" id="rt" name="rt" required>
            </div>
            <div class="form-group">
                <label for="rw">RW:</label>
                <input type="number" id="rw" name="rw" required>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan:</label>
                <input type="text" id="kecamatan" name="kecamatan" required>
            </div>
            <div class="form-group">
                <label for="kelurahan">Kelurahan:</label>
                <input type="text" id="kelurahan" name="kelurahan" required>
            </div>

            <!-- Informasi Istri -->
            <div class="form-group">
                <h4>Informasi Istri</h4>
                <label for="nama-istri">Nama Istri:</label>
                <input type="text" id="nama-istri" name="nama_istri" required>

                <label for="nik-istri">NIK Istri:</label>
                <input type="text" id="nik-istri" name="nik_istri" required>
            </div>

            <!-- Dokumen Persyaratan -->
            <div class="form-group">
                <label for="buku-nikah">Upload Buku Nikah:</label>
                <input type="file" id="buku-nikah" name="buku_nikah" accept="image/*,application/pdf" required>
            </div>

            <!-- Akte Anak -->
            <div id="child-certificates">
                <h4>Upload Akte Kelahiran Anak (Opsional)</h4>
                <div class="form-group">
                    <label for="akte-anak-1">Upload Akte Anak 1:</label>
                    <input type="file" id="akte-anak-1" name="akte_anak[]" accept="image/*,application/pdf">
                </div>
            </div>
            <button type="button" class="add-button" onclick="addChildCertificate()">Tambah Akte Anak</button>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
