<?php
session_start();
include '../config/db.php';

// Cek jika sudah login dan role adalah customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../index.php");
    exit();
}

// Ambil semua data kamar
$sql = "SELECT room_number, type, price, status FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Informasi Kamar</title>
    <style>
        table {
            width: 70%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #999;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Daftar Informasi Kamar</h2>

<table>
    <tr>
        <th>No Kamar</th>
        <th>Tipe</th>
        <th>Harga per Malam</th>
        <th>Status</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['room_number']) ?></td>
                <td><?= htmlspecialchars($row['type']) ?></td>
                <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                <td><?= $row['status'] == 'available' ? 'Tersedia' : 'Terbooking' ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4">Tidak ada kamar tersedia.</td></tr>
    <?php endif; ?>
</table>

<div style="text-align:center;">
    <a href="dashboard_customer.php">Kembali ke Dashboard</a>
</div>

</body>
</html>
