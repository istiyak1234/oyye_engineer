<?php
// ====== Telegram Bot Settings ======
$botToken = "7147444893:AAGBZaF1t3YtMokQGFRMSa4j-QH7C5RzmkM"; // 🔥 Put your bot token here
$chatId = "@Istiyak02_bot";     // 🔥 Put your Telegram user ID here

// ====== Create Your Notification Message ======
$visitorIP = $_SERVER['REMOTE_ADDR']; // Get visitor IP
$date = date('Y-m-d H:i:s'); // Current date and time

$message = "🚀 New Visitor Alert!\n\n"
         . "🕒 Time: $date\n"
         . "🌐 IP Address: $visitorIP";

// ====== Telegram API URL ======
$apiURL = "https://api.telegram.org/bot$botToken/sendMessage";

// ====== Send Notification ======
$data = [
    'chat_id' => $chatId,
    'text' => $message
];

// Send HTTP GET request to Telegram
file_get_contents($apiURL . '?' . http_build_query($data));
?>
