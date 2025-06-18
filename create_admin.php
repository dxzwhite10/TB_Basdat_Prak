<?php
include 'config/db.php';

$name = "Ramdani";
$email = "ramdani@hotel.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

// Cek dulu apakah email sudah ada
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Admin dengan email ini sudah ada.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    if ($stmt->execute()) {
        echo "Admin berhasil dibuat.";
    } else {
        echo "Gagal: " . $stmt->error;
    }
}
?>
