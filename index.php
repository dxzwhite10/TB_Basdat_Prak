<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservasi Hotel UNIKU</title>
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
            0%   { background-image: url('assets/bg1.jpg'); }
            33%  { background-image: url('assets/bg2.jpg'); }
            66%  { background-image: url('assets/bgg3.jpg'); }
            100% { background-image: url('assets/bg1.jpg'); }
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

        .nav-links a {
            text-decoration: none;
            color:rgb(252, 146, 25);
            font-weight: bold;
            margin-left: 20px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background:rgb(252, 146, 25);
            color: white;
        }

        .main-content {
            margin: auto;
            background: rgba(255,255,255,0.92);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 600px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .main-content p {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            background-color: rgba(255,255,255,0.95);
            box-shadow: 0 -1px 5px rgba(0,0,0,0.1);
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .social-icons a {
            text-decoration: none;
            display: flex;
            align-items: center;
            color: #333;
            font-weight: bold;
            gap: 6px;
        }

        .social-icons img {
            height: 20px;
            vertical-align: middle;
        }

        @media screen and (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                margin-top: 10px;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .main-content {
                width: 90%;
                padding: 20px;
            }

            .social-icons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <div class="slideshow"></div>

    <div class="header">
        <div class="logo-title">
            <img src="assets/logo_uniku.png" alt="Logo Hotel UNIKU">
            <h2>Reservasi Hotel UNIKU</h2>
        </div>
        <div class="nav-links">
            <a href="pages/login_admin.php">Login Admin</a>
            <a href="pages/login_customer.php">Login Pelanggan</a>
            <a href="pages/register.php">Registrasi</a>
        </div>
    </div>

    <div class="main-content">
        <p>
            Selamat datang di reservasi online Hotel UNIKU.<br>
            Hotel kami menawarkan kenyamanan dan layanan terbaik untuk semua tamu kami.
            Terletak di lokasi strategis dengan fasilitas modern, suasana tenang, dan pelayanan ramah,
            kami siap menjadi pilihan utama Anda untuk menginap di kota Garut ini.
        </p>
    </div>

    <div class="footer">
        <div>Hubungi Kami: 0855-2447-0273 | Email: info@hoteluniku.com</div>
        <div class="social-icons">
            <a href="#"><img src="assets/icons/insta.png" alt="Instagram"> hoteluniku_garut</a>
            <a href="#"><img src="assets/icons/x.png" alt="X"> hoteluniku_garut</a>
            <a href="#"><img src="assets/icons/tiktok.png" alt="Tiktok"> hotelunikugarut</a>
        </div>
    </div>

</body>
</html>
