<?php
error_reporting(0);
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="static/theme.css" type="text/css">
    <link rel="stylesheet" href="static/style.css" type="text/css">
    <link rel="shortcut icon" href="static/favicon.png" type="image/png"/>
    <title>Pig Security</title>
</head>

<body id="wrapper">
    <nav class="navbar navbar-expand-md navbar-dark" style="background-color: #cb7e7e; box-shadow: 0px 5px 10px 5px #d3a4a4;">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <b style="font-size: 20px; font-weight: 500;">🐷 Pig Security</b>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center justify-content-end" id="navbar2SupportedContent">
                <ul class="navbar-nav" style="font-size: 18px;">
                    <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <script>
        function login(form) {
            let data = {'id': form.id.value, 'pw': form.pw.value};
            $.post("login.php", data, (res) => {
                let result = JSON.parse(res);
                alert(result['message']);
            });
            return false;
        }
    </script>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div style="font-weight: 500; margin-left: 45%;">Can you read the admin's note?</div>
                <img src="static/pig.png" style="width:90px; height: auto; margin-left: 60%;">

                <form class="login-form" onsubmit="login(this); return false;">
                    <input type="text" name="id" placeholder="ID" autofocus="" required=""><br>
                    <input type="password" name="pw" placeholder="PW" required=""><br>

                    <hr>
                    <input type="submit" class="btn btn-lg btn-danger" value="Log in">
                </form>
                <br>
                <div style="font-weight: 500;">
                    Test account : guest / guest<br>
                    Source : <a href="source.zip">source.zip</a>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <script src="static/jquery-3.4.1.min.js"></script>
    <script src="static/bootstrap.min.js"></script>	
</body>
</html>