<?php
include 'include/config.php';

$nama_minuman = isset($_POST['nama_minuman']) ? $_POST['nama_minuman'] : '';

if (!empty($nama_minuman)) {
    $query = $conn->prepare("
        SELECT * FROM minuman
        WHERE (nama_minuman LIKE ?)
    ");
    
    $like_input = "%$nama_minuman%"; 
    
    $query->bind_param('s', $like_input);
    $query->execute();
    
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        echo "<h2>Hasil Pencarian:</h2>";
        echo "<li>";
        while ($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row['nama_minuman']) . " - " . 
            htmlspecialchars($row['deskripsi']) . " - Rp " . 
            number_format($row['harga'], 0, ',', '.');
        }
        echo "</li>";
    } else {
        echo "<h2>Tidak ditemukan hasil pencarian.</h2>";
    }

    $query->close();
} else {
    echo "<h2>Masukkan kata kunci untuk mencari!</h2>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Makanan</title>
    <link rel="shortcut icon" href="image/LogoX-Kantin.png" type="image/x-icon">
</head>
<body>
    <h1>Pencarian Minuman</h1>
    <form method="POST" action="">
        <label for="nama_minuman">Nama Minuman:</label>
        <input type="text" id="nama_minuman" name="nama_minuman" placeholder="Masukkan nama makanan"><br><br>

        <button type="submit">Cari</button>
    </form>
</body>
</html>
