<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cooknow";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi Gagal : " . mysqli_connect_error());
} else {
    $query = "SHOW TABLE STATUS LIKE 'recipes'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $auto_increment_resep = $row['Auto_increment'];
}
