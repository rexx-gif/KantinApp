<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "kantin");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil pesan logout jika ada
$logoutMessage = isset($_SESSION['logout_status']) ? $_SESSION['logout_status'] : "";
unset($_SESSION['logout_status']);

// Daftar akun Admin dan Guru
$admin_users = [
    'rafi' => 'rawr',
    'Ebin' => 'rexxrawr12345',
];

$guru_users = [
    'Pak Abdi' => 'GuruRPL',
    'Pak Nut' => 'GuruRPL',
    'Pak Hendra' => 'GuruRPL',
    'Pak Iqbal' => 'GuruRPL',
];

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($admin_users[$username]) && $admin_users[$username] === $password) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'Admin';
        $role = 'Admin';
    } elseif (isset($guru_users[$username]) && $guru_users[$username] === $password) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'Guru RPL';
        $role = 'Guru RPL';
    } elseif (!empty($username) && !isset($admin_users[$username]) && !isset($guru_users[$username])) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'Pelanggan';
        $role = 'Pelanggan';
    } else {
        $_SESSION['error'] = "Username atau password salah. Silakan coba lagi.";
        header('Location: login.php');
        exit;
    }

    // Simpan atau update user di database
    $stmt = $conn->prepare("INSERT INTO users (username, role, last_login) VALUES (?, ?, NOW()) 
                            ON DUPLICATE KEY UPDATE role = VALUES(role), last_login = NOW()");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $stmt->close();

    // Redirect berdasarkan role
    if ($role == 'Admin' || $role == 'Guru RPL') {
        header('Location: ../index.php');
    } elseif ($role == 'Pelanggan') {
        header('Location: ../menu.php');
    }
    exit;
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login X-Kantin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../style/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../image/LogoX-Kantin.png" type="image/x-icon">
</head>
<body>

<!-- Modal Alert Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header text-light">
                <h5 class="modal-title" id="logoutModalLabel">X-Kantin Alert</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-light text-center">
                <?= htmlspecialchars($logoutMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn w-50" style="background-color: #6f42c1; color: white; border-color: #6f42c1;" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Tampilkan error jika ada -->
<div class="container fade-in-target">
    <h1>Silahkan Mengisi Form X-Kantin</h1>
    <form method="POST" class="fade-in-target">
        <label for="username">Username :</label>
        <input type="text" id="username" name="username" placeholder="Masukkan Username" autocomplete="off" required>
        
        <label for="password">Password :</label>
        <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
        
        <button type="submit" class="fade-in-target">Login</button>
    </form>
    <br>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
    <br>
    <p class="note"><span class="note" style="color:red;">Note</span> Pelanggan : Pelanggan boleh pakai password apa saja.</p>
    <p class="note"><span class="note" style="color:red;">Note</span> Admin : Wajib pakai password yg sudah disediakan</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal Logout JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (!empty($logoutMessage)) : ?>
        var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        logoutModal.show();
    <?php endif; ?>
});

// Animasi fade-in untuk form login
document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".fade-in-target");
    elements.forEach((element, index) => {
        setTimeout(() => {
            element.classList.add("fade-in");
        }, index * 200);
    });
});
</script>

</body>
</html>
