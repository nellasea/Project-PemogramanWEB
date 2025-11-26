<?php
session_start();
include '../config/koneksi.php';

if ($_POST['action'] == 'register') {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek apakah email sudah terdaftar
    $check_query = "SELECT id FROM users WHERE email = '$email'";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header("Location: ../register.php");
        exit();
    }
    
    // Insert user baru
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: ../login.php");
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
        header("Location: ../register.php");
    }
} elseif ($_POST['action'] == 'login') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            // Cek apakah user sudah mengisi data profil
            $profile_query = "SELECT * FROM user_profiles WHERE user_id = " . $user['id'];
            $profile_result = mysqli_query($koneksi, $profile_query);
            
            if (mysqli_num_rows($profile_result) > 0) {
                header("Location: ../dashboard.php");
            } else {
                header("Location: ../tambah.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['error'] = "Email tidak terdaftar!";
        header("Location: ../login.php");
    }
}

mysqli_close($koneksi);
?>