<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang Uniku - Hotel UNIKU</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            overflow: hidden;
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

        .dashboard-content {
    margin: auto;
    background: rgba(255,255,255,0.92);
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    max-width: 700px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.menu-grid a {
    text-decoration: none;
    font-weight: bold;
    color: rgb(252, 146, 25);
    padding: 15px;
    border: 2px solid rgb(252, 146, 25);
    border-radius: 10px;
    transition: all 0.3s ease;
    background-color: white;
}

.menu-grid a:hover {
    background-color: rgb(252, 146, 25);
    color: white;
}

    </style>
</head>
<body>

    <div class="slideshow"></div>

    <div class="header">
        <div class="logo-title">
            <img src="../assets/logo_uniku.png" alt="Logo Hotel UNIKU">
            <h2>Selamat Datang Di Hotel UNIKU</h2>
        </div>
    </div>

    <div class="dashboard-content">
    <div class="menu-grid">
        <a href="manage_rooms.php">Kelola Kamar</a>
        <a href="bookings_report.php">Laporan Booking</a>
        <a href="kelola_user.php">Kelola Data User</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
