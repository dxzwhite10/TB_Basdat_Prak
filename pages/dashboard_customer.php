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

$user_id = $_SESSION['user_id'];

// Otomatis batalkan booking unpaid yang sudah kadaluarsa
$conn->query("UPDATE bookings 
              SET status = 'cancelled' 
              WHERE status = 'pending' 
              AND payment_status = 'unpaid' 
              AND expired_at < NOW()");

// Hitung notifikasi belum dibaca untuk badge
$sql_unread = "SELECT COUNT(*) as total_unread FROM notifications WHERE user_id = $user_id AND is_read = 0";
$result_unread = $conn->query($sql_unread);
$unread_count = 0;
if ($result_unread && $row = $result_unread->fetch_assoc()) {
    $unread_count = $row['total_unread'];
}

// Tandai notifikasi sebagai telah dibaca
$conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id AND is_read = 0");

// Ambil notifikasi terbaru
$notifications = [];
$sql = "SELECT n.message, n.created_at, b.id AS booking_id, b.payment_status
        FROM notifications n
        LEFT JOIN bookings b ON n.room_id = b.room_id AND n.user_id = b.user_id
        WHERE n.user_id = $user_id
        ORDER BY n.created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}

// Ambil satu kamar tersedia dari tiap tipe
$rooms = [];
$sql_rooms = "
    (SELECT id, room_number, type, price, image FROM rooms 
     WHERE type = 'standar' AND status = 'available' LIMIT 1)
    UNION
    (SELECT id, room_number, type, price, image FROM rooms 
     WHERE type = 'superior' AND status = 'available' LIMIT 1)
    UNION
    (SELECT id, room_number, type, price, image FROM rooms 
     WHERE type = 'deluxe' AND status = 'available' LIMIT 1)";
$result_rooms = $conn->query($sql_rooms);
if ($result_rooms && $result_rooms->num_rows > 0) {
    while ($row = $result_rooms->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pelanggan - Hotel UNIKU</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
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
            background-color: rgba(255, 255, 255, 0.95);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            position: relative;
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
        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-links a {
            text-decoration: none;
            color: rgb(252, 146, 25);
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgb(252, 146, 25);
            color: white;
        }
        .notif-icon {
            position: relative;
            cursor: pointer;
        }
        .notif-icon img {
            height: 24px;
        }
        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: red;
            color: white;
            border-radius: 50%;
            font-size: 10px;
            padding: 2px 6px;
        }
        .notif-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 30px;
            background: white;
            width: 300px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            border-radius: 6px;
            z-index: 99;
        }
        .notif-dropdown.active {
            display: block;
        }
        .notif-item {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        .notif-item:last-child {
            border-bottom: none;
        }
        .notif-item time {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        .room-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 120px auto 50px;
            max-width: 1000px;
            flex-wrap: wrap;
        }
        .room-card {
            background: rgba(255, 255, 255, 0.95);
            width: 300px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.2);
            overflow: hidden;
            text-align: center;
        }
        .room-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .room-card .details {
            padding: 15px;
        }
        .room-card .details h3 {
            margin: 10px 0 5px;
            color: #333;
        }
        .room-card .details p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .room-card .details a {
            display: inline-block;
            margin-top: 10px;
            background: rgb(252,146,25);
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        @media screen and (max-width: 800px) {
            .room-container {
                flex-direction: column;
                align-items: center;
            }
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
    <div class="nav-links">
        <a href="booking.php">Booking Kamar</a>
        <a href="informasi_rooms.php">Lihat Informasi Kamar</a>
        <a href="../logout.php">Logout</a>
        <div class="notif-icon" onclick="toggleNotifDropdown()">
            <img src="../assets/icons/bell.png" alt="Notifikasi">
            <?php if ($unread_count > 0): ?>
                <span class="notif-badge"><?= $unread_count ?></span>
            <?php endif; ?>
            <div class="notif-dropdown" id="notifDropdown">
                <?php foreach ($notifications as $notif): ?>
                    <div class="notif-item">
                        <?= htmlspecialchars($notif['message']) ?>
                        <?php if ($notif['payment_status'] === 'unpaid' && $notif['booking_id']): ?>
                            <br><a href="payment.php?booking_id=<?= $notif['booking_id'] ?>">💳 Bayar Sekarang</a>
                        <?php endif; ?>
                        <time><?= htmlspecialchars($notif['created_at']) ?></time>
                    </div>
                <?php endforeach; ?>
                <?php if (count($notifications) === 0): ?>
                    <div class="notif-item">Tidak ada notifikasi baru.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="room-container">
    <?php foreach ($rooms as $room): ?>
        <div class="room-card">
            <img src="../<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['type']) ?>">
            <div class="details">
                <h3><?= htmlspecialchars($room['room_number']) ?></h3>
                <p>Tipe: <?= htmlspecialchars($room['type']) ?></p>
                <p>Harga: Rp <?= number_format($room['price'], 0, ',', '.') ?></p>
                <a href="booking.php?type=<?= strtolower($room['type']) ?>">Pesan Sekarang</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function toggleNotifDropdown() {
    var dropdown = document.getElementById('notifDropdown');
    dropdown.classList.toggle('active');
}
document.addEventListener('click', function(e) {
    var notifIcon = document.querySelector('.notif-icon');
    var dropdown = document.getElementById('notifDropdown');
    if (!notifIcon.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});
</script>

</body>
</html>
