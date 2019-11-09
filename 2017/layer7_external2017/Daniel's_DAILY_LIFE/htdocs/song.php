<?php
error_reporting(0);
session_start();
include './include/library.php';
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
            <a class="nav-link" href="cooking.php">Cooking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="song.php">Song</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contacts.php">Contacts</a>
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
  <div class="jumbotron jumbotron-fluid">
    <div class="container">
      <br>
      <h1 class="display-6">Song</h1>
      <p class="lead">daily life of Daniel</p>
    </div>
  </div>
  <div class="py-5  section">
    <div class="container">
      <div class="row">
      <?php
        $query = mysqli_query($conn, "SELECT * FROM `song` ORDER BY idx DESC");
        while($assoc = mysqli_fetch_assoc($query)){
          echo '<div class="col-md-3">
                  <a href="./read.php?category=song&idx='.$assoc['idx'].'"><h2 class="text-sm-center text-primary">'.$assoc['title'].'</h2>
                  <p class="my-1">'.substr(view_category($assoc['contents']), 0, 50).'</p></a>
                </div>';
        }
      ?>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="./assets/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="./assets/smooth-scroll.js"></script>
</body>
</html>