<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Cek apakah ini untuk tracker atau profil
if (isset($_POST['action']) && $_POST['action'] == 'tracker') {
    // Proses data tracker
    $user_id = $_SESSION['user_id'];
    $berat_badan = mysqli_real_escape_string($koneksi, $_POST['berat_badan']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $query = "INSERT INTO weight_tracker (user_id, berat_badan, tanggal) 
              VALUES ('$user_id', '$berat_badan', '$tanggal')";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Data tracker berhasil disimpan!";
        header("Location: ../hasil.php?fitur=tracker");
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
        header("Location: ../hasil.php?fitur=tracker");
    }
} else {
    // Proses data profil
    $user_id = $_SESSION['user_id'];
    $umur = mysqli_real_escape_string($koneksi, $_POST['umur']);
    $tinggi_badan = mysqli_real_escape_string($koneksi, $_POST['tinggi_badan']);
    $berat_badan = mysqli_real_escape_string($koneksi, $_POST['berat_badan']);
    $tujuan_olahraga = mysqli_real_escape_string($koneksi, $_POST['tujuan_olahraga']);
    $tingkat_aktivitas = mysqli_real_escape_string($koneksi, $_POST['tingkat_aktivitas']);

    // Hitung BMI
    $tinggi_badan_m = $tinggi_badan / 100;
    $bmi = $berat_badan / ($tinggi_badan_m * $tinggi_badan_m);

    // Simpan data profil
    $query = "INSERT INTO user_profiles (user_id, umur, tinggi_badan, berat_badan, tujuan_olahraga, tingkat_aktivitas, bmi) 
              VALUES ('$user_id', '$umur', '$tinggi_badan', '$berat_badan', '$tujuan_olahraga', '$tingkat_aktivitas', '$bmi')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Profil berhasil disimpan!";
        header("Location: ../dashboard.php");
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
        header("Location: ../tambah.php");
    }
}

mysqli_close($koneksi);
?>