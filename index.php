<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SehatinAja.com - Solusi Kesehatan Anda</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fef7e5, #f8ecff, #e9f6ff);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* NAVIGATION */
        nav {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            padding: 15px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,0.25);
            border-bottom: 1px solid rgba(255,255,255,0.5);
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #5ac8fa, #9d4edd, #ff88dc);
            -webkit-background-clip: text; /* Background gradient dengan background-clip: text 
    TIDAK BEKERJA pada elemen <i> Font Awesome karena:
    1. Font Awesome menggunakan sistem font icons, bukan elemen dengan background
    2. Background-clip: text hanya bekerja pada elemen yang memiliki konten text biasa
    3. Ikon Font Awesome dirender sebagai karakter font khusus, bukan text biasa */
            color: transparent;
        }

        .menu a {
            margin: 0 15px;
            font-size: 1.1rem;
            color: #444;
            text-decoration: none;
            font-weight: 500;
        }

        .menu a:hover { color: #9d4edd; }

        .btn-nav {
            padding: 10px 22px;
            border-radius: 25px;
            background: linear-gradient(45deg,#5ac8fa,#4a8fe7);
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }
        .btn-nav:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        }

        /* HERO */
        .hero {
            margin-top: 120px;
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(45deg, #5ac8fa, #9d4edd, #ff88dc);
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            margin: 20px auto;
            max-width: 700px;
            line-height: 1.7;
            color: #555;
        }

        .btn-main {
            padding: 14px 35px;
            margin-top: 15px;
            display: inline-block;
            border-radius: 30px;
            background: linear-gradient(45deg,#ff88dc,#e86ed0);
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            transition: .3s;
        }
        .btn-main:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
        }

        /* FEATURES */
        .features {
            padding: 60px;
            text-align: center;
        }

        .features h2 {
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #333;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px 25px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: .4s;
        }

        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 18px 30px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 2.2rem;
            margin-bottom: 15px;
            background: linear-gradient(45deg,#5ac8fa,#ff88dc);
            -webkit-background-clip: text; 
            color: transparent;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 40px;
            color: #666;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo"><i class="fas fa-heartbeat"></i> SehatinAja</div>

        <div class="menu">
            <a href="#">Beranda</a>
            <a href="#">Fitur</a>
            <a href="#">Tentang</a>
            <a href="#">Kontak</a>
        </div>

        <a href="login.php" class="btn-nav">Login</a>
    </nav>

    <section class="hero">
        <h1>Wujudkan Hidup Sehatmu Sekarang!</h1>
        <p>Website untuk membantu mengatur jadwal olahraga mingguan, tracking berat badan, rekomendasi nutrisi, dan AI Assistant yang siap menemani perjalanan sehatmu.</p>
        <a href="register.php" class="btn-main"><i class="fas fa-user-plus"></i> Mulai Sekarang</a>
    </section>

    <section class="features">
        <h2>Fitur Unggulan</h2>

        <div class="feature-grid">
            <div class="feature-card">
                <i class="far fa-calendar-check feature-icon"></i>
                <h3>Buat Jadwal Olahraga</h3>
                <p>Rancang jadwal mingguan dengan tampilan kalender interaktif.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3>Progress Tracking</h3>
                <p>Perubahan berat, kalori, dan statistik dalam dashboard grafik.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-apple-alt feature-icon"></i>
                <h3>Saran Nutrisi</h3>
                <p>Rekomendasi menu sehat sesuai target tubuh.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-robot feature-icon"></i>
                <h3>AI Assistant</h3>
                <p>Teman ngobrol tentang pola hidup sehat 24/7.</p>
            </div>
        </div>
    </section>

    <footer>
        &copy; 2025 SehatinAja.com — Semua Hak Cipta Dilindungi
    </footer>
</body>
</html>
