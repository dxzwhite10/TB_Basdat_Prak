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

<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Informasi Kamar</title>
  <link href="https://fonts.googleapis.com/css2?family=Bad+Script&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      height: 100%;
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

    .container {
      padding: 40px 20px;
      background-color: rgba(255, 255, 255, 0.95);
      margin: 50px auto;
      max-width: 900px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      color: rgb(252, 146, 25);
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px 16px;
      border: 1px solid #ccc;
      text-align: center;
      font-size: 15px;
    }

    th {
      background-color: #f8f8f8;
      color: #333;
    }

    tr:nth-child(even) {
      background-color: #fcfcfc;
    }

    .status-available {
      color: green;
      font-weight: bold;
    }

    .status-booked {
      color: red;
      font-weight: bold;
    }

    .back-button {
      text-align: center;
      margin-top: 30px;
    }

    .back-button a {
      text-decoration: none;
      background-color: rgb(252, 146, 25);
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .back-button a:hover {
      background-color: rgb(230, 120, 10);
    }

    @media screen and (max-width: 600px) {
      .container {
        padding: 20px 10px;
      }

      table th, table td {
        font-size: 13px;
        padding: 8px;
      }
    }
  </style>
</head>
<body>

<div class="slideshow"></div>

<div class="container">
  <h2>Daftar Informasi Kamar</h2>

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
          <td class="<?= $row['status'] == 'available' ? 'status-available' : 'status-booked' ?>">
            <?= $row['status'] == 'available' ? 'Tersedia' : 'Terbooking' ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4">Tidak ada kamar tersedia.</td></tr>
    <?php endif; ?>
  </table>

  <div class="back-button">
    <a href="dashboard_customer.php">⬅ Kembali ke Dashboard</a>
  </div>
</div>

</body>
</html>