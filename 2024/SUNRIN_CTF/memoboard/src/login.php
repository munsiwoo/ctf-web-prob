<?php
session_start();
require_once 'db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (preg_match('/admin|_/i', $username)) {
        $msg = 'Invalid username.';
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :u");
        $stmt->bindValue(':u', $username, SQLITE3_TEXT);
        $res = $stmt->execute();
        $user = $res->fetchArray(SQLITE3_ASSOC);

        if ($user) {
            $decryptedPassword = decryptAES($user['password'], $user['iv']);
            if ($decryptedPassword === $password) {
                $twofaStmt = $db->prepare("SELECT twofa_enabled FROM twofa WHERE username = :u");
                $twofaStmt->bindValue(':u', $username, SQLITE3_TEXT);
                $twofaRes = $twofaStmt->execute();
                $twofaData = $twofaRes->fetchArray(SQLITE3_ASSOC);

                if ($twofaData && $twofaData['twofa_enabled']) {
                    $_SESSION['temp_username'] = $username;
                    $_SESSION['temp_password'] = $password;
                    header('Location: 2fa.php');
                    exit;
                } else {
                    $_SESSION['username'] = $username;
                    header('Location: index.php');
                    exit;
                }
            } else {
                $msg = 'Invalid username or password.';
            }
        } else {
            $msg = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Login</h1>
  <?php if ($msg): ?>
  <p style="color:red"><?=htmlspecialchars($msg)?></p>
  <?php endif; ?>
  <form method="POST" style="display: flex; flex-direction: column; align-items: center;">
    <label>Username: <input type="text" name="username"></label><br/>
    <label>Password: <input type="password" name="password"></label><br/>
    <div style="display: flex; gap: 10px;">
      <button type="submit" style="flex: 1;">Login</button>
      <button type="button" onclick="location.href='index.php'" style="flex: 1;">Go Home</button>
    </div>
  </form>
</div>
</body>
</html>