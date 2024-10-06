<?php
require 'db_connect.php'; // Connection to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if email already exists
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists!";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the data into the database
        $query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $name, $email, $hashedPassword);
        if ($query->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $query->error;
        }
    }

    $checkQuery->close();
    $query->close();
    $conn->close();
}
?>
