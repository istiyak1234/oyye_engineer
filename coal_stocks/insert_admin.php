<?php
// insert_admin.php (run once to insert admin)
include 'conn.php';
$username = 'Commercial';
$password = password_hash('TRNcoal@2025', PASSWORD_DEFAULT); // Secure password hash

$stmt = $mysqli->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$stmt->close();

echo "Admin user created.";
?>
