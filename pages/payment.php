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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - Hotel UNIKU</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            overflow: auto;
            display: flex;
            flex-direction: column;
        }

        .slideshow {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-size: cover;
            background-position: center;
            animation: slideShow 18s infinite;
        }

        @keyframes slideShow {
            0%   { background-image: url('../assets/bg1.jpg'); }
            33%  { background-image: url('../assets/bg2.jpg'); }
            66%  { background-image: url('../assets/bgg3.jpg'); }
            100% { background-image: url('../assets/bg1.jpg'); }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255,255,255,0.95);
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .logo-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-title img {
            height: 50px;
        }

        .logo-title h2 {
            margin: 0;
            color: #333;
        }

        .form-container {
            margin: 40px auto;
            background: rgba(255,255,255,0.92);
            padding: 30px;
            border-radius: 12px;
            text-align: left;
            max-width: 600px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        p {
            margin: 10px 0;
            font-size: 16px;
        }

        strong {
            color: #333;
        }

        button {
            background-color: rgb(252, 146, 25);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
        }

        button:hover {
            background-color: #e48b0f;
        }
    </style>
</head>
<body>

<div class="slideshow"></div>

<div class="header">
    <div class="logo-title">
        <img src="../assets/logo_uniku.png" alt="Logo Hotel UNIKU">
        <h2>Hotel UNIKU</h2>
    </div>
</div>

<div class="form-container">
    <h2>Pembayaran</h2>
    <p><strong>Kamar:</strong> <?= htmlspecialchars($booking['room_number']) ?></p>
    <p><strong>Harga:</strong> Rp<?= number_format($booking['price'], 0, ',', '.') ?></p>
    <p><strong>Status Pembayaran:</strong> <?= htmlspecialchars($booking['payment_status']) ?></p>

    <?php if ($booking['payment_status'] == 'unpaid'): ?>
        <form method="POST">
            <button type="submit">Bayar Sekarang</button>
        </form>
    <?php else: ?>
        <p><em>Pembayaran sudah dilakukan.</em></p>
    <?php endif; ?>
</div>

</body>
</html>