<?php
error_reporting(0);
session_start();
include '../include/library.php';

if($_SESSION['username'] !== 'admin'){
  exit("<script>location.href='../home.php';</script>");
}

if(token_chk($_SESSION['username'], $_SESSION['token']) === 0){
  exit('token error');
}

if(isset($_POST['title'], $_POST['contents'], $_POST['category'])){
  
  if($_POST['category'] !== 'cooking' && $_POST['category'] !== 'song'){
    exit("<script>location.href='./admin_write.php'</script>");
  }
  if(strlen($_POST['title']) < 1 || strlen($_POST['contents']) < 1){
    exit("<script>alert('title or contents is too short');location.href='./admin_write.php'</script>");
  }

  $title = sqli_block(xss_block($_POST['title']));
  $contents = sqli_block(bbcode(xss_block($_POST['contents'])));
  $category = $_POST['category'];
  $write_query = "INSERT INTO `{$category}` VALUES (NULL, '{$title}', '{$contents}')";  
  
  mysqli_query($conn, $write_query) or die('write error');
  exit("<script>alert('write ok');location.href='./admin_write.php'</script>");
}
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="../assets/style.css" type="text/css">
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
            <a class="nav-link" href="../home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./admin_write.php">Write</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../shop/shop.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./add_member.php">New Member</a>
          </li>
        </ul>
        <a class="btn navbar-btn btn-secondary mx-3" href="../logout.php">logout</a>
      </div>
    </div>
  </nav>
  <div class="bg-faded py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="pi-item">Write</h1><br><br>
          <form method="POST">
            <input type="text" name="title" placeholder="Title" class="form-control" />
            <hr>
            <textarea type="text" name="contents" placeholder="Contents" class="form-control"></textarea>
            <br><br>
            <select name="category" class="btn dropdown-toggle btn-light">
              <div class="dropdown-menu">
              <option value="cooking" class="dropdown-item">Cooking</option>
              <option value="song" class="dropdown-item">Song</option>
              </div>
            </select> 
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="../assets/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="../assets/smooth-scroll.js"></script>
</body>
</html>