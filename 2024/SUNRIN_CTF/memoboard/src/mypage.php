<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$stmt = $db->prepare("SELECT * FROM twofa WHERE username=:u");
$stmt->bindValue(':u', $_SESSION['username'], SQLITE3_TEXT);
$res = $stmt->execute();
$user = $res->fetchArray(SQLITE3_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enable2fa']) && $user['twofa_enabled'] == 0) {
        $secret = generateRandomSecret();
        $upd = $db->prepare("UPDATE twofa SET twofa_enabled=1, twofa_secret=:s WHERE username=:u");
        $upd->bindValue(':s', $secret, SQLITE3_TEXT);
        $upd->bindValue(':u', $_SESSION['username'], SQLITE3_TEXT);
        $upd->execute();
        $msg = '2FA Enabled';
        $user['twofa_enabled'] = 1;
        $user['twofa_secret'] = $secret;
    }
}
function generateRandomSecret($length = 16) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    return implode('', array_map(fn() => $alphabet[random_int(0, strlen($alphabet) - 1)], range(1, $length)));
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>My Page</h1>
  <?php if($user['twofa_enabled'] == 0): ?>
  <form method="POST">
    <button name="enable2fa" value="1">Enable 2FA</button>
  </form>
  <?php else: ?>
  <p>
    2FA is Enabled.<br>
    Secret: <?=htmlspecialchars($user['twofa_secret'])?><br><br>
    Please install Google Authenticator and add this secret code to register.
  </p>
  <?php endif; ?>
  <p><a href="index.php">Go Main</a></p>
</div>
</body>
</html>