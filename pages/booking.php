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
<html>
<head>
    <title>Booking Kamar</title>
</head>
<body>
    <h2>Form Booking Kamar</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <form method="post">
        <label for="room_id">Pilih Kamar:</label>
        <select name="room_id" required>
            <option value="">-- Pilih Kamar --</option>
            <?php while ($row = $available_rooms->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['room_number'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        Check-in: <input type="date" name="check_in" required><br>
        Check-out: <input type="date" name="check_out" required><br><br>

        <button type="submit">Pesan Sekarang</button>
    </form>

    <br>
    <a href="dashboard_customer.php">⬅ Kembali ke Dashboard</a>
</body>
</html>

