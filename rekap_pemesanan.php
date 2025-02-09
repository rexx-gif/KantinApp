<?php
include('include/config.php');

// Query untuk mengambil data pemesanan dengan nama makanan dan minuman
$query = "SELECT p.id, p.nama_pelanggan, 
                 GROUP_CONCAT(m.nama_makanan SEPARATOR ', ') AS nama_makanan, 
                 GROUP_CONCAT(mi.nama_minuman SEPARATOR ', ') AS nama_minuman, 
                 p.total_harga, p.status, p.payment_method, p.online_payment, p.created_at
          FROM pemesanan p
          LEFT JOIN makanan m ON FIND_IN_SET(m.id, p.makanan_id)
          LEFT JOIN minuman mi ON FIND_IN_SET(mi.id, p.minuman_id)
          GROUP BY p.id";


$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pemesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Rekap Pemesanan</h2>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pelanggan</th>            
            <th>Nama Makanan</th>
            <th>Nama Minuman</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Metode Pembayaran</th>
            <th>Pembayaran Online</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nama_pelanggan']; ?></td>
            <td><?php echo $row['nama_makanan'] ?: '-'; ?></td>
            <td><?php echo $row['nama_minuman'] ?: '-'; ?></td>
            <td><?php echo number_format($row['total_harga'], 2, ',', '.'); ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['payment_method']; ?></td>
            <td><?php echo $row['online_payment'] ?: '-'; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
