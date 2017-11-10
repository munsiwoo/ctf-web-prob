<?php
error_reporting(0);
session_start();

if(!isset($_SESSION['username'])){
  exit("<script>location.href='../'</script>");
}

?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="./assets/style.css" type="text/css">
  <script src="./assets/script.js"></script>
</head>
<body class="text-center">
<nav class="navbar navbar-inverse navbar-expand-md navbar-dark bg-primary">
    <div class="container">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <form action="index.php" method="post" name="colors">
          <ul class="navbar-nav ">
            <li class="nav-item">
              <a class="nav-link" href="javascript:load('red')">red</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:load('orange')">orange</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:load('green')">green</a>
            </li>
            <a class="btn navbar-btn btn-secondary mx-3" href="?leave">leave</a>
          </ul>
          <input type="hidden" name="color">
        </form>
      </div>
    </div>
  </nav>
  <div class="cover d-flex align-items-center pt-1 bg-primary">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-md-left align-self-center my-5">
          <h1 class="text-white display-5" align="center"><?php echo strtoupper($assoc['xsl']); ?></h1>
        </div>
      </div>
    </div>
  </div>
  <div class="section py-5 text-md-center">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-primary"><?php echo strtoupper($assoc['xsl']); ?> COLOR</h2>
          <br>
          <?php
            echo $proc->transformToXML($xml);
          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="./assets/bootstrap.min.js"></script>
</body>
</html>