<?php
session_start();
require_once '../include/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect ke halaman lain jika bukan admin
    header("Location: ../404.php");
    exit();
}

$sql = "SELECT id, nama_makanan, deskripsi, harga FROM makanan WHERE id = '" . $_GET['id'] . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['simpan'])) {
    include 'config.php';
    $stmt = $conn->prepare("UPDATE makanan SET nama_makanan = ?, deskripsi = ?, harga = ? WHERE id = ?");
    $stmt->bind_param("ssii", $_POST['nama_makanan'], $_POST['deskripsi'], $_POST['harga'], $_POST['id']);

    if ($stmt->execute()) {
        header('Location:../menu.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Makanan</title>
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

        <li><a href="#">Contact</a></li>
    </ul>
    <div class="menu-icon">
    </div>
</nav>
</head>
<body>
    <form action="" method="post" autocomplete="off">
        <h1>Update Makanan</h1>
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="nama_makanan">Nama Makanan:</label>
        <input type="text" id="nama_makanan" name="nama_makanan" value="<?php echo $row['nama_makanan']; ?>"><br><br>
        <label for="deskripsi">Deskripsi:</label>
        <input type="text" id="deskripsi" name="deskripsi" value="<?php echo $row['deskripsi']; ?>"><br><br>
        <label for="harga">Harga:</label>
        <input type="number" id="harga" name="harga" value="<?php echo $row['harga']; ?>"><br><br>
        <input type="submit" name="simpan" value="Simpan">
    </form>
</body>
</html>
