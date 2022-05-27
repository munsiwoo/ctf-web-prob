<?php
error_reporting(0);
if(isset($_POST['url'])){
    if(preg_match("/^http:\/\/sunrin\.kiminfo\.kr:18625\/.*$/i", $_POST['url'])){
        $url = addslashes($_POST['url']);
        $db = new mysqli('127.0.0.1', 'admin', 'password', 'ezxss');
        $db->query("INSERT INTO urls VALUES (NULL, '{$url}', 0)");
        echo '<script>alert("Thank you!\nadmin will check it soon with flag!");</script>';
    }
    else {
        echo '<script>alert("Invalid URL format.");</script>';
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>send us your url, admin will check it soon :)</p>
    <form method="POST">
        <input type="text" name="url" placeholder="http://sunrin.kiminfo.kr:18625/index.php" style="width: 350px;">
        <input type="submit">
    </form>
</body>
</html>
