<?php

// index.php
//
// Started: 29/08/2017

require_once("inc/ml_lib.php");
require_once("inc/ml_html_data.php");

MISC :: no_cache();

if(($ret = new ADMIN()) -> check_login())
{
	header("Location: /library.php", true, 301);
}

ob_start();
?>

<!DOCTYPE HTML>

<html lang="EN">
<?php

print(HTML_GENERATE :: head("My Library - Login"));

print(HTML_GENERATE :: header("Login", "index.php", "t___1"));

print(__SEPARATOR_H_M);

if(isset($_POST["email"]) && isset($_POST["password"]) && $_POST["email"] && $_POST["password"])
{
	$ret = new ADMIN();
	$ret = $ret -> login($_POST["email"], $_POST["password"]);
	
	print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000);</script>');
}

ob_end_flush();

?>
		
		<main>
			<center>
				<div class="__titles">Please login</div>
				
				<br><br>
				
				<div class="row">
					<div class="col s12 m12 l6 offset-l3">
						<form action="index.php" method="POST">
							<table style="">
								<tbody class="input-field">
									<tr>
										<td><div style="font-size: 20px"><code>Username:</code></div></td>
										<td><input type="text" name="email" autofocus="" class="validate"></td>
									</tr>
									<tr>
										<td><div style="font-size: 20px"><code>Password:</code></div></td>
										<td><input type="password" name="password" class="validate"></td>
									</tr>
								</tbody>
							</table>
							<br>
							<button class="btn grey darken-2 waves-effect" type="submit">Log in</button>
						</form>
					</div>
				</div>
			</center>
		</main>
		
		<footer></footer>
		
	</body>
</html>