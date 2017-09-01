<?php

// index.php
//
// Started: 29/08/2017

require_once("inc/lib.php");

header("Cache-Control: no-cache, no-store", true);

if(USER :: check_login())
{
	header("Location: /library.php", true, 301);
}

ob_start();
?>

<!DOCTYPE HTML>

<html lang="EN">
	<head>
		<title>My Library</title>
		
		<link type="text/css" rel="stylesheet" href="static/css/google.1.css">
		<link type="text/css" rel="stylesheet" href="static/css/google.2.css">
		<link type="text/css" rel="stylesheet" href="static/css/materialize.min.css">
		<!--<link type="text/css" rel="stylesheet" href="static/css/body.css">-->
		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
		<meta name="android-mobile-web-app-capable" content="yes" />
		
		<script type="text/javascript" src="static/js/jquery.min.js"></script>
		<script type="text/javascript" src="static/js/materialize.min.js"></script>
		
		<style>
			a:hover{text-decoration: underline; color: red;}
		
			.__div_header
			{
				font-size: 40px;
				color: yellow;
			}
			
			.__yellow
			{
				color: yellow;
			}
			
			.__green 
			{
				color: green;
			}
			
			.__titles
			{
				font-size: 25px;
			}

		</style>
	</head>

	<body>
		<header>
			<div class="center purple darken-3">
				<br>
				<div class="__div_header">My Library</div>
				<br>
			</div>
		</header>
		
		<br>
		
		<script type="text/javascript">
<?php

if(isset($_POST["username"]) && isset($_POST["password"]) && $_POST["username"] && $_POST["password"])
{
	$ret = USER :: login($_POST["username"], $_POST["password"]);
	
	print('Materialize.toast("' . $ret . '", 5000);');
}

ob_end_flush();

?>
		</script>
		
		<main>
			<center>
				<div class="__titles">Hello! Welcome to my private library. It's a hobby website which I manage in my free time.<br>If you know the password, you're welcome inside! ;-)</div>
				
				<br><br>
				
				<form action="index.php" method="POST">
					<table style="width: 50%">
						<tr>
							<td><div style="font-size: 20px"><code>Username:</code></div></td>
							<td><input type="text" name="username" autofocus="" class="validate"></td>
						</tr>
						<tr>
							<td><div style="font-size: 20px"><code>Password:</code></div></td>
							<td><input type="password" name="password" class="validate"></td>
						</tr>
					</table>
					<br>
					<button class="btn" type="submit">Log in</button>
				</form>
			</center>
		</main>
		
		<footer></footer>
		
	</body>
</html>