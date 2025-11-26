<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - SehatinAja.com</title>
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
            text-align: center;
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

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-icon {
            font-size: 2.5rem;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 5px 15px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #ffffff, #e0c3fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        /* Main Content */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        /* Profile Form Styles */
        .profile-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .profile-card {
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

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90,200,250,0.1), rgba(255,136,220,0.1));
            z-index: -1;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .profile-header h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .profile-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 1rem;
            color: rgba(255,255,255,0.9);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
        }

        .form-group input::placeholder,
        .form-group select option:first-child {
            color: rgba(255,255,255,0.6);
        }

        .form-group select option {
            background: #badeefff;
            color: #001f3f;
        }

        /* Input with icons */
        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
            font-size: 1.1rem;
        }

        /* BMI Preview */
        .bmi-preview {
            background: rgba(255,255,255,0.08);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .bmi-preview h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: rgba(255,255,255,0.9);
        }

        .bmi-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .bmi-category {
            font-size: 1.1rem;
            font-weight: 600;
            opacity: 0.9;
        }

        /* Button Styles */
        .btn {
            padding: 16px 35px;
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
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #7acdf3ff, #4a8fe7);
            color: #001f3f;
            box-shadow: 0 5px 15px rgba(90,200,250,0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #ff88dc, #e86ed0);
            color: #3a003f;
            box-shadow: 0 5px 15px rgba(255,136,220,0.4);
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255,255,255,0.2);
            z-index: 1;
        }

        .progress-bar {
            position: absolute;
            top: 15px;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #5ac8fa, #ff88dc);
            z-index: 2;
            transition: width 0.3s ease;
            width: 100%;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
        }

        .step-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            color: #001f3f;
            box-shadow: 0 3px 10px rgba(90,200,250,0.4);
        }

        .step-label {
            font-size: 0.85rem;
            opacity: 0.7;
            text-align: center;
        }

        .step.active .step-label {
            opacity: 1;
            font-weight: 600;
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

        .profile-card {
            animation: fadeIn 1s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }
            
            .profile-card {
                padding: 30px 25px;
            }
            
            .profile-header h2 {
                font-size: 1.8rem;
            }
            
            .progress-steps {
                margin-bottom: 30px;
            }
            
            .step-label {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 95%;
            }
            
            header h1 {
                font-size: 1.8rem;
            }
            
            .profile-card {
                padding: 25px 20px;
            }
            
            .profile-header h2 {
                font-size: 1.6rem;
            }
            
            .bmi-preview {
                padding: 15px;
            }
            
            .bmi-value {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <i class="fas fa-heartbeat logo-icon"></i>
                <h1>SehatinAja.com</h1>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <h2>Lengkapi Data Profil Anda</h2>
                    <p>Data ini akan digunakan untuk memberikan rekomendasi olahraga dan nutrisi yang sesuai dengan kebutuhan Anda.</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="progress-bar"></div>
                    <div class="step active">
                        <div class="step-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-label">Data Diri</div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <div class="step-label">Olahraga</div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="step-label">Nutrisi</div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="step-label">Dashboard</div>
                    </div>
                </div>
                
                <form action="logic/create_data.php" method="POST" id="profileForm">
                    <div class="form-group">
                        <label for="umur">
                            <i class="fas fa-birthday-cake"></i> Umur
                        </label>
                        <div class="input-with-icon">
                            <input type="number" id="umur" name="umur" min="17" max="100" placeholder="Masukkan umur Anda" required>
                            <span class="input-icon"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tinggi_badan">
                            <i class="fas fa-arrows-alt-v"></i> Tinggi Badan
                        </label>
                        <div class="input-with-icon">
                            <input type="number" id="tinggi_badan" name="tinggi_badan" min="100" max="250" placeholder="Masukkan tinggi badan" required>
                            <span class="input-icon"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="berat_badan">
                            <i class="fas fa-weight"></i> Berat Badan
                        </label>
                        <div class="input-with-icon">
                            <input type="number" id="berat_badan" name="berat_badan" min="30" max="200" step="0.1" placeholder="Masukkan berat badan" required>
                            <span class="input-icon"></span>
                        </div>
                    </div>

                    <!-- BMI Preview -->
                    <div class="bmi-preview">
                        <h3>Indeks Massa Tubuh (BMI) Anda</h3>
                        <div class="bmi-value" id="bmiValue">-</div>
                        <div class="bmi-category" id="bmiCategory">Masukkan data untuk melihat BMI</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tujuan_olahraga">
                            <i class="fas fa-bullseye"></i> Tujuan Olahraga
                        </label>
                        <select id="tujuan_olahraga" name="tujuan_olahraga" required>
                            <option value="">Pilih Tujuan Olahraga Anda</option>
                            <option value="menurunkan_berat_badan">Menurunkan Berat Badan</option>
                            <option value="meningkatkan_massa_otot">Meningkatkan Massa Otot</option>
                            <option value="menjaga_kesehatan">Menjaga Kesehatan</option>
                            <option value="meningkatkan_kebugaran">Meningkatkan Kebugaran</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tingkat_aktivitas">
                            <i class="fas fa-running"></i> Tingkat Aktivitas Mingguan
                        </label>
                        <select id="tingkat_aktivitas" name="tingkat_aktivitas" required>
                            <option value="">Pilih Tingkat Aktivitas Anda</option>
                            <option value="pemula">Pemula (1-2 kali/minggu)</option>
                            <option value="menengah">Menengah (3-4 kali/minggu)</option>
                            <option value="aktif">Aktif (5-6 kali/minggu)</option>
                            <option value="sangat_aktif">Sangat Aktif (setiap hari)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan dan Lanjutkan ke Dashboard
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 SehatinAja.com. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
        // Calculate BMI in real-time
        function calculateBMI() {
            const tinggi = document.getElementById('tinggi_badan').value;
            const berat = document.getElementById('berat_badan').value;
            const bmiValue = document.getElementById('bmiValue');
            const bmiCategory = document.getElementById('bmiCategory');
            
            if (tinggi && berat) {
                const tinggiMeter = tinggi / 100;
                const bmi = (berat / (tinggiMeter * tinggiMeter)).toFixed(1);
                bmiValue.textContent = bmi;
                
                if (bmi < 18.5) {
                    bmiCategory.textContent = 'Kurus';
                    bmiCategory.style.color = '#ffa502';
                } else if (bmi >= 18.5 && bmi < 25) {
                    bmiCategory.textContent = 'Normal (Ideal)';
                    bmiCategory.style.color = '#2ed573';
                } else if (bmi >= 25 && bmi < 30) {
                    bmiCategory.textContent = 'Gemuk';
                    bmiCategory.style.color = '#ffa502';
                } else {
                    bmiCategory.textContent = 'Obesitas';
                    bmiCategory.style.color = '#ff4757';
                }
            } else {
                bmiValue.textContent = '-';
                bmiCategory.textContent = 'Masukkan data untuk melihat BMI';
                bmiCategory.style.color = 'rgba(255,255,255,0.9)';
            }
        }

        // Add event listeners for BMI calculation
        document.getElementById('tinggi_badan').addEventListener('input', calculateBMI);
        document.getElementById('berat_badan').addEventListener('input', calculateBMI);

        // Form validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const umur = document.getElementById('umur').value;
            const tinggi = document.getElementById('tinggi_badan').value;
            const berat = document.getElementById('berat_badan').value;
            const tujuan = document.getElementById('tujuan_olahraga').value;
            const aktivitas = document.getElementById('tingkat_aktivitas').value;
            
            if (!umur || !tinggi || !berat || !tujuan || !aktivitas) {
                e.preventDefault();
                alert('Harap lengkapi semua data sebelum melanjutkan!');
                return;
            }
            
            if (umur < 10 || umur > 100) {
                e.preventDefault();
                alert('Umur harus antara 10-100 tahun!');
                return;
            }
            
            if (tinggi < 100 || tinggi > 250) {
                e.preventDefault();
                alert('Tinggi badan harus antara 100-250 cm!');
                return;
            }
            
            if (berat < 30 || berat > 200) {
                e.preventDefault();
                alert('Berat badan harus antara 30-200 kg!');
                return;
            }
        });

        // Add input validation for real-time feedback
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                const value = parseFloat(this.value);
                const min = parseFloat(this.min);
                const max = parseFloat(this.max);
                
                if (value < min || value > max) {
                    this.style.borderColor = '#ff4757';
                    this.style.boxShadow = '0 0 0 3px rgba(255,71,87,0.2)';
                } else {
                    this.style.borderColor = 'rgba(255,255,255,0.3)';
                    this.style.boxShadow = 'none';
                }
            });
        });
    </script>
</body>
</html>