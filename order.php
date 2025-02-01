<?php
session_start();
include('include/config.php');

// Ambil semua makanan
$query_makanan = "SELECT * FROM makanan";
$result_makanan = $conn->query($query_makanan);
if (!$result_makanan) {
    die("Error pada query makanan: " . $conn->error);
}

// Ambil semua minuman
$query_minuman = "SELECT * FROM minuman";
$result_minuman = $conn->query($query_minuman);
if (!$result_minuman) {
    die("Error pada query minuman: " . $conn->error);
}

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelanggan = isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'; // Nama pelanggan dari session
    $pesanan = $_POST['pesanan']; // Data makanan dan minuman yang dipesan
    $status = 'Dikirim';  // Status pesanan

    foreach ($pesanan as $item_id => $quantity) {
        if ($quantity > 0) {
            // Cari item di tabel makanan dan minuman berdasarkan item_id
            $query_item = "SELECT 'makanan' AS jenis, id, nama_makanan AS nama, harga FROM makanan WHERE id = ? 
                           UNION 
                           SELECT 'minuman' AS jenis, id, nama_minuman AS nama, harga FROM minuman WHERE id = ?";
            $stmt_item = $conn->prepare($query_item);
            $stmt_item->bind_param("ii", $item_id, $item_id);
            $stmt_item->execute();
            $result_item = $stmt_item->get_result();
            $item = $result_item->fetch_assoc();

            if ($item) {
                $total_harga = $item['harga'] * $quantity;
                $nama_item = $item['nama'];

                // Query untuk memasukkan pemesanan ke dalam tabel
                $query_pemesanan = "INSERT INTO pemesanan (nama_pelanggan, jenis_item, item_id, nama_item, quantity, total_harga, status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_pemesanan = $conn->prepare($query_pemesanan);
                $stmt_pemesanan->bind_param(
                    "ssisisd",
                    $nama_pelanggan,  // Nama pelanggan
                    $item['jenis'],   // Jenis item (makanan/minuman)
                    $item['id'],      // ID item (makanan/minuman)
                    $nama_item,       // Nama item (makanan atau minuman)
                    $quantity,        // Jumlah pesanan
                    $total_harga,     // Total harga
                    $status           // Status pemesanan
                );

                // Cek jika eksekusi berhasil
                if ($stmt_pemesanan->execute()) {
                    echo "<script>alert('Pesanan berhasil dikirim!'); window.location.href='menu.php';</script>";
                } else {
                    echo "Pemesanan gagal! Error: " . $stmt_pemesanan->error;
                }
            }
        }
    }
    exit;
}



if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please Login First'); window.location.href='Login/login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan dan Minuman</title>
    <link rel="stylesheet" href="style/form.css">
    <link rel="shortcut icon" href="image/LogoX-Kantin.png" type="image/x-icon">
    <script>
        // Fungsi untuk menghitung total harga
        function hitungTotal() {
            let total = 0;
            const inputs = document.querySelectorAll('input[data-harga]');
            inputs.forEach(input => {
                const harga = parseFloat(input.dataset.harga);
                const jumlah = parseInt(input.value) || 0;
                total += harga * jumlah;
            });
            document.getElementById('total-harga').innerText = `Total: Rp ${total.toLocaleString('id-ID')}`;
        }

        // Fungsi untuk konfirmasi sebelum mengirimkan pesanan
        function konfirmasiPesanan(event) {
            event.preventDefault(); // Mencegah form langsung dikirim
            const total = document.getElementById('total-harga').innerText;
            const konfirmasi = confirm(`Anda yakin ingin mengirimkan pesanan dengan ${total}?`);
            if (konfirmasi) {
                document.getElementById('form-pesanan').submit(); // Kirimkan form
            }
        }
    </script>
</head>
<body>
    <form method="POST" id="form-pesanan" onsubmit="konfirmasiPesanan(event)">
        <h1>Pesan Makanan dan Minuman</h1>
        <p>Nama Pelanggan: <?php echo htmlspecialchars(isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'); ?></p>

        <h2>Makanan</h2>
        <?php if ($result_makanan && $result_makanan->num_rows > 0): ?>
            <?php while ($row = $result_makanan->fetch_assoc()): ?>
                <div>
                    <p><?php echo htmlspecialchars($row['nama_makanan'])." - ".($row['deskripsi']); ?> - Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <label for="makanan_<?php echo $row['id']; ?>">Jumlah:</label>
                    <input 
                    type="number" 
                    id="makanan_<?php echo $row['id']; ?>" 
                    name="pesanan[<?php echo $row['id']; ?>]" 
                    min="0" 
                    value="0" 
                    data-harga="<?php echo $row['harga']; ?>" 
                    oninput="hitungTotal()">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada makanan tersedia.</p>
        <?php endif; ?>

        <h2>Minuman</h2>
        <?php if ($result_minuman && $result_minuman->num_rows > 0): ?>
            <?php while ($row = $result_minuman->fetch_assoc()): ?>
                <div>
                    <p><?php echo htmlspecialchars($row['nama_minuman']); ?> - Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <label for="minuman_<?php echo $row['id']; ?>">Jumlah:</label>
                    <input 
                        type="number" 
                        id="minuman_<?php echo $row['id']; ?>" 
                        name="pesanan[<?php echo $row['id']; ?>]" 
                        min="0" 
                        value="0" 
                        data-harga="<?php echo $row['harga']; ?>" 
                        oninput="hitungTotal()">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada minuman tersedia.</p>
        <?php endif; ?>

        <!-- Elemen untuk total harga -->
        <h3 id="total-harga">Total: Rp 0</h3>

        <button type="submit">Pesan</button>
    </form>
</body>
</html>