


<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql303.infinityfree.com";
$user = "if0_38823587";
$pass = "Istiyak0209";
$db = "if0_38823587_istiyak_web";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
