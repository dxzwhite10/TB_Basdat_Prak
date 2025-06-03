<?php
require_once("../config/db.php");

// Tambah kamar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $sql = "INSERT INTO rooms (room_number, type, price) VALUES ('$room_number', '$type', $price)";
    $conn->query($sql);
    header("Location: manage_room.php");
    exit();
}

// Hapus kamar
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM rooms WHERE id = $id");
    header("Location: manage_room.php");
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
    header("Location: manage_room.php");
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
        <a href="manage_room.php">Batal</a>
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
