<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="table-container">
        <h5>Data Kartu Keluarga</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No KK</th>
                    <th>Kepala Keluarga</th>
                    <th>Alamat</th>
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
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>