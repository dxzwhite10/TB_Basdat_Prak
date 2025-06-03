<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role='admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Hotel UNIKU</title>
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

        .login-form-container {
            margin: auto;
            background: rgba(255,255,255,0.92);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .login-form-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .login-form-container button {
            padding: 10px 20px;
            background-color: rgb(252, 146, 25);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-form-container button:hover {
            background-color: #e68a00;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        @media screen and (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <div class="slideshow"></div>

    <div class="header">
        <div class="logo-title">
            <img src="../assets/logo_uniku.png" alt="Logo Hotel UNIKU">
            <h2>Login Admin - Hotel UNIKU</h2>
        </div>
    </div>

    <div class="login-form-container">
        <form method="post">
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    </div>

</body>
</html>
