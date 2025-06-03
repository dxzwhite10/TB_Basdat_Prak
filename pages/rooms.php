<?php
include('../config/db.php');
$result = $conn->query("SELECT * FROM rooms WHERE status='available'");

while ($row = $result->fetch_assoc()) {
    echo "Kamar: " . $row['room_number'] . " | Tipe: " . $row['type'] . " | Harga: " . $row['price'];
    echo "<a href='booking.php?room_id=" . $row['id'] . "'>Pesan</a><br><br>";
}
?>