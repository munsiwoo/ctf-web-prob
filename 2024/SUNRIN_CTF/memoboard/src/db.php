<?php
error_reporting(0);
$db = new SQLite3('/db/database.db');

$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT,
    iv TEXT
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    content TEXT,
    author TEXT,
    iv TEXT
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS twofa (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    twofa_enabled INTEGER DEFAULT 0,
    twofa_secret TEXT
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS tokens (
    token TEXT UNIQUE
);
");

function encryptAES($plain) {
    $key = 'dummydummydummydummydummydummydu';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($plain, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return [
        'ciphertext' => base64_encode($encrypted),
        'iv' => base64_encode($iv)
    ];
}
function decryptAES($ciphertext, $iv) {
    $key = 'dummydummydummydummydummydummydu';
    $decodedCiphertext = base64_decode($ciphertext);
    $decodedIV = base64_decode($iv);
    return openssl_decrypt($decodedCiphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $decodedIV);
}