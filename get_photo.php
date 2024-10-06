<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT id, name, email, image_url FROM photos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$photo = $result->fetch_assoc();

if ($photo) {
    $photo['image_url'] = 'data:image/jpeg;base64,' . base64_encode($photo['image_url']);
}

echo json_encode($photo);
$stmt->close();
$conn->close();
?>
