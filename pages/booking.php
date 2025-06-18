<?php
session_start();
include('../config/db.php');

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = intval($_POST['room_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out) 
            VALUES ($user_id, $room_id, '$check_in', '$check_out')";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id;
        $conn->query("UPDATE rooms SET status = 'booked' WHERE id = $room_id");
        header("Location: payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        $message = "❌ Gagal memesan: " . $conn->error;
    }
}

$type_filter = isset($_GET['type']) ? strtolower($_GET['type']) : '';

// Hitung berapa tipe yang ditampilkan
$active_column_count = 0;
if ($type_filter == '' || $type_filter == 'standar') $active_column_count++;
if ($type_filter == '' || $type_filter == 'superior') $active_column_count++;
if ($type_filter == '' || $type_filter == 'deluxe') $active_column_count++;

$sql = "SELECT * FROM rooms WHERE status = 'available'";
if (in_array($type_filter, ['standar', 'superior', 'deluxe'])) {
    $sql .= " AND LOWER(type) = '$type_filter'";
}
$available_rooms = $conn->query($sql);

$standar = [];
$superior = [];
$Deluxe = [];

$available_rooms->data_seek(0);
while ($room = $available_rooms->fetch_assoc()) {
    if (strtolower($room['type']) == 'standar') {
        $standar[] = $room;
    } elseif (strtolower($room['type']) == 'superior') {
        $superior[] = $room;
    } elseif (strtolower($room['type']) == 'deluxe') {
        $Deluxe[] = $room;
    }
}

function generateRoomCard($room) {
    ob_start(); ?>
    <div class="room-card">
        <img src="../<?= htmlspecialchars($room['image']) ?>" alt="Kamar <?= $room['room_number'] ?>">
        <div class="room-details">
            <h3>Kamar <?= htmlspecialchars($room['room_number']) ?></h3>
            <p>Tipe: <?= htmlspecialchars($room['type']) ?></p>
            <p>Harga: Rp<?= number_format($room['price'], 0, ',', '.') ?></p>
            <form method="post">
                <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                <label for="check_in">Check-in:</label>
                <input type="date" name="check_in" required>
                <label for="check_out">Check-out:</label>
                <input type="date" name="check_out" required>
                <button type="submit">Pesan Sekarang</button>
            </form>
        </div>
    </div>
    <?php return ob_get_clean();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Kamar - Hotel UNIKU</title>
    <style>
    .judul-booking {
    text-align: center;
    font-size: 26px;
    margin-bottom: 20px;
    color: #333;
}
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
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
            position: sticky;
            top: 0;
            z-index: 10;
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

        .container {
            background: rgba(255, 255, 255, 0.95);
            margin: 30px auto;
            padding: 20px;
            border-radius: 12px;
            max-width: 1200px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .three-column {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .column {
            flex: 1;
            background: rgba(255,255,255,0.9);
            padding: 10px;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .column h3 {
            text-align: center;
            color: #444;
            margin-bottom: 10px;
        }

        .room-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .room-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .room-details {
            padding: 15px;
        }

        .room-details h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .room-details p {
            margin: 6px 0;
            font-size: 14px;
            color: #555;
        }

        .room-details form {
            margin-top: 10px;
        }

        .room-details input, .room-details button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .room-details button {
            background-color: rgb(252, 146, 25);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .room-details button:hover {
            background-color: #e48b0f;
        }

        .message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }.

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
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

<div class="container">
    <h2 class="judul-booking">Booking Kamar</h2>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <?php
    $three_column_style = '';
    if ($active_column_count == 1) {
        $three_column_style = 'max-width: 420px; margin: 0 auto; justify-content: center;';
    } elseif ($active_column_count == 2) {
        $three_column_style = 'max-width: 860px; margin: 0 auto;';
    } else {
        $three_column_style = 'max-width: 1200px; margin: 0 auto;';
    }
    ?>
    <div class="three-column" style="<?= $three_column_style ?>">
        <?php if ($type_filter == 'standar' || $type_filter == ''): ?>
            <div class="column"><h3>Standar</h3>
                <?php foreach ($standar as $room): ?>
                    <?= generateRoomCard($room) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($type_filter == 'superior' || $type_filter == ''): ?>
            <div class="column"><h3>Superior</h3>
                <?php foreach ($superior as $room): ?>
                    <?= generateRoomCard($room) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($type_filter == 'deluxe' || $type_filter == ''): ?>
            <div class="column"><h3>Deluxe</h3>
                <?php foreach ($Deluxe as $room): ?>
                    <?= generateRoomCard($room) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <a href="dashboard_customer.php">⬅ Kembali ke Dashboard</a>
</div>

</body>
</html>
