<?php
error_reporting(0);
session_start();
include_once './include/library.php';
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="./assets/style.css" type="text/css">
  <meta name="description" content="daily life of Daniel">
  <meta name="keywords" content="daily life of Daniel">
  <title>Daniel Lee</title>
</head>
<body class="text-center">
  <nav class="navbar navbar-inverse navbar-expand-md navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="home.php">daily life of Daniel</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav ">
          <li class="nav-item">
            <a class="nav-link" href="#cooking">Cooking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#song">Song</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contacts">Contacts</a>
          </li>
          <?php
          if(isset($_SESSION['username'])){
            echo '<li class="nav-item"><a class="nav-link" href="./shop/shop.php">Shop</a></li>';
          	if($_SESSION['username'] === 'admin'){
          		echo '<li class="nav-item"><a class="nav-link" href="./admin/admin_home.php">Admin Page</a></li>';
  	        }
            echo '<li class="nav-item"><a class="nav-link"><font color=white>Hello, '.xss_block($_SESSION['username']).'</font></a></li>';
            echo '<a class="btn navbar-btn btn-secondary mx-3" href="logout.php">logout</a>';
          }
          else {
            echo '<a class="btn navbar-btn btn-secondary mx-3" href="login.php">login</a>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="cover d-flex align-items-center pt-1 bg-primary" id="home">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-md-left align-self-center my-5">
          <h1 class="text-white display-3" align="center">daily life of Daniel</h1>
          <h4 class="text-white" align="center">Hello, my name is Daniel Lee. I like cooking and singing.</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="section py-5 text-md-center" id="cooking">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-primary">Cooking board</h2>
          <p class="lead">Homemade, simple dishes.</p>
          <a href="cooking.php" class="btn btn-outline-primary btn-lg">Cooking board</a>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="section py-5 text-md-center" id="song">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-primary">Song board</h2>
          <p class="lead">Sing and Song</p>
          <a href="song.php" class="btn btn-outline-primary btn-lg">Song board</a>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="section py-5 text-md-center" id="contacts">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-primary">Contacts</h2>
          <p class="lead">You can say to admin</p>
          <a href="contacts.php" class="btn btn-outline-primary btn-lg">Contacts</a>
        </div>
      </div>
    </div>
  </div>
  <div class="py-5 bg-info" id="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-12 my-3">
          <p>© Daniel. All Rights Reserved</p>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="./assets/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="./assets/smooth-scroll.js"></script>
</body>
</html>