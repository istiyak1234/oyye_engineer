<?php
header('Content-Type: application/json');
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['room_id']) || trim($data['room_id']) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing room_id']);
    exit();
}

$room_id = trim(strip_tags($data['room_id']));
$pdo->prepare("DELETE FROM messages WHERE room_id = ?")->execute([$room_id]);

echo json_encode(['cleared' => true]);
?>
