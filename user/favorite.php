<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['id_user'];
$recipe_id = $_POST['recipe_id'];
$action = $_POST['action'];

if ($action === 'add') {
    $query = "INSERT INTO favorites (user_id, recipe_id) VALUES ('$user_id', '$recipe_id')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe added to favorites']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add recipe to favorites']);
    }
} elseif ($action === 'remove') {
    $query = "DELETE FROM favorites WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe removed from favorites']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove recipe from favorites']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
