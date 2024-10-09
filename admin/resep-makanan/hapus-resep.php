<?php
include "../../config/koneksi.php";

session_start();

if(!isset($_SESSION['admin_name'])){
    header('location: ../../');
    }
  

if (isset($_SESSION['msg'])) {
    echo "<script>alert('" . $_SESSION['msg'] . "');</script>";
    unset($_SESSION['msg']); // Hapus pesan setelah ditampilkan
}

$id = $_GET['id'];

// Hapus terlebih dahulu dari tabel recipe_nut
$delete_nutrition = mysqli_query($conn, "DELETE FROM recipe_nut WHERE recipe_id='$id'");

if ($delete_nutrition) {
    // Kemudian hapus dari tabel recipes
    $delete_recipe = mysqli_query($conn, "DELETE FROM recipes WHERE recipe_id='$id'");

    if ($delete_recipe) {
        header('location:resep.php');
    } else {
        echo "Maaf, proses menghapus data tidak berhasil";
    }
} else {
    echo "Maaf, proses menghapus data tidak berhasil";
}
