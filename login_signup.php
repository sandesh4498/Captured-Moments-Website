<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';
session_start();

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup'])) {
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);
        $confirm_password = sanitize_input($_POST['confirm_password']);

        // Check if passwords match
        if ($password !== $confirm_password) {
            echo json_encode(['error' => 'Passwords do not match!']);
            exit();
        }

        // Hash the password before saving it to the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['error' => 'Email already exists!']);
            exit();
        } else {
            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                echo json_encode(['success' => 'Signup successful!']);
            } else {
                echo json_encode(['error' => 'Signup failed!']);
            }
            $stmt->close();
        }
        $query->close();
    }

    if (isset($_POST['login'])) {
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Retrieve the user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo json_encode(['success' => 'Login successful!']);
        } else {
            echo json_encode(['error' => 'Invalid email or password!']);
        }

        $stmt->close();
    }
}
$conn->close();
exit(); // Ensure no additional output is sent
?>
