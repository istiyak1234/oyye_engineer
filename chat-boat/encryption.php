<?php
define('ENCRYPTION_KEY', 'your-32-char-random-secret-key!!'); // Must be 32 chars, keep secret & private

function encryptMessage($plaintext) {
    $key = ENCRYPTION_KEY;
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext_raw);
}

function decryptMessage($ciphertext_base64) {
    $key = ENCRYPTION_KEY;
    $ciphertext = base64_decode($ciphertext_base64);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
    $iv = substr($ciphertext, 0, $ivlen);
    $ciphertext_raw = substr($ciphertext, $ivlen);
    return openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}
?>
