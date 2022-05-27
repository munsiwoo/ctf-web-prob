<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.5/purify.min.js"></script>
    <script type="text/javascript" src="config.js"></script>
</head>
<body>
    <small>If you find a vulnerability, please report it <a href="report.php">here.</a></small>
	<form>
		<input name="name" placeholder="name" maxlength="5">
		<input name="msg" placeholder="msg" maxlength="20">
		<input type="submit" value="write">
	</form>
    <div id="msgbox"></div>
    <script>
        var url = new URL(window.location.href);

        if(url.searchParams.has('name'))
        	name = DOMPurify.sanitize(url.searchParams.get('name'));
		if(url.searchParams.has('msg'))
			msg = DOMPurify.sanitize(url.searchParams.get('msg'));

        name = name.substr(0, 5);
        document.getElementsByName('name')[0].value = name;
        document.getElementsByName('msg')[0].value = msg;

        document.getElementById('msgbox').innerHTML = `<p><b>${name} said "${msg}"</b></p>`;
    </script>
</body>
</html>