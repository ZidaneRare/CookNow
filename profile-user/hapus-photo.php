<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
    exit;
}

$email_from_session = $_SESSION['user_email'];

// Query untuk mengupdate foto menjadi 'profile.png'
$update_query = "UPDATE user SET photo = 'assets/img/profile-img.png' WHERE email = '$email_from_session'";
$update_result = mysqli_query($conn, $update_query);

if ($update_result) {
    // Update session untuk menampilkan foto default
    $_SESSION['user_photo'] = 'assets/img/profile-img.png';
    echo "Foto berhasil dihapus.";
} else {
    echo "Gagal menghapus foto.";
}
?>