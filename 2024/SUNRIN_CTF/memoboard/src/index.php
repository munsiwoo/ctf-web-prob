<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Main</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .welcome-message {
      font-size: 1.2em;
      color: #555;
    }
    .feature-list {
      margin-top: 20px;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 5px;
    }
    .feature-list h2 {
      margin-bottom: 10px;
    }
    .feature-list ul {
      list-style-type: square;
      padding-left: 20px;
    }
  </style>
</head>
<body>
<div class="container">
  <?php if (!isset($_SESSION['username'])): ?>
  <h1>Memo board</h1>
  <p class="welcome-message">Welcome to our service! Please log in or register to enjoy all features.</p>
  <p>
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a>
  </p>
  <div class="feature-list">
    <h2>Quick Links:</h2>
    <ul>
      <li>Experience a secure memo board with 2FA.</li>
      <li>Enjoy a variety of features for free.</li>
      <li>Log out securely when you're done.</li>
    </ul>
  </div>
  <?php else: ?>
  <h1>Hello, <?=htmlspecialchars($_SESSION['username'])?></h1>
  <p class="welcome-message">Welcome back! Explore the features available to you.</p>
  <p>
    <a href="board.php">Board</a> |
    <a href="mypage.php">My Page</a> |
    <a href="logout.php">Logout</a>
  </p>
  <div class="feature-list">
    <h2>Quick Links:</h2>
    <ul>
      <li>Experience a secure memo board with 2FA.</li>
      <li>Enjoy a variety of features for free.</li>
      <li>Log out securely when you're done.</li>
    </ul>
  </div>
  <?php endif; ?>
</div>
</body>
</html>