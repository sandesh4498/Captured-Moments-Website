<?php
// test_db_connect.php

$servername = "localhost:3307";
$username = "root";
$password = ""; // Change as needed
$dbname = "project1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}
?>
