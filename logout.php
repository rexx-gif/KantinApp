<?php
session_start();

$username = isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';

session_destroy(); // Hapus semua session
session_start(); // Mulai session baru untuk menyimpan pesan logout
$_SESSION['logout_status'] = "Goodbye $username ($role), Kamu telah logout dari X-Kantin"; // Simpan pesan logout
header("Location: Login/login.php"); // Redirect ke halaman login
exit;
?>
