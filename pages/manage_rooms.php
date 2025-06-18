<?php
require_once("../config/db.php");

// Tambah kamar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $sql = "INSERT INTO rooms (room_number, type, price) VALUES ('$room_number', '$type', $price)";
    $conn->query($sql);
    header("Location: manage_rooms.php");
    exit();
}

// Hapus kamar
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM rooms WHERE id = $id");
    header("Location: manage_rooms.php");
    exit();
}

// Ambil data kamar jika sedang edit
$edit_mode = false;
$edit_data = [];
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM rooms WHERE id = $id");
    $edit_data = $result->fetch_assoc();
}

// Simpan hasil edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_room'])) {
    $id = intval($_POST['id']);
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $conn->query("UPDATE rooms SET room_number = '$room_number', type = '$type', price = $price WHERE id = $id");
    header("Location: manage_rooms.php");
    exit();
}

// Ambil semua data kamar
$rooms = $conn->query("SELECT * FROM rooms");
?>

<h2>Kelola Kamar</h2>

<?php if ($edit_mode): ?>
    <h3>Edit Kamar</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        Nomor Kamar: <input type="text" name="room_number" value="<?= $edit_data['room_number'] ?>" required><br>
        Tipe: <input type="text" name="type" value="<?= $edit_data['type'] ?>" required><br>
        Harga: <input type="number" name="price" value="<?= $edit_data['price'] ?>" required><br>
        <button type="submit" name="update_room">Simpan Perubahan</button>
        <a href="manage_rooms.php">Batal</a>
    </form>
<?php else: ?>
    <h3>Tambah Kamar</h3>
    <form method="post">
        Nomor Kamar: <input type="text" name="room_number" required><br>
        Tipe: <input type="text" name="type" required><br>
        Harga: <input type="number" name="price" required><br>
        <button type="submit" name="add_room">Tambah</button>
    </form>
<?php endif; ?>

<h3>Daftar Kamar</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Nomor</th>
        <th>Tipe</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php while ($r = $rooms->fetch_assoc()): ?>
        <tr>
            <td><?= $r['room_number'] ?></td>
            <td><?= $r['type'] ?></td>
            <td><?= $r['price'] ?></td>
            <td><?= $r['status'] ?></td>
            <td>
                <a href="?edit=<?= $r['id'] ?>">Edit</a> |
                <a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Yakin ingin hapus kamar ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="admin_dashboard.php">⬅ Kembali</a>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Kamar - Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff3e0;
      margin: 0;
      padding: 20px;
    }

    h2, h3 {
      color: #e65100;
    }

    form {
      background: #ffffff;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      max-width: 400px;
    }

    label {
      color: #e65100;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 15px;
      border: 1px solid #ffa726;
      border-radius: 5px;
      box-sizing: border-box;
    }

    button {
      padding: 10px 16px;
      background: #fb8c00;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
    }

    button:hover {
      background: #ef6c00;
    }

    a {
      text-decoration: none;
      color: #e65100;
      margin-left: 10px;
    }

    a:hover {
      text-decoration: underline;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff8e1;
      border-radius: 8px;
      overflow: hidden;
    }

    table th, table td {
      border: 1px solid #ffd54f;
      padding: 10px;
      text-align: center;
    }

    table th {
      background-color: #ffb300;
      color: white;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: #ef6c00;
    }
  </style>
</head>
<body>

