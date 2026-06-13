<?php
header('Content-Type: application/json');
session_start();
include 'connect.php';

$filename = "typing_status.json"; // temporary storage file (or better, use DB or cache)

// Accept POST: save typing status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['sender'], $data['room_id'], $data['typing'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data']);
        exit();
    }

    $status = json_decode(file_get_contents($filename), true) ?? [];
    $room = $data['room_id'];

    // Keep typing statuses by room: only one user typing reported here for simplicity
    if ($data['typing']) {
        $status[$room] = [
            'sender' => $data['sender'],
            'timestamp' => time()
        ];
    } else {
        // Remove typing if user stopped
        if (isset($status[$room]) && $status[$room]['sender'] === $data['sender']) {
            unset($status[$room]);
        }
    }

    file_put_contents($filename, json_encode($status));
    echo json_encode(['success' => true]);
    exit();
}

// Accept GET: send typing status if any for the room
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['room_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing room_id']);
        exit();
    }

    $status = json_decode(file_get_contents($filename), true) ?? [];
    $room = $_GET['room_id'];

    // Clear expired typing status (2 seconds timeout)
    if (isset($status[$room])) {
        if ((time() - $status[$room]['timestamp']) > 2) {
            unset($status[$room]);
            file_put_contents($filename, json_encode($status));
            echo json_encode([]);
            exit();
        }
        echo json_encode(['sender' => $status[$room]['sender'], 'typing' => true]);
    } else {
        echo json_encode([]);
    }
    exit();
}
?>
