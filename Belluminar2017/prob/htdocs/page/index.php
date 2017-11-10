<?php
  error_reporting(0);
  session_start();
  require("config.php");

  if(!isset($_SESSION['username'])){
    exit("<script>location.href='../'</script>");
  }

  if(isset($_GET['leave'])){
    session_destroy();
    exit("<script>location.href='../'</script>");
  }

  $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

  $color = isset($_POST['color']) ? $_POST['color'] : 'red';
  $query = "SELECT * FROM `xslt` WHERE xsl='".$color."'";
  $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query)) or die('color not found.');

  $xml = new  DOMDocument;
  $xml->load("style.xml");

  $xsl = new DOMDocument;
  $xsl->load($assoc['path']);

  $proc = new XSLTProcessor;
  $proc->registerPHPFunctions();
  $proc->importStyleSheet($xsl);

  require("base.php");
?>
