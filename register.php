<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SehatinAja.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #5ac8fa, #9d4edd, #ff88dc);
            background-size: 300% 300%;
            animation: gradientMove 12s ease infinite;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding-top: 30px;
        }

        /* Header Styles */
        header {
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: -1;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }

        .logo-icon {
            font-size: 2rem;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));
        }

        header h1 {
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 5px 15px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #ffffff, #e0c3fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        /* Tombol Kembali ke Beranda */
        .back-btn {
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
            cursor: pointer;
            background: linear-gradient(45deg, #ff88dc, #e86ed0);
            color: #3a003f;
            box-shadow: 0 5px 15px rgba(255,136,220,0.4);
            flex-shrink: 0;
        }

        .back-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255,136,220,0.6);
        }

        .back-btn:hover::before {
            opacity: 1;
        }

        /* Main Content */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        /* Auth Form Styles */
        .auth-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255,255,255,0.1);
            padding: 40px 35px;
            border-radius: 25px;
            backdrop-filter: blur(15px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90,200,250,0.1), rgba(255,136,220,0.1));
            z-index: -1;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .auth-header h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .auth-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 50px 15px 20px; /* Padding kanan 50px untuk icon */
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            height: 50px; /* Fixed height */
            line-height: 20px;
        }

        .form-group input:focus {
            outline: none;
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
        }

        .form-group input::placeholder {
            color: rgba(255,255,255,0.6);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 38px; /* POSISI FIXED - sesuaikan dengan tinggi input */
            transform: none; /* Hapus transform karena pakai fixed position */
            background: none;
            border: none;
            color: rgba(255,255,255,0.7);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .strength-bar {
            height: 5px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background: #47ff87ff;
            width: 33%;
        }

        .strength-medium {
            background: #ffa502;
            width: 66%;
        }

        .strength-strong {
            background: #2ed573;
            width: 100%;
        }

        .btn {
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #5ac8fa, #4a8fe7);
            color: #001f3f;
            box-shadow: 0 5px 15px rgba(90,200,250,0.4);
        }

        .auth-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .auth-footer p {
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .auth-footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border-bottom: 1px solid transparent;
        }

        .auth-footer a:hover {
            border-bottom: 1px solid #ffffff;
        }

        /* Terms Checkbox */
        .terms-group {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
        }

        .terms-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .terms-group label {
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .terms-group a {
            color: #5ac8fa;
            text-decoration: none;
            font-weight: 600;
        }

        .terms-group a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        }

        footer p {
            opacity: 0.8;
            font-size: 1rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-card {
            animation: fadeIn 1s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            header h1 {
                font-size: 1.8rem;
            }
            
            .auth-card {
                padding: 30px 25px;
            }
            
            .auth-header h2 {
                font-size: 1.8rem;
            }
            
            .back-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 95%;
            }
            
            header h1 {
                font-size: 1.6rem;
            }
            
            .auth-card {
                padding: 25px 20px;
            }
            
            .auth-header h2 {
                font-size: 1.6rem;
            }
            
            .terms-group {
                padding: 12px;
            }
            
            .terms-group label {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-heartbeat logo-icon"></i>
                    <h1>SehatinAja.com</h1>
                </div>
                <a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Daftar Akun Baru</h2>
                    <p>Bergabunglah dengan kami dan mulai perjalanan sehat Anda</p>
                </div>
                
                <form action="logic/auth.php" method="POST" id="registerForm">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap Anda" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan alamat email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required minlength="6">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                        <div class="password-strength" id="passwordStrength">
                            Kekuatan password: <span id="strengthText">-</span>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthBar"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Konfirmasi Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Masukkan ulang password" required>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="far fa-eye"></i>
                        </button>
                        <div class="password-strength" id="confirmMessage"></div>
                    </div>
                    
                    <div class="terms-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Saya menyetujui <a href="#">Syarat dan Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> SehatinAja.com
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </button>
                </form>
                
                <div class="auth-footer">
                    <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 SehatinAja.com. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
        // Toggle password visibility dengan improvement
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        this.setAttribute('aria-label', 'Sembunyikan password');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        this.setAttribute('aria-label', 'Tampilkan password');
    }
    
    // Fokus kembali ke input setelah toggle
    passwordInput.focus();
});

// Toggle confirm password visibility dengan improvement
document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmInput = document.getElementById('confirmPassword');
    const icon = this.querySelector('i');
    
    if (confirmInput.type === 'password') {
        confirmInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        this.setAttribute('aria-label', 'Sembunyikan konfirmasi password');
    } else {
        confirmInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        this.setAttribute('aria-label', 'Tampilkan konfirmasi password');
    }
    
    // Fokus kembali ke input setelah toggle
    confirmInput.focus();
});

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = 'Lemah';
            
            if (password.length >= 6) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/\d/)) strength += 1;
            if (password.match(/[^a-zA-Z\d]/)) strength += 1;
            
            // Reset classes
            strengthBar.className = 'strength-fill';
            
            if (password.length === 0) {
                strengthText.textContent = '-';
                strengthBar.style.width = '0%';
            } else if (strength <= 1) {
                strengthText.textContent = 'Lemah';
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 2) {
                strengthText.textContent = 'Sedang';
                strengthBar.classList.add('strength-medium');
            } else {
                strengthText.textContent = 'Kuat';
                strengthBar.classList.add('strength-strong');
            }
        });

        // Password confirmation check
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const message = document.getElementById('confirmMessage');
            
            if (confirmPassword.length === 0) {
                message.textContent = '';
            } else if (password === confirmPassword) {
                message.textContent = '✓ Password cocok';
                message.style.color = '#2ed573';
            } else {
                message.textContent = '✗ Password tidak cocok';
                message.style.color = '#ff4757';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat dan Ketentuan!');
                return;
            }
        });
    </script>
</body>
</html>