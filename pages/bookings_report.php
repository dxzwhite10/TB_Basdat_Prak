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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff3e0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-family: 'Poppins', cursive;
            font-size: 36px;
            color: #e65100;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff8e1;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 14px;
            border: 1px solid #ffe082;
            text-align: center;
        }

        th {
            background-color: #ffb300;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fffde7;
        }

        .btn-batal {
            background-color: transparent;
            color: #d84315;
            font-weight: bold;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-batal:hover {
            text-decoration: underline;
        }

        .message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-left: 5px solid #66bb6a;
            margin-bottom: 20px;
            max-width: 500px;
        }

        a.back-link {
            display: inline-block;
            margin-top: 30px;
            color: #e65100;
            text-decoration: none;
            font-weight: bold;
        }

        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Laporan Pemesanan Kamar</h2>

    <?php if (isset($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

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

    <a href="admin_dashboard.php" class="back-link">⬅ Kembali ke Dashboard</a>

</body>
</html>