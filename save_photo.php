<?php
require_once 'db_connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_POST['photoId'] ?? null;
$name = $_POST['name'];
$email = $_POST['email'];
$image_blob = null;

// Check if the file was uploaded
if (isset($_FILES['photoFile']) && $_FILES['photoFile']['error'] === UPLOAD_ERR_OK) {
    $image_blob = file_get_contents($_FILES['photoFile']['tmp_name']);
} else {
    echo json_encode(['success' => false, 'message' => 'File upload error']);
    exit();
}

if ($id) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE photos SET name=?, email=?, image_url=? WHERE id=?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("sssi", $name, $email, $image_blob, $id);
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO photos (name, email, image_url) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $name, $email, $image_blob);
}

$result = $stmt->execute();
if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit();
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true]);
?>
