<?php
require 'conn.php';

$sql = "SELECT u.name, l.latitude, l.longitude, l.location_name, l.updated_at
        FROM user_locations l
        JOIN users_table u ON u.id = l.user_id
        ORDER BY l.updated_at DESC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
