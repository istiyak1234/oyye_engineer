<?php
// approveRequest.php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['room_id'], $input['username'], $input['approve'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$room_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $input['room_id']);
$usernameToChange = trim($input['username']);
$approve = filter_var($input['approve'], FILTER_VALIDATE_BOOLEAN);

if ($usernameToChange === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid username']);
    exit;
}

$dir = __DIR__ . "/data";
$requestsFile = "$dir/{$room_id}_join_requests.json";
$ownersFile = "$dir/room_owners.json";

if (!file_exists($ownersFile)) {
    http_response_code(400);
    echo json_encode(['error' => 'Room owner file missing']);
    exit;
}

$owners = json_decode(file_get_contents($ownersFile), true);
if (!is_array($owners) || !isset($owners[$room_id])) {
    http_response_code(400);
    echo json_encode(['error' => 'Room or owner not found']);
    exit;
}

// Optional: Validate that caller is the owner (authentication needed)

// Load join requests
if (!file_exists($requestsFile)) {
    http_response_code(400);
    echo json_encode(['error' => 'No join requests found']);
    exit;
}

$joinRequests = json_decode(file_get_contents($requestsFile), true);
if (!is_array($joinRequests)) {
    http_response_code(400);
    echo json_encode(['error' => 'Corrupted join requests data']);
    exit;
}

// Update status if request exists and pending
if (!isset($joinRequests[$usernameToChange]) || $joinRequests[$usernameToChange]['status'] !== 'pending') {
    http_response_code(400);
    echo json_encode(['error' => 'Join request not found or already handled']);
    exit;
}

$joinRequests[$usernameToChange]['status'] = $approve ? 'approved' : 'rejected';
$joinRequests[$usernameToChange]['timestamp'] = time();

file_put_contents($requestsFile, json_encode($joinRequests));

echo json_encode(['success' => true, 'approved' => $approve]);
