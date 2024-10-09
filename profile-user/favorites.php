<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['id_user'];
$recipe_id = $_POST['recipe_id'];
$action = $_POST['action'];

if ($action === 'add') {
    $query = "INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)";
} elseif ($action === 'remove') {
    $query = "DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $recipe_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
}

$stmt->close();
$conn->close();
?>