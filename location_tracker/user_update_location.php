<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require 'conn.php';

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    !isset($data['device_id']) ||
    !isset($data['name']) ||
    !isset($data['latitude']) ||
    !isset($data['longitude'])
) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input. Required: device_id, name, latitude, longitude"]);
    exit;
}

$device_id = $conn->real_escape_string($data['device_id']);
$name      = $conn->real_escape_string($data['name']);
$latitude  = floatval($data['latitude']);
$longitude = floatval($data['longitude']);
$timestamp = date("Y-m-d H:i:s");

// Optional: Add/update users table (if it exists)
$conn->query("INSERT INTO users_table (device_id, name) 
              VALUES ('$device_id', '$name') 
              ON DUPLICATE KEY UPDATE name = '$name'");

// Insert new location entry
$insert = $conn->query("INSERT INTO user_locations (device_id, name, latitude, longitude, timestamp) 
                        VALUES ('$device_id', '$name', '$latitude', '$longitude', '$timestamp')");

if ($insert) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert location: " . $conn->error]);
}
?>
