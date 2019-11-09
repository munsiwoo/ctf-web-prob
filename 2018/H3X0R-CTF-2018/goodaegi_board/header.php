<?php
  error_reporting(0);
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="static/css/style.css" type="text/css">
  <title>goodaegi board</title>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar3SupportedContent" aria-controls="navbar3SupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse text-center justify-content-center" id="navbar3SupportedContent">
        <ul class="navbar-nav">
          <?php
          if(isset($_SESSION['user'])) :
          ?>
          <li class="nav-item"><a class="nav-link text-white" href="?p=home.html"><font size="5">Home</font></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?p=board.html"><font size="5">Board</font></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?p=logout.html"><font size="5">Logout</font></a></li>
          <?php
          else :
          ?>
          <li class="nav-item"><a class="nav-link text-white" href="?p=home.html"><font size="5">Home</font></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?p=login.html"><font size="5">Login</font></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?p=join.html"><font size="5">Join</font></a></li>
          <?php
          endif;
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="text-white bg-dark">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center align-self-center my-4">
          <h4 class="display-4">Goodaegi board</h4>
          <p class="lead">
          <?php
            if(isset($_SESSION['user'])) :
              echo 'Hello, '.$_SESSION['user']['nickname'].'('.$_SESSION['user']['username'].')';
            else :
              echo 'goodaegi board is awesome';
            endif;
          ?>
          </p>
        </div>
      </div>
    </div>
  </div>