<?php
header('Content-Type: application/json');
include 'connect.php';
include 'encryption.php';

if (!isset($_GET['room_id']) || trim($_GET['room_id']) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing room_id']);
    exit();
}

$room_id = trim(strip_tags($_GET['room_id']));

$stmt = $pdo->prepare("SELECT sender, message, created_at FROM messages WHERE room_id = ? ORDER BY created_at ASC");
$stmt->execute([$room_id]);
$messages = $stmt->fetchAll();

foreach ($messages as &$msg) {
    $msg['message'] = decryptMessage($msg['message']);
}

echo json_encode($messages);
?>
