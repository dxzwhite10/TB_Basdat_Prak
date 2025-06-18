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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data User</title>
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

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 14px;
            background-color: #ff9800;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }

        button:hover {
            background-color: #fb8c00;
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

        a.button {
            padding: 6px 12px;
            background-color: #ff9800;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }

        a.button:hover {
            background-color: #f57c00;
        }

        a.danger {
            background-color: #d84315;
        }

        a.danger:hover {
            background-color: #bf360c;
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

<a href="admin_dashboard.php" class="back-link">⬅ Kembali ke Dashboard</a>

</body>
</html>