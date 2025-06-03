<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../index.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "db_hotel";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['booking_id'])) {
    echo "Booking ID tidak ditemukan.";
    exit;
}

$booking_id = (int) $_GET['booking_id'];

// Ambil data booking
$sql = "SELECT b.*, r.room_number, r.price FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.id = $booking_id AND b.user_id = {$_SESSION['user_id']}";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "Data booking tidak valid.";
    exit;
}

$booking = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update status booking
    $update = "UPDATE bookings SET payment_status = 'paid', status = 'confirmed' WHERE id = $booking_id";
    $conn->query($update);

    // Tambah notifikasi
    $msg = "Pembayaran untuk kamar {$booking['room_number']} telah berhasil.";
    $conn->query("INSERT INTO notifications (user_id, room_id, message) VALUES ({$_SESSION['user_id']}, {$booking['room_id']}, '$msg')");

    header("Location: dashboard_customer.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
</head>
<body>
    <h2>Pembayaran</h2>
    <p><strong>Kamar:</strong> <?= htmlspecialchars($booking['room_number']) ?></p>
    <p><strong>Harga:</strong> Rp<?= number_format($booking['price'], 0, ',', '.') ?></p>
    <p><strong>Status Pembayaran:</strong> <?= htmlspecialchars($booking['payment_status']) ?></p>

    <?php if ($booking['payment_status'] == 'unpaid'): ?>
        <form method="POST">
            <button type="submit">Bayar Sekarang</button>
        </form>
    <?php else: ?>
        <p>Pembayaran sudah dilakukan.</p>
    <?php endif; ?>
</body>
</html>
