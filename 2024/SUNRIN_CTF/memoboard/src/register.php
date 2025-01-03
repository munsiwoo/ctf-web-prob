<?php
session_start();
require_once 'db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $checkStmt = $db->prepare("SELECT 1 FROM users WHERE username = :u");
    $checkStmt->bindValue(':u', $username, SQLITE3_TEXT);
    $result = $checkStmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $msg = 'Username already exists!';
    } else {
        $passwordData = encryptAES($password);
        $stmt = $db->prepare("INSERT INTO users (username, password, iv) VALUES (:u, :p, :iv)");
        $stmt->bindValue(':u', $username, SQLITE3_TEXT);
        $stmt->bindValue(':p', $passwordData['ciphertext'], SQLITE3_TEXT);
        $stmt->bindValue(':iv', $passwordData['iv'], SQLITE3_TEXT);

        $twofaStmt = $db->prepare("INSERT INTO twofa (username) VALUES (:u)");
        $twofaStmt->bindValue(':u', $username, SQLITE3_TEXT);
        try {
            $stmt->execute();
            $twofaStmt->execute();
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $msg = 'An error occurred during registration.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Register</h1>
  <?php if ($msg): ?>
  <p style="color:red"><?=htmlspecialchars($msg)?></p>
  <?php endif; ?>
  <form method="POST" style="display: flex; flex-direction: column; align-items: center;">
    <label>Username: <input type="text" name="username"></label><br/>
    <label>Password: <input type="password" name="password"></label><br/>
    <div style="display: flex; gap: 10px;">
      <button type="submit" style="flex: 1;">Register</button>
      <button type="button" onclick="location.href='index.php'" style="flex: 1;">Go Home</button>
    </div>
  </form>
</div>
</body>
</html>