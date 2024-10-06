<?php
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = json_decode(file_get_contents('php://input'), true)['id'];

$stmt = $conn->prepare("DELETE FROM photos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['success' => true]);
?>
