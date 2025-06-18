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

// Cegah admin menghapus dirinya sendiri
if ($id == $_SESSION['user_id']) {
    echo "Anda tidak dapat menghapus akun Anda sendiri.";
    exit;
}

// Hapus user
$conn->query("DELETE FROM users WHERE id = $id");

header("Location: kelola_user.php");
exit;
?>
