<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['temp_username'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $otp = $_POST['otp'];

    $stmt = $db->prepare("SELECT * FROM twofa WHERE username = :u");
    $stmt->bindValue(':u', $_SESSION['temp_username'], SQLITE3_TEXT);
    $res = $stmt->execute();
    $user = $res->fetchArray(SQLITE3_ASSOC);

    if ($user && verifyOTP($user['twofa_secret'], $otp)) {
        $token = bin2hex(random_bytes(16));
        $insertToken = $db->prepare("INSERT INTO tokens (token) VALUES (:t)");
        $insertToken->bindValue(':t', $token, SQLITE3_TEXT);
        $insertToken->execute();

        echo json_encode(['status' => true, 'token' => $token]);
        exit;
    } else {
        echo json_encode(['status' => false, 'msg' => 'Invalid OTP. Please try again.']);
        exit;
    }
}
function base32Decode($secret) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $secret = strtoupper($secret);
    $paddingPos = strpos($secret, '=');
    if ($paddingPos !== false) {
        $secret = substr($secret, 0, $paddingPos);
    }
    $bits = '';
    for ($i = 0; $i < strlen($secret); $i++) {
        $val = strpos($alphabet, $secret[$i]);
        $bits .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
    }
    $decoded = '';
    for ($i = 0; $i < strlen($bits); $i += 8) {
        $chunk = substr($bits, $i, 8);
        if (strlen($chunk) < 8) break;
        $decoded .= chr(bindec($chunk));
    }
    return $decoded;
}
function verifyOTP($secret, $otp) {
    $decodedKey = base32Decode($secret);
    $timeSlice = intdiv(time(), 30);
    return array_reduce(range(-1, 1), function($carry, $offset) use ($decodedKey, $timeSlice, $otp) {
        return $carry || generateTOTP($decodedKey, $timeSlice + $offset) === $otp;
    }, false);
}
function generateTOTP($key, $timeSlice) {
    $timeBytes = pack('N*', 0) . pack('N*', $timeSlice);
    $hmac = hash_hmac('sha1', $timeBytes, $key, true);
    $offset = ord($hmac[19]) & 0xf;
    $binary = (
        (ord($hmac[$offset]) & 0x7f) << 24 |
        (ord($hmac[$offset + 1]) & 0xff) << 16 |
        (ord($hmac[$offset + 2]) & 0xff) << 8 |
        (ord($hmac[$offset + 3]) & 0xff)
    );
    $otp = $binary % 1000000;
    return str_pad($otp, 6, '0', STR_PAD_LEFT);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>2FA</title>
  <link rel="stylesheet" href="style.css">
  <script>
    async function sendOTP() {
        const otp = document.getElementById('otp').value;
        const response = await fetch('2fa.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ otp })
        });
        const result = await response.json();
        if (result.status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'verify.php';

            const username = '<?=str_replace(["'", "\\"], "", htmlspecialchars($_SESSION['temp_username']))?>';
            const password = '<?=str_replace(["'", "\\"], "", htmlspecialchars($_SESSION['temp_password']))?>';
            const token = result.token;

            const usernameInput = document.createElement('input');
            usernameInput.type = 'hidden';
            usernameInput.name = 'username';
            usernameInput.value = username;
            form.appendChild(usernameInput);

            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;
            form.appendChild(passwordInput);

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'token';
            tokenInput.value = token;
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
        } else {
            alert(result.msg);
        }
    }
  </script>
</head>
<body>
<div class="container">
  <h1>Two-Factor Auth</h1>
  <p>Enter the OTP from your<br>Google authenticator app:</p>
  <input type="text" id="otp" placeholder="Enter OTP">
  <button onclick="sendOTP()">Verify OTP</button>
</div>
</body>
</html>