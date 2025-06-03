    <?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../index.php");
    exit;
}

// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_hotel";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil notifikasi berdasarkan user_id
$user_id = $_SESSION['user_id'];
$notifications = [];

$sql = "SELECT message, created_at FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pelanggan</title>
    <style>
        h2 { margin-bottom: 10px; }
        .menu a {
            display: inline-block;
            margin-right: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .menu a:hover { text-decoration: underline; }
        .notif-box {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            max-width: 700px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .notif-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-time {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Dashboard Pelanggan</h2>
    <div class="menu">
        <a href="booking.php">Booking Kamar</a>
        <a href="informasi_rooms.php">Lihat Informasi Kamar</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="notif-box">
        <h3>Notifikasi Terbaru</h3>
        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notif-item">
                    <strong><?= htmlspecialchars($notif['message']) ?></strong><br>
                    <span class="notif-time"><?= htmlspecialchars($notif['created_at']) ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada notifikasi baru.</p>
        <?php endif; ?>
    </div>
</body>
</html>
