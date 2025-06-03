<?php
session_start();
include('../config/db.php');

// Cek apakah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data User</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
        a.button { padding: 5px 10px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none; margin-right: 5px; }
        .danger { background-color: #dc3545; }
    </style>
</head>
<body>

<h2>Kelola Data User</h2>

<form method="get">
    <input type="text" name="q" placeholder="Cari nama atau email..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    <button type="submit">Cari</button>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>
    <?php if ($users->num_rows > 0): 
        $no = 1;
        while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <a class="button" href="edit_user.php?id=<?= $u['id'] ?>">Edit</a>
                <a class="button danger" href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; else: ?>
        <tr><td colspan="5">Belum ada data user.</td></tr>
    <?php endif; ?>
</table>


<br>
<a href="admin_dashboard.php">⬅ Kembali ke Dashboard</a>

</body>
</html>
