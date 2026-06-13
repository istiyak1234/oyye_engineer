<?php
// joinRequestStatus.php
header('Content-Type: application/json');

if (!isset($_GET['room_id'], $_GET['username'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$room_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['room_id']);
$username = trim($_GET['username']);

if ($username === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid username']);
    exit;
}

$dir = __DIR__ . "/data";
$requestsFile = "$dir/{$room_id}_join_requests.json";

$response = ['approved' => false, 'rejected' => false, 'pending' => false];

if (!file_exists($requestsFile)) {
    // No requests found for room
    echo json_encode($response);
    exit;
}

$joinRequests = json_decode(file_get_contents($requestsFile), true);
if (!is_array($joinRequests) || !isset($joinRequests[$username])) {
    // User has not requested join or data corrupted
    echo json_encode($response);
    exit;
}

$status = $joinRequests[$username]['status'] ?? 'pending';

$response['approved'] = ($status === 'approved');
$response['rejected'] = ($status === 'rejected');
$response['pending'] = ($status === 'pending');

echo json_encode($response);
