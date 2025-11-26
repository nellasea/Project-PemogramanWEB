<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($id > 0 && ($_GET['type'] ?? '') === 'tracker' && ($_GET['confirm'] ?? '') === '1') {
    // Hapus data dari database
    mysqli_query($koneksi, "DELETE FROM weight_tracker WHERE id = '$id' AND user_id = '$user_id'");
    $_SESSION['success'] = "Data berhasil dihapus!";
}

header("Location: ../hasil.php?fitur=tracker");
exit();
?>