<?php
session_start();
require_once '../include/config.php';

// Validasi role untuk akses halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect ke halaman lain jika bukan admin
    header("Location: ../404.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama_minuman = $_POST['nama_minuman'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO minuman (nama_minuman, deskripsi, harga) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssi", $nama_minuman, $deskripsi, $harga);

        if ($stmt->execute()) {
            header('Location:../menu.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Minuman</title>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../image/LogoX-Kantin.png" type="image/x-icon">
    <link rel="stylesheet" href="../style/form.css">
</head>
<body>
<nav class="navbar">
    <div class="logo"><img src="../image/LogoX-Kantin.png" alt=""></div>
    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li class="dropdown">
            <a href="" class="dropbtn">Menu <i class="fa-solid fa-caret-down"></i></a>
            <div class="dropdown-content">
            <a href="../menu.php#makanan">Makanan</a>
            <a href="../menu.php#minuman">Minuman</a>
            </div>  

        </li>
        <li class="dropdown">
            <a href="" class="dropbtn">Tambahkan <i class="fa-solid fa-caret-down"></i></a>
            <div class="dropdown-content">
            <a href="../CUD/tambah_makanan.php">Makanan +</a>
            <a href="../CUD/tambah_minuman.php">Minuman +</a>
            </div>  
        </li>
    </ul>
    <div class="menu-icon">
    <a href="Login/login.php"> <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'; ?> <i class="fa-solid fa-circle-user" style="color: #ffffff;"></i></a>
    </div>
</nav>

    <form action="" method="post" autocomplete="off">
        <h1>Tambah Minuman</h1>
        <label for="minuman">Nama Minuman :</label>
        <input type="text" id="minuman" name="nama_minuman" placeholder="Masukkan nama minuman" required>
        <label for="deskripsi">Deskripsi Minuman : </label>
        <input type="text" name="deskripsi" id="deskripsi" placeholder="Masukan deskripsi minuman" required>
        <label for="harga">Harga Minuman : </label>
        <input type="number" id="harga" name="harga" placeholder="Masukkan harga minuman" required>
        <button type="submit" name="submit">Tambah</button>
    </form>
</body>
</html>
