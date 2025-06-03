<?php
session_start();
include('../config/db.php');

// Cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Proses pembatalan booking jika tombol Batalkan diklik
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $booking_id = intval($_GET['cancel']);

    // Ambil room_id dari booking
    $booking = $conn->query("SELECT room_id FROM bookings WHERE id = $booking_id AND payment_status = 'unpaid'");
    if ($booking && $booking->num_rows > 0) {
        $room = $booking->fetch_assoc();
        $room_id = $room['room_id'];

        // Update status booking jadi cancelled
        $conn->query("UPDATE bookings SET status = 'cancelled' WHERE id = $booking_id");

        // Kembalikan kamar jadi available
        $conn->query("UPDATE rooms SET status = 'available' WHERE id = $room_id");

        $message = "Booking ID $booking_id berhasil dibatalkan.";
    }
}

// Ambil semua data booking
$sql = "SELECT 
            b.id, 
            u.name AS user, 
            r.room_number, 
            b.check_in, 
            b.check_out,
            b.status,
            b.payment_status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        ORDER BY b.check_in DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Booking</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-batal { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Pemesanan Kamar</h2>

    <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>

    <table>
        <tr>
            <th>No</th>
            <th>Pengguna</th>
            <th>Nomor Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th>Batalkan Booking</th>
        </tr>
        <?php if ($result->num_rows > 0): 
            $no = 1;
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['user']) ?></td>
                <td><?= htmlspecialchars($row['room_number']) ?></td>
                <td><?= htmlspecialchars($row['check_in']) ?></td>
                <td><?= htmlspecialchars($row['check_out']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['payment_status']) ?></td>
                <td>
                    <?php if ($row['payment_status'] === 'unpaid' && $row['status'] !== 'cancelled'): ?>
                        <a href="?cancel=<?= $row['id'] ?>" class="btn-batal" onclick="return confirm('Yakin batalkan booking ini?')">Batalkan</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="8">Belum ada data booking.</td></tr>
        <?php endif; ?>
    </table>

    <br>
    <a href="admin_dashboard.php">⬅ Kembali ke Dashboard</a>
</body>
</html>


