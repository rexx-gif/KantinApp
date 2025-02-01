<?php
session_start();

$username = isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$is_pelanggan = ($role === 'Pelanggan');
$is_admin = ($role === 'Admin');
$is_guest = ($role === 'Guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <link rel="stylesheet" href="style/404.css">
    <link rel="shortcut icon" href="image/404X-Kantin.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <img src="image/background404.png" alt="">
    <div class="container">
        <div class="lost-icon"></div>
        <h1>Oops, You're Lost!</h1>
        <p>Hello <strong><?php echo htmlspecialchars($username)." "."($role)"; ?></strong>, you seem to be in the wrong place!</p>
        
        <?php if ($is_admin) { ?>
            <p>If you're an admin, please go back and do your task in X-Kantin.</p>
        <?php } else { ?>
            <p>If you're not an admin, you can't access this page in X-Kantin.</p>
            <p>Redirecting you to the home page in <span id="countdown">5</span> seconds...</p>
        <?php } ?>
        
        <div class="a">
            <a href="menu.php">Return to Home</a>
        </div>
    </div>

    <script>
        let countdown = 5;
        function countdownMenu() {
            const countdownElement = document.getElementById('countdown');
            countdownElement.textContent = countdown;
            if (countdown > 0) {
                countdown--;
                setTimeout(countdownMenu, 1000);
            } else {
                window.location.href = 'menu.php';
            }
        }
        window.onload = countdownMenu;
    </script>
</body>
</html>
