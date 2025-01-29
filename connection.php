<?php
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'emanager';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to safely escape user inputs
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, trim($input));
}
?>