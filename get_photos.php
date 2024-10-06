<?php
header('Content-Type: application/json');

include "db_connect.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, image_url FROM photos";
$result = $conn->query($sql);

$photos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming image_url is a BLOB, fetch and encode as base64
        $row['image_url'] = 'data:image/jpeg;base64,' . base64_encode($row['image_url']);
        $photos[] = $row;
    }
}

echo json_encode($photos);

$conn->close();
?>
