<?php
// joinRequest.php
header('Content-Type: application/json');

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['room_id']) || !isset($input['username'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$room_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $input['room_id']);
$username = trim($input['username']);
if ($username === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid username']);
    exit;
}

$dir = __DIR__ . "/data";
if (!file_exists($dir)) mkdir($dir, 0777, true);

$requestsFile = "$dir/{$room_id}_join_requests.json";

// Load existing requests or initialize
$joinRequests = [];
if (file_exists($requestsFile)) {
    $joinRequests = json_decode(file_get_contents($requestsFile), true);
    if (!is_array($joinRequests)) $joinRequests = [];
}

// Load or create room owners file
$ownersFile = "$dir/room_owners.json";
$owners = [];
if (file_exists($ownersFile)) {
    $owners = json_decode(file_get_contents($ownersFile), true);
    if (!is_array($owners)) $owners = [];
}

// Determine if room owner exists
if (!isset($owners[$room_id])) {
    // No owner yet, assign current user as owner
    $owners[$room_id] = $username;
    file_put_contents($ownersFile, json_encode($owners));
    // Also mark join request as approved immediately for owner
    $joinRequests[$username] = ['status' => 'approved', 'timestamp' => time()];
    file_put_contents($requestsFile, json_encode($joinRequests));

    echo json_encode(['isOwner' => true]);
    exit;
}

// Check if this username is the owner
if ($owners[$room_id] === $username) {
    // Owner rejoining (or reconnecting)
    // Ensure approved
    $joinRequests[$username] = ['status' => 'approved', 'timestamp' => time()];
    file_put_contents($requestsFile, json_encode($joinRequests));

    echo json_encode(['isOwner' => true]);
    exit;
}

// For normal users:
// Check if user already has a pending or approved request
if (isset($joinRequests[$username])) {
    // Already requested, return isOwner false
    echo json_encode(['isOwner' => false]);
    exit;
}

// Add new join request with status 'pending'
$joinRequests[$username] = ['status' => 'pending', 'timestamp' => time()];
file_put_contents($requestsFile, json_encode($joinRequests));

echo json_encode(['isOwner' => false]);
