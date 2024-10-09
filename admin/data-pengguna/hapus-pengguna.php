<?php
include "../../config/koneksi.php";

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location: ../../index.php');
}


if (isset($_SESSION['msg'])) {
    echo "<script>alert('" . $_SESSION['msg'] . "');</script>";
    unset($_SESSION['msg']); // Hapus pesan setelah ditampilkan
}

$id = $_GET['id'];

$delete_user = mysqli_query($conn, "DELETE FROM user WHERE user_id = '$id'");
if ($delete_user) {
    header('location:pengguna.php');
} else {
    echo "Maaf, proses menghapus data tidak berhasil";
}
