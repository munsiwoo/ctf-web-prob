<?php
error_reporting(0);
if(isset($_GET['source'])){
    show_source('index.php');
    exit;
}
if(!isset($_GET['jscode'])){
    header('Location: ./index.php?action=show&jscode=print()');
    exit;
}
$action = $_GET['action'] == 'run' ? 'run' : 'show'; 
$jscode = isset($_GET['jscode']) ? preg_replace("/([<>'\"\s])/", null, $_GET['jscode']) : 'print()';
if(strlen($jscode) > 7*7*7) $jscode = 'print()';
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.1/purify.min.js"></script>
    <title>Lucky7</title>
</head>
<body>
    <h2>Lucky7</h2>
    <small>
        If you can steal `flag` variable, please send a payload <a href="https://munsiwoo.kr/report.php">here.</a><br>
        <a href="?source">source</a>
    </small>
    <form>
        <select name="action">
            <option value="run" <?php echo $action == 'run' ? 'selected' : '' ?>>run</option>
            <option value="show" <?php echo $action == 'show' ? 'selected' : '' ?>>show</option>
        </select>
        <input name="jscode" placeholder="print()" maxlength="7" value="<?php echo $jscode ?>">
        <input type="submit" value="run">
    </form>
    <script>
        var flag = "hspace{fake-flag}";
        var action = "<?php echo $action ?>";
        var jscode  = DOMPurify.sanitize("<?php echo $jscode ?>");

        window.onerror = function(e){
            var error = document.createElement('small');
            error.textContent  = e.toString();
            document.body.appendChild(error);
        }
        window.onload = function(){
            eval = null;
            flag = null;
            var luckybox = document.createElement('iframe');
            luckybox.width = '600px';
            luckybox.height = '300px';
            luckybox.src = `./lucky.php?action=${action}&jscode=${jscode}`;
            document.body.appendChild(luckybox);
        }
    </script>
</body>
</html>
