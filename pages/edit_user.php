<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID user tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'customer'; // hanya dua pilihan role

    $conn->query("UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id");

    header("Location: kelola_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>

    <form method="post">
        Nama: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
        Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
        Role: 
        <select name="role">
            <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <br><a href="kelola_user.php">⬅ Kembali</a>
</body>
</html>
