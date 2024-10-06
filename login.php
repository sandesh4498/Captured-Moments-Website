<?php
require 'db_connect.php'; // Connection to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if the email exists
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, redirect to crud.html
            header("Location: crud.html");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }

    $query->close();
    $conn->close();
}
?>
