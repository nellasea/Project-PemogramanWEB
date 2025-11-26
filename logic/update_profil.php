<?php
session_start();
include "../config/koneksi.php";

$user_id   = $_SESSION['user_id'];
$umur      = mysqli_real_escape_string($koneksi, $_POST['umur']);
$tinggi    = mysqli_real_escape_string($koneksi, $_POST['tinggi_badan']);
$berat     = mysqli_real_escape_string($koneksi, $_POST['berat_badan']);
$tujuan    = mysqli_real_escape_string($koneksi, $_POST['tujuan_olahraga']);
$aktivitas = mysqli_real_escape_string($koneksi, $_POST['tingkat_aktivitas']);

$bmi = $berat / (($tinggi/100) * ($tinggi/100));

$update = "UPDATE user_profiles SET 
    umur='$umur',
    tinggi_badan='$tinggi',
    berat_badan='$berat',
    tujuan_olahraga='$tujuan',
    tingkat_aktivitas='$aktivitas',
    bmi='$bmi'
    WHERE user_id='$user_id'";

if (mysqli_query($koneksi, $update)) {
    header("Location: ../dashboard.php?status=updated");
} else {
    echo "Gagal update profil: " . mysqli_error($koneksi);
}
?>
