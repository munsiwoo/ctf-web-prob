<?php
error_reporting(0);
if(!isset($_GET['lang'])){
	header('Location: ./index.php?lang=en');
	exit;
}
$lang = preg_replace('/(\"|\\\\|\/)/', '\\\\$1', $_GET['lang']);
$lang = preg_replace('/\s/', '', $lang);
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<h1>Sunrin XSS Sanitizer 1.0</h1>
	<small>Can you bypass my sanitizer? Really?<br>
	If you find a vulnerability, please report it <a href="report.php">here.</a></small>

	<form>
		<input type="hidden" name="lang" value="en">
		<input name="comment" placeholder="comment" autofocus>
		<input type="submit">
	</form>
	<p></p>

	<script>
		window.onload = function(){
			let comment = new URL(location.href).searchParams.get("comment");
			document.querySelector('p').innerHTML = comment;
		};
	</script>

	<script>
		const APPINFO = {
			name: "Sunrin XSS Sanitizer",
			version: 1.0,
			lang: "<?php echo $lang ?>"
		};
		function sanitizeParams(){ // Super duper sanitizer :)
			let values = new URL(location.href).searchParams.values();
			for(value of values)
			if(value.indexOf('<') !== -1 || value.indexOf('>') !== -1)
			document.body.innerHTML = "<h1>XSS detected!</h1>";
		}
		sanitizeParams();
	</script>



</body>
</html>

