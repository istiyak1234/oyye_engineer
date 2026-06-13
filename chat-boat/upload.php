<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
// Allow CORS - Adjust the origin if you want to restrict domains
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Check if a file was uploaded
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

// Basic validation settings
$allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'text/plain',
    // Add more MIME types if you wish to allow other file types
];
$maxFileSize = 10 * 1024 * 1024; // 10 MB max file size limit

$file = $_FILES['file'];
$fileMimeType = mime_content_type($file['tmp_name']);
$fileSize = $file['size'];

// Validate file size
if ($fileSize > $maxFileSize) {
    echo json_encode(['error' => 'File exceeds max size of 10MB']);
    exit;
}

// Validate file type
if (!in_array($fileMimeType, $allowedMimeTypes)) {
    echo json_encode(['error' => 'File type not allowed']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['error' => 'Failed to create upload directory']);
        exit;
    }
}

// Sanitize and generate a unique filename
$originalName = basename($file['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$filename = uniqid('upload_', true) . '.' . $extension;
$targetFile = $uploadDir . $filename;

// Move the uploaded file to the uploads directory
if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    // Adjust the URL according to where your uploads directory is publicly accessible
    $url = '/uploads/' . $filename;
    echo json_encode(['url' => $url]);
} else {
    echo json_encode(['error' => 'Failed to move uploaded file']);
}
?>