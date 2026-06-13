<?php
$servername = "sql303.infinityfree.com";
$username = "if0_38823587";
$password = "Istiyak0209";
$dbname = "if0_38823587_istiyak_web";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Optional: you can echo a success message during testing
// echo "Connected successfully";
?>
