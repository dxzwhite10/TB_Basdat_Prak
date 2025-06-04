<?php
session_start();
include('../config/db.php');

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = intval($_POST['room_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $user_id = $_SESSION['user_id'];

    // Simpan ke database
    $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out) 
            VALUES ($user_id, $room_id, '$check_in', '$check_out')";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id;

        // Update status kamar
        $conn->query("UPDATE rooms SET status = 'booked' WHERE id = $room_id");

        // Redirect ke halaman pembayaran
        header("Location: payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        $message = "❌ Gagal memesan: " . $conn->error;
    }
}

// Ambil daftar kamar yang tersedia
$available_rooms = $conn->query("SELECT id, room_number FROM rooms WHERE status = 'available'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Kamar - Hotel UNIKU</title>
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

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        select, input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: rgb(252, 146, 25);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #e48b0f;
        }

        a {
            display: inline-block;
            margin-top: 10px;
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

<div class="form-container">
    <h2>Form Booking Kamar</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <form method="post">
        <label for="room_id">Pilih Kamar:</label>
        <select name="room_id" required>
            <option value="">-- Pilih Kamar --</option>
            <?php while ($row = $available_rooms->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['room_number'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="check_in">Check-in:</label>
        <input type="date" name="check_in" required>

        <label for="check_out">Check-out:</label>
        <input type="date" name="check_out" required>

        <button type="submit">Pesan Sekarang</button>
    </form>

    <a href="dashboard_customer.php">⬅ Kembali ke Dashboard</a>
</div>

</body>
</html>

