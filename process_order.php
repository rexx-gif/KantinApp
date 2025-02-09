<?php 
session_start();
include('include/config.php');  

// Tangkap data dari form 
$total_harga = isset($_POST['total_harga']) ? (float)$_POST['total_harga'] : 0;
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

// Nama pelanggan dari session 
$nama_pelanggan = $_SESSION['user'] ?? '';  

// Ambil daftar makanan & minuman dari session 
$makanan_list = $_SESSION['order']['makanan'] ?? [];
$minuman_list = $_SESSION['order']['minuman'] ?? [];

// Ambil ID makanan & minuman yang dipesan (jika ada)
$makanan_id = !empty($makanan_list) ? implode(',', array_column($makanan_list, 'id')) : NULL;
$minuman_id = !empty($minuman_list) ? implode(',', array_column($minuman_list, 'id')) : NULL;

// Ambil nama makanan & minuman untuk penyimpanan
$nama_makanan = !empty($makanan_list) ? implode(', ', array_column($makanan_list, 'nama')) : NULL;
$nama_minuman = !empty($minuman_list) ? implode(', ', array_column($minuman_list, 'nama')) : NULL;

// Tangkap metode pembayaran online jika dipilih 
$online_payment = ($payment_method == "online_payment") ? ($_POST['online_payment'] ?? NULL) : NULL;  

// Simpan ke database tanpa quantity
$query = "INSERT INTO pemesanan (nama_pelanggan, nama_makanan, nama_minuman, makanan_id, minuman_id, total_harga, payment_method, online_payment, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Lunas')";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("sssdds", 
        $nama_pelanggan, 
        $nama_makanan, 
        $nama_minuman, 
        $total_harga, 
        $payment_method, 
        $online_payment // NULL jika tidak dipilih
    );

    // Eksekusi query 
    if ($stmt->execute()) {
        echo "Pemesanan berhasil!";
        header('Location: menu.php');
        exit;
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    // Tutup koneksi 
    $stmt->close();
} else {
    echo "Terjadi kesalahan dalam persiapan query: " . $conn->error;
}  

$conn->close();
?>
