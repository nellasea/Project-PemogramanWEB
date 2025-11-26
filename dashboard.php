<?php
session_start();
include 'components/session_protected.php';
include 'config/koneksi.php';

// Ambil data profil user
$user_id = $_SESSION['user_id'];
$profile_query = "SELECT * FROM user_profiles WHERE user_id = $user_id";
$profile_result = mysqli_query($koneksi, $profile_query);

if ($profile_result && mysqli_num_rows($profile_result) > 0) {
    $profile = mysqli_fetch_assoc($profile_result);
} else {
    $profile = null;
}

// Ambil data tracker berat badan
$tracker_query = "SELECT * FROM weight_tracker WHERE user_id = $user_id ORDER BY tanggal DESC LIMIT 4";
$tracker_result = mysqli_query($koneksi, $tracker_query);
$weight_data = [];
if ($tracker_result) {
    while ($row = mysqli_fetch_assoc($tracker_result)) {
        $weight_data[] = $row;
    }
}

mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SehatinAja.com</title>
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .user-info span {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Button Styles */
        .btn {
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
            flex-shrink: 0;
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
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #5ac8fa, #4a8fe7);
            color: #001f3f;
            box-shadow: 0 5px 15px rgba(90,200,250,0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #ff88dc, #e86ed0);
            color: #3a003f;
            box-shadow: 0 5px 15px rgba(255,136,220,0.4);
        }

        /* Main Content */
        main {
            padding: 40px 0;
        }

        /* Dashboard Header */
        .dashboard-header {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90,200,250,0.1), rgba(255,136,220,0.1));
            z-index: -1;
        }

        .dashboard-header h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .profile-summary {
            background: rgba(255,255,255,0.08);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .profile-summary h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-summary h3 i {
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .profile-summary p {
            margin-bottom: 10px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .profile-summary a {
            color: #5ac8fa;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .profile-summary a:hover {
            text-decoration: underline;
        }

        /* Dashboard Features */
        .dashboard-features {
            margin-bottom: 40px;
        }

        .dashboard-features h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .feature-card {
            background: rgba(255,255,255,0.1);
            padding: 30px 25px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-align: center;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90,200,250,0.1), rgba(255,136,220,0.1));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            background: rgba(255,255,255,0.15);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .feature-icon {
            font-size: 1.5rem;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .feature-card p {
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .feature-card .btn {
            width: auto;
            margin: 0 auto;
            padding: 10px 25px;
        }

        /* Recent Tracker */
        .recent-tracker {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .recent-tracker::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90,200,250,0.1), rgba(255,136,220,0.1));
            z-index: -1;
        }

        .recent-tracker h3 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .recent-tracker h3 i {
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .tracker-table {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            overflow: hidden;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        th {
            background: rgba(255,255,255,0.1);
            font-weight: 600;
            color: rgba(255,255,255,0.9);
        }

        td {
            opacity: 0.9;
        }

        .positive {
            color: #2ed573;
            font-weight: 600;
        }

        .negative {
            color: #ff4757;
            font-weight: 600;
        }

        .neutral {
            color: rgba(255,255,255,0.6);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-header, .dashboard-features, .recent-tracker {
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
            
            .dashboard-header h2 {
                font-size: 2rem;
            }
            
            .dashboard-features h2 {
                font-size: 1.8rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .user-info {
                justify-content: center;
            }
            
            th, td {
                padding: 12px 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 95%;
            }
            
            header h1 {
                font-size: 1.6rem;
            }
            
            .dashboard-header, .recent-tracker {
                padding: 20px;
            }
            
            .dashboard-header h2 {
                font-size: 1.8rem;
            }
            
            .profile-summary {
                padding: 20px;
            }
            
            th, td {
                padding: 10px 12px;
                font-size: 0.9rem;
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
                <div class="user-info">
                    <span><i class="fas fa-user"></i> Halo, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></span>
                    <a href="logic/logout.php" class="btn btn-secondary" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="dashboard-header">
            <h2>Dashboard Kesehatan Anda</h2>
            <div class="profile-summary">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3><i class="fas fa-chart-line"></i> Ringkasan Profil</h3>
        <button class="btn btn-primary" onclick="openEditModal()">
            <i class="fas fa-edit"></i> Edit Profil
        </button>
    </div>

    <?php if ($profile): ?>
        <p><i class="fas fa-user"></i> Umur: <?= $profile['umur'] ?> tahun | Tinggi: <?= $profile['tinggi_badan'] ?> cm | Berat: <?= $profile['berat_badan'] ?> kg</p>
        <p><i class="fas fa-bullseye"></i> Tujuan: <?= ucfirst(str_replace('_',' ',$profile['tujuan_olahraga'])) ?> | Aktivitas: <?= ucfirst($profile['tingkat_aktivitas']) ?></p>
        <p><i class="fas fa-calculator"></i> BMI: <?= number_format($profile['bmi'],1) ?> 
            (<span style="color: 
                <?php 
                if ($profile['bmi'] < 18.5) echo '#ffa502';
                elseif ($profile['bmi'] < 25) echo '#2ed573';
                elseif ($profile['bmi'] < 30) echo '#ffa502';
                else echo '#ff4757';
                ?>">
                <?php 
                if ($profile['bmi'] < 18.5) echo "Underweight";
                elseif ($profile['bmi'] < 25) echo "Normal";
                elseif ($profile['bmi'] < 30) echo "Overweight";
                else echo "Obesity";
                ?>
            </span>)
        </p>
    <?php else: ?>
        <p><i class="fas fa-exclamation-triangle"></i> Profil belum lengkap. <a href="tambah.php">Lengkapi profil Anda</a></p>
    <?php endif; ?>
</div>

        </section>

        <section class="dashboard-features">
            <h2>Fitur Utama</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3><i class="far fa-calendar-alt feature-icon"></i> Jadwal Olahraga</h3>
                    <p>Atur dan lihat jadwal olahraga mingguan Anda</p>
                    <a href="hasil.php?fitur=jadwal" class="btn btn-primary">
                        <i class="fas fa-dumbbell"></i> Buka Jadwal
                    </a>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-chart-line feature-icon"></i> Tracker Kesehatan</h3>
                    <p>Pantau berat badan dan progress Anda</p>
                    <a href="hasil.php?fitur=tracker" class="btn btn-primary">
                        <i class="fas fa-weight"></i> Lihat Tracker
                    </a>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-utensils feature-icon"></i> Rekomendasi Nutrisi</h3>
                    <p>Dapatkan list nutrisi sesuai kebutuhan Anda</p>
                    <a href="hasil.php?fitur=nutrisi" class="btn btn-primary">
                        <i class="fas fa-apple-alt"></i> Lihat Nutrisi
                    </a>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-robot feature-icon"></i> AI Assistant</h3>
                    <p>Konsultasi dengan AI Assistant kami</p>
                    <a href="hasil.php?fitur=ai" class="btn btn-primary">
                        <i class="fas fa-comments"></i> Chat dengan AI
                    </a>
                </div>
            </div>
        </section>

        <section class="recent-tracker">
            <h3><i class="fas fa-history"></i> Progress Berat Badan Terakhir</h3>
            <?php if (!empty($weight_data)): ?>
                <div class="tracker-table">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="far fa-calendar"></i> Tanggal</th>
                                <th><i class="fas fa-weight"></i> Berat Badan</th>
                                <th><i class="fas fa-exchange-alt"></i> Perubahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($weight_data as $index => $data): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($data['tanggal'])); ?></td>
                                    <td><strong><?php echo $data['berat_badan']; ?> kg</strong></td>
                                    <td>
                                        <?php if ($index > 0): 
                                            $change = $weight_data[$index-1]['berat_badan'] - $data['berat_badan'];
                                            if ($change > 0) {
                                                echo "<span class='positive'><i class='fas fa-arrow-down'></i> " . number_format($change, 1) . " kg</span>";
                                            } elseif ($change < 0) {
                                                echo "<span class='negative'><i class='fas fa-arrow-up'></i> " . number_format(abs($change), 1) . " kg</span>";
                                            } else {
                                                echo "<span class='neutral'><i class='fas fa-minus'></i></span>";
                                            }
                                        else: ?>
                                            <span class="neutral"><i class="fas fa-minus"></i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p><i class="fas fa-info-circle"></i> Belum ada data tracker. <a href="hasil.php?fitur=tracker">Tambahkan data pertama Anda</a></p>
            <?php endif; ?>
        </section>
    </main>
<!-- ==================== MODAL EDIT PROFIL ==================== -->
<div id="editModal" class="modal-overlay" style="display:none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2><i class="fas fa-user-edit"></i> Edit Profil</h2>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="logic/update_profil.php" method="POST" class="profile-form">
                <div class="form-group">
                    <label for="umur"><i class="fas fa-birthday-cake"></i> Umur</label>
                    <input type="number" id="umur" name="umur" value="<?= $profile['umur'] ?>" min="1" max="120" required>
                </div>
                
                <div class="form-group">
                    <label for="tinggi_badan"><i class="fas fa-arrows-alt-v"></i> Tinggi Badan (cm)</label>
                    <input type="number" id="tinggi_badan" name="tinggi_badan" value="<?= $profile['tinggi_badan'] ?>" min="50" max="250" required>
                </div>
                
                <div class="form-group">
                    <label for="berat_badan"><i class="fas fa-weight"></i> Berat Badan (kg)</label>
                    <input type="number" id="berat_badan" name="berat_badan" value="<?= $profile['berat_badan'] ?>" min="20" max="300" step="0.1" required>
                </div>
                
                <div class="form-group">
                    <label for="tujuan_olahraga"><i class="fas fa-bullseye"></i> Tujuan Olahraga</label>
                    <select id="tujuan_olahraga" name="tujuan_olahraga" required>
                        <option value="Menurunkan Berat Badan" <?= $profile['tujuan_olahraga']=="Menurunkan Berat Badan"?"selected":"" ?>>Menurunkan Berat Badan</option>
                        <option value="Menambah Massa Otot" <?= $profile['tujuan_olahraga']=="Menambah Massa Otot"?"selected":"" ?>>Menambah Massa Otot</option>
                        <option value="Menjaga Kebugaran" <?= $profile['tujuan_olahraga']=="Menjaga Kebugaran"?"selected":"" ?>>Menjaga Kebugaran</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tingkat_aktivitas"><i class="fas fa-running"></i> Tingkat Aktivitas</label>
                    <select id="tingkat_aktivitas" name="tingkat_aktivitas" required>
                        <option value="Rendah" <?= $profile['tingkat_aktivitas']=="Rendah"?"selected":"" ?>>Rendah</option>
                        <option value="Sedang" <?= $profile['tingkat_aktivitas']=="Sedang"?"selected":"" ?>>Sedang</option>
                        <option value="Tinggi" <?= $profile['tingkat_aktivitas']=="Tinggi"?"selected":"" ?>>Tinggi</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }
    
    /* Modal Container */
    .modal-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        animation: slideUp 0.4s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Modal Header */
    .modal-header {
        background: linear-gradient(45deg, #5ac8fa, #9d4edd);
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }
    
    .modal-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    /* Modal Body */
    .modal-body {
        padding: 25px;
    }
    
    /* Form Styles */
    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-group label {
        font-weight: 500;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-group label i {
        color: #5ac8fa;
        width: 16px;
    }
    
    .form-group input,
    .form-group select {
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #5ac8fa;
        box-shadow: 0 0 0 3px rgba(90, 200, 250, 0.2);
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .form-actions .btn {
        flex: 1;
        justify-content: center;
        padding: 12px;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 576px) {
        .modal-container {
            width: 95%;
            margin: 10px;
        }
        
        .modal-header {
            padding: 15px 20px;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    function openEditModal() {
        document.getElementById("editModal").style.display = "flex";
    }
    
    function closeEditModal() {
        document.getElementById("editModal").style.display = "none";
    }
    
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEditModal();
                }
            });
        }
    });
</script>
</body>
</html>