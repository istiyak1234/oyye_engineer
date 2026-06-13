<?php
header('Content-Type: application/json');
include 'connect.php';
include 'encryption.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['sender'], $data['message'], $data['room_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit();
}

$sender = trim(strip_tags($data['sender']));
$room_id = trim(strip_tags($data['room_id']));
$message = trim($data['message']);

if ($sender === '' || $message === '' || $room_id === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty sender, message or room_id']);
    exit();
}

$encryptedMessage = encryptMessage($message);

$stmt = $pdo->prepare("INSERT INTO messages (sender, room_id, message) VALUES (?, ?, ?)");
if ($stmt->execute([$sender, $room_id, $encryptedMessage])) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Insert failed']);
}
?>
