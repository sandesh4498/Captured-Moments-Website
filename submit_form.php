<?php
// submit_form.php

include 'db_connect.php'; // Include database connection

// Initialize variables
$name = $email = $message = "";
$name_err = $email_err = $message_err = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter a message.";
    } else {
        $message = trim($_POST["message"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO contact_table (name, email, message) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_name, $param_email, $param_message);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_message = $message;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to a thank you page or display a success message
                header("Location: contact.html");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>
