<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['temp_username'])) {
    header('Location: login.php');
    exit;
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['temp_username'];
    $password = $_POST['password'];
    $token = $_POST['token'];

    $query = "SELECT * FROM users WHERE username = '{$username}'";
    $res = $db->query($query);
    $user = $res->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        $decryptedPassword = decryptAES($user['password'], $user['iv']);
        if ($decryptedPassword === $password) {
            $twofaStmt = $db->prepare("SELECT twofa_enabled FROM twofa WHERE username = :u");
            $twofaStmt->bindValue(':u', $user['username'], SQLITE3_TEXT);
            $twofaRes = $twofaStmt->execute();
            $twofaData = $twofaRes->fetchArray(SQLITE3_ASSOC);

            if ($twofaData && $twofaData['twofa_enabled']) {
                $stmt = $db->prepare("SELECT * FROM tokens WHERE token = :t");
                $stmt->bindValue(':t', $token, SQLITE3_TEXT);
                $res = $stmt->execute();
                $tokenRow = $res->fetchArray(SQLITE3_ASSOC);

                if ($tokenRow) {
                    $stmt = $db->prepare("DELETE FROM tokens WHERE token = :t");
                    $stmt->bindValue(':t', $token, SQLITE3_TEXT);
                    $stmt->execute();

                    $_SESSION['username'] = $user['username'];
                    header('Location: index.php');
                    exit;
                } else {
                    $msg = 'Invalid token.';
                }
            }
        } else {
            $msg = 'Invalid username or password.';
        }
    } else {
        $msg = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verify</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Verify Login</h1>
  <?php if ($msg): ?>
  <p style="color:red"><?=htmlspecialchars($msg)?></p>
  <?php endif; ?>
</div>
</body>
</html>