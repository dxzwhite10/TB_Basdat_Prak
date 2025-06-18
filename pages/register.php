<?php
session_start();
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($cek->num_rows > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'customer')";
        if ($conn->query($sql)) {
            header("Location: login_customer.php");
            exit();
        } else {
            $error = "Gagal registrasi: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrasi Pelanggan</title>
  <link href="https://fonts.googleapis.com/css2?family=Bad+Script&display=swap" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
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

    .center-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-grow: 1;
    }

    .register-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 380px;
      color: #333;
      text-align: center;
    }

    .register-box img.logo {
      height: 60px;
      margin-bottom: 15px;
    }

    .register-box h2 {
      color: rgb(252, 146, 25);
      margin-bottom: 25px;
    }

    .register-box input[type="text"],
    .register-box input[type="email"],
    .register-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background-color: rgb(252, 146, 25);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .register-box button:hover {
      background-color: rgb(230, 120, 10);
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }

    .back-button {
      position: absolute;
      top: 15px;
      left: 20px;
    }

    .back-button a {
      text-decoration: none;
      color: #fff;
      background: rgba(0,0,0,0.4);
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: bold;
    }

    .back-button a:hover {
      background: rgba(0,0,0,0.6);
    }

    .link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .link a {
      color: #007bff;
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }

    .footer {
      background-color: rgba(255,255,255,0.95);
      padding: 15px;
      text-align: center;
      font-size: 14px;
      box-shadow: 0 -1px 5px rgba(0,0,0,0.1);
    }

    .social-icons {
      margin-top: 10px;
    }

    .social-icons a {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
      display: inline-flex;
      align-items: center;
      font-family: Arial, sans-serif;
      font-size: 14px;
    }

    .social-icons img {
      height: 20px;
      margin-right: 6px;
      vertical-align: middle;
    }

    @media screen and (max-width: 480px) {
      .register-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="slideshow"></div>

  <div class="back-button">
    <a href="../index.php">⬅ Kembali</a>
  </div>

  <div class="center-container">
    <div class="register-box">
      <img src="../assets/logo_uniku.png" alt="Logo Hotel UNIKU" class="logo">
      <h2>Registrasi Pelanggan</h2>
      <?php if (isset($error)) echo "<div class='error'>".htmlspecialchars($error)."</div>"; ?>
      <form method="post">
        <label>Nama:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Daftar</button>
      </form>
      <div class="link">
        Sudah punya akun? <a href="login_customer.php">Login di sini</a>
      </div>
    </div>
  </div>

  <div class="footer">
    <div>Hubungi Kami: 0812-3456-7890 | Email: info@hoteluniku.com</div>
    <div class="social-icons">
      <a href="#"><img src="../assets/icons/insta.png" alt="Instagram"> hoteluniku_garut</a>
      <a href="#"><img src="../assets/icons/tiktok.png" alt="TikTok"> hotelunikugarut</a>
      <a href="#"><img src="../assets/icons/x.png" alt="X"> hoteluniku_garut</a>
    </div>
  </div>

</body>
</html>
