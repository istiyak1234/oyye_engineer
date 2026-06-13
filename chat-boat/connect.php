<?php
// DB connection file: use this include in all scripts

$host = 'sql303.infinityfree.com';      // adjust if needed
$db   = 'if0_38823587_istiyak_web';        // your database name
$user = 'if0_38823587';         // replace with DB username
$pass = 'Istiyak0209';     // replace with DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Connection failed']);
    exit;
}
?>
