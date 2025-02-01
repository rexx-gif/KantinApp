<?php
session_start();

// Ambil data session
$username = $_SESSION['user'] ?? 'Guest';
$role = $_SESSION['role'] ?? 'guest';

$is_admin = ($role === 'Admin');
$is_pelanggan = ($role === 'Pelanggan');
$is_guest = ($role === 'guest'); // Perbaikan huruf kecil

// Pesan modal selamat datang
if ($is_admin || $is_pelanggan) {
    $message = "Selamat datang, " . htmlspecialchars($role) . " " . htmlspecialchars($username) . " Tercinta di X-Kantin!";
} else {
    $message = "Selamat datang " . htmlspecialchars($role);
}

// Jika Guest, tampilkan alert & modal login
if ($is_guest) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Kantin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="image/LogoX-Kantin.png" type="image/x-icon">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="image/LogoX-Kantin.png" alt="Logo Kantin">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li class="dropdown">
                <a href="menu.php" class="dropbtn">Menu <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="menu.php#makanan">Makanan</a>
                    <a href="menu.php#minuman">Minuman</a>
                </div>
            </li>
        </ul>
        <div class="menu-icon">
            <a href="logout.php" class="logout" style="text-decoration:none; color:white;">
                <?php echo htmlspecialchars($username) . " (" . htmlspecialchars($role) . ")"; ?> 
                <i class="fa-solid fa-circle-user" style="color: #ffffff;"></i>
            </a>
        </div>
    </nav>

    <!-- Background Section -->
    <div class="background-container">
        <img src="image/backgroundX-Kantin.png" alt="Background Image" class="background-image">
        <div class="text-container">
            <h1>Hi <?php echo htmlspecialchars($username) . " (" . htmlspecialchars($role) . ")"; ?> </h1>
            <h1>Welcome to X-Kantin</h1>
            <p>
                Kami menyajikan berbagai pilihan makanan dan minuman segar dengan harga terjangkau. 
                Nikmati suasana nyaman dan menu yang bervariasi, cocok untuk makan bersama teman 
                atau sekadar bersantai.
            </p>
        </div>
    </div>

    <!-- Modal untuk Login (Hanya 1, Tidak Duplikat) -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header text-light">
                    <h5 class="modal-title" id="loginModalLabel">X-Kantin Alert</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="window.location.href='Login/login.php'" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-light text-center">
                   <p>Selamat Datang Guest, kamu di mohon login terlebih dahulu</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger w-50" onclick="window.location.href='Login/login.php'">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Selamat Datang (Hanya untuk User yang Bukan Guest) -->
    <?php if (!$is_guest): ?>
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header text-light d-flex justify-content-between">
                    <h5 class="modal-title" id="modalTitle">X-Kantin Alert</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-light text-center">
                    <?= $message; ?>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success w-50" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const transitionLayer = document.createElement("div");
        transitionLayer.style.position = "fixed";
        transitionLayer.style.top = "0";
        transitionLayer.style.left = "0";
        transitionLayer.style.width = "100%";
        transitionLayer.style.height = "100%";
        transitionLayer.style.backgroundColor = "black";
        transitionLayer.style.zIndex = "9999";
        transitionLayer.style.opacity = "0";
        transitionLayer.style.pointerEvents = "none";
        transitionLayer.style.transition = "opacity 1s ease-in-out";
        document.body.appendChild(transitionLayer);

        const links = document.querySelectorAll("a");
        links.forEach(link => {
            link.addEventListener("click", (event) => {
                const href = link.getAttribute("href");
                if (href && !href.startsWith("#") && !href.startsWith("javascript")) {
                    event.preventDefault();
                    transitionLayer.style.pointerEvents = "auto";
                    transitionLayer.style.opacity = "1";
                    setTimeout(() => {
                        window.location.href = href;
                    }, 1000);
                }
            });
        });

        window.addEventListener("pageshow", () => {
            transitionLayer.style.opacity = "0"; 
            setTimeout(() => {
                transitionLayer.style.pointerEvents = "none";
            }, 1000); 
        });
    });
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!$is_guest): ?>
                var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                welcomeModal.show();
                <?php if ($is_admin): ?>
                    var adminModal = new bootstrap.Modal(document.getElementById('adminModal'));
                    adminModal.show();
                <?php elseif ($is_pelanggan): ?>
                    var pelangganModal = new bootstrap.Modal(document.getElementById('pelangganModal'));
                    pelangganModal.show();
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>

</body>
</html>
