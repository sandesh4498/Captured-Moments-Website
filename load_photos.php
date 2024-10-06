<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = "SELECT id, name, email, image_url FROM photos";
$result = $conn->query($sql);

$photos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['image_url'] = 'data:image/jpeg;base64,' . base64_encode($row['image_url']);
        $photos[] = $row;
    }
}

echo json_encode($photos);
$conn->close();
?>
