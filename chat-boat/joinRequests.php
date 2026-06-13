<?php
// joinRequests.php
header('Content-Type: application/json');

if (!isset($_GET['room_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing room_id']);
    exit;
}

$room_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['room_id']);

$dir = __DIR__ . "/data";
$requestsFile = "$dir/{$room_id}_join_requests.json";
$ownersFile = "$dir/room_owners.json";

if (!file_exists($ownersFile)) {
    echo json_encode(['requests' => []]);
    exit;
}

$owners = json_decode(file_get_contents($ownersFile), true);
if (!is_array($owners) || !isset($owners[$room_id])) {
    // Room or owner not found
    echo json_encode(['requests' => []]);
    exit;
}

// Optional: you may want to check caller is owner by some auth

$joinRequests = [];
if (file_exists($requestsFile)) {
    $joinRequestsTmp = json_decode(file_get_contents($requestsFile), true);
    if (is_array($joinRequestsTmp)) $joinRequests = $joinRequestsTmp;
}

// Filter only pending requests and exclude owner
$pending = [];
foreach ($joinRequests as $user => $info) {
    if ($info['status'] === 'pending' && $user !== $owners[$room_id]) {
        $pending[] = [
            'username' => $user,
            'timestamp' => $info['timestamp'],
        ];
    }
}

echo json_encode(['requests' => $pending]);
