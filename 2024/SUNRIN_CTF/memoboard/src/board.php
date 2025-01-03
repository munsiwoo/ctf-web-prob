<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("INSERT INTO posts (title, content, author, iv) VALUES (:t, :c, :a, :iv)");
    $stmt->bindValue(':t', $_POST['title'], SQLITE3_TEXT);

    $encryptedData = encryptAES($_POST['content']);
    $stmt->bindValue(':c', $encryptedData['ciphertext'], SQLITE3_TEXT);
    $stmt->bindValue(':iv', $encryptedData['iv'], SQLITE3_TEXT);
    $stmt->bindValue(':a', $_SESSION['username'], SQLITE3_TEXT);

    $stmt->execute();
}
$stmt = $db->prepare("SELECT title, content, iv FROM posts WHERE author = :a ORDER BY id DESC");
$stmt->bindValue(':a', $_SESSION['username'], SQLITE3_TEXT);
$rows = $stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Board</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Board</h1>
  <form method="post">
    <label>Title: <input type="text" name="title"></label><br/>
    <label>Content:<br/><textarea name="content"></textarea></label><br/>
    <button type="submit">Post</button>
  </form>
  <hr/>
  <?php while ($post = $rows->fetchArray(SQLITE3_ASSOC)): ?>
    <h3><?=htmlspecialchars($post['title'])?></h3>
    <?php 
    $decrypted = decryptAES($post['content'], $post['iv']); 
    ?>
    <p><?=nl2br(htmlspecialchars($decrypted))?></p>
    <hr/>
  <?php endwhile; ?>
  <p><a href="index.php">Go Main</a></p>
</div>
</body>
</html>