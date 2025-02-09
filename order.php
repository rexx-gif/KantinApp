<?php
session_start();
include('include/config.php');
unset($_SESSION['order']); // Reset order session at the beginning

// Initialize total price
$total_harga = 0;

// Mengecek jika ada makanan yang dipilih
if (isset($_GET['id_makanan']) && is_numeric($_GET['id_makanan'])) {
    $makanan_id = $_GET['id_makanan'];
    $query_makanan = "SELECT * FROM makanan WHERE id = ?";
    $stmt_makanan = $conn->prepare($query_makanan);

    if ($stmt_makanan) {
        $stmt_makanan->bind_param("i", $makanan_id);
        $stmt_makanan->execute();
        $result_makanan = $stmt_makanan->get_result();

        if ($result_makanan->num_rows > 0) {
            $makanan = $result_makanan->fetch_assoc();
            $_SESSION['order']['makanan'][] = [
                'nama' => $makanan['nama_makanan'],
                'harga' => $makanan['harga']
            ];
            $total_harga += $makanan['harga'];
        }
    }
}

// Mengecek jika ada minuman yang dipilih
if (isset($_GET['id_minuman']) && is_numeric($_GET['id_minuman'])) {
    $minuman_id = $_GET['id_minuman'];
    $query_minuman = "SELECT * FROM minuman WHERE id = ?";
    $stmt_minuman = $conn->prepare($query_minuman);

    if ($stmt_minuman) {
        $stmt_minuman->bind_param("i", $minuman_id);
        $stmt_minuman->execute();
        $result_minuman = $stmt_minuman->get_result();

        if ($result_minuman->num_rows > 0) {
            $minuman = $result_minuman->fetch_assoc();
            $_SESSION['order']['minuman'][] = [
                'nama' => $minuman['nama_minuman'],
                'harga' => $minuman['harga']
            ];
            $total_harga += $minuman['harga'];
        }
    }
}

// Display the total price
echo "<h3>Total Harga: Rp " . number_format($total_harga, 0, ',', '.') . "</h3>";
?>

<h4>Daftar Makanan yang Dipilih:</h4>
<ul>
    <?php
    if (!empty($_SESSION['order']['makanan'])) {
        foreach ($_SESSION['order']['makanan'] as $item) {
            echo "<li>" . htmlspecialchars($item['nama']) . " - Rp " . number_format($item['harga'], 0, ',', '.') . "</li>";
        }
    } else {
        echo "<li>Tidak ada makanan yang dipilih.</li>";
    }
    ?>
</ul>

<h4>Daftar Minuman yang Dipilih:</h4>
<ul>
    <?php
    if (!empty($_SESSION['order']['minuman'])) {
        foreach ($_SESSION['order']['minuman'] as $item) {
            echo "<li>" . htmlspecialchars($item['nama']) . " - Rp " . number_format($item['harga'], 0, ',', '.') . "</li>";
        }
    } else {
        echo "<li>Tidak ada minuman yang dipilih.</li>";
    }
    ?>
</ul>

<form method="POST" action="process_order.php">
    <label for="payment_method">Choose Payment Method:</label><br>
    <input type="radio" id="cash" name="payment_method" value="cash_on_delivery" checked onclick="togglePaymentOptions()">
    <label for="cash">Cash on Delivery</label><br>
    <input type="radio" id="online" name="payment_method" value="online_payment" onclick="togglePaymentOptions()">
    <label for="online">Online Payment</label><br>

    <div id="online_payment_options" style="display: none;">
        <label for="online_payment">Select Payment Method:</label>
        <select name="online_payment" id="online_payment">
            <option value="Ovo">Ovo</option>
            <option value="Dana">Dana</option>
            <option value="ShopeePay">Shopee Pay</option>
            <option value="GoPay">Go-Pay</option>
            <option value="qris">Qris</option>
        </select>
    </div>

    <?php
    // Hidden inputs for selected foods and drinks
    if (!empty($_SESSION['order']['makanan'])) {
        foreach ($_SESSION['order']['makanan'] as $item) {
            echo '<input type="hidden" name="nama_makanan[]" value="' . htmlspecialchars($item['nama']) . '">';
        }
    }

    if (!empty($_SESSION['order']['minuman'])) {
        foreach ($_SESSION['order']['minuman'] as $item) {
            echo '<input type="hidden" name="nama_minuman[]" value="' . htmlspecialchars($item['nama']) . '">';
        }
    }
    ?>

    <input type="hidden" name="total_harga" value="<?php echo $total_harga; ?>">
    <a href="menu.php">Pesen Lagi</a>
    <button type="submit">Order Now</button>
</form>

<script>
function togglePaymentOptions() {
    let onlinePaymentOptions = document.getElementById("online_payment_options");
    onlinePaymentOptions.style.display = document.getElementById("online").checked ? "block" : "none";
}
</script>
