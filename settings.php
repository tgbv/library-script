<?php 

// settings.php
//
// Started: 03/09/2017

require_once("inc/ml_lib.php");
require_once("inc/ml_html_data.php");

MISC :: no_cache();

if(!($ret = new ADMIN()) -> check_login())
{
	MISC :: redirect("/index.php");
}

if(isset($_COOKIE["history"]))
{
	$ret = preg_replace("/(&*latest_order=.*\&asc=.*)/", "", $_SERVER["REQUEST_URI"]); 
	$ret = preg_replace("/(&*search_order=.*\&asc=.*)/", "", $_SERVER["REQUEST_URI"]); 
	
	setcookie("history", $ret, time() + (10 * 365 * 24 * 60 * 60), "/");
	
	$_COOKIE["history"] = $ret;
}
else
{
	setcookie("history", $_SERVER["REQUEST_URI"], time() + (10 * 365 * 24 * 60 * 60), "/");
}

?>

<!DOCTYPE HTML>

<html lang="EN">
<?php 

print(HTML_GENERATE :: head("Library Settings")); 

print(HTML_GENERATE :: header("Library Settings", HOME_PATH . "settings.php", "t___3"));

print(__SEPARATOR_H_M);

if(isset($_GET["action"]) && $_GET["action"] == "update_password")
{
	if(	isset($_POST["email"]) && isset($_POST["c_password"]) && isset($_POST["n_password"]) &&
		$_POST["email"] && $_POST["c_password"] && $_POST["n_password"])
	{
		$ret = new SETTINGS();
		$ret = $ret -> update_password($_POST["email"], $_POST["c_password"], $_POST["n_password"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000)</script>');
	}
}
else if(isset($_GET["action"]) && $_GET["action"] == "update_email")
{
	if(	isset($_POST["c_email"]) && isset($_POST["password"]) && isset($_POST["n_email"]) &&
		$_POST["c_email"] && $_POST["password"] && $_POST["n_email"])
	{
		$ret = new SETTINGS();
		$ret = $ret -> update_email($_POST["c_email"], $_POST["password"], $_POST["n_email"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000)</script>');
	}
}

?>

<main>

	<div class="section">
		<div class="row">
			<div class="col">
				<div class="__titles">
					Admin management
				</div>
			</div>
		</div>
	</div>
	
	<div class="divider"></div>
	
	<div class="section">
		<div class="row">
			<div class="col s10 m10 l4">
			
				<br>
				<div style="font-size: 20px;">
					Update account password
				</div>
				
				<form action="settings.php?action=update_password" method="POST">
					<table class="responsive-table">
						<tbody class="input-field">
							<tr>
								<td><div>Admin e-mail:</div></td>
								<td><input type="email" name="email" class=""></td>
							</tr>
							<tr>
								<td><div>Current password:</div></td>
								<td><input type="password" name="c_password" class=""></td>
							</tr>
							<tr>
								<td><div>New password:</div></td>
								<td><input type="password" name="n_password" class=""></td>
							</tr>
							<tr>
								<td>
									<button class="btn grey darken-2 waves-effect waves-dark" type="submit">Update</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			
			<div class="col s10 m10 l4">
			
				<br>
				<div style="font-size: 20px;">
					Update account e-mail
				</div>
				
				<form action="settings.php?action=update_email" method="POST">
					<table class="responsive-table">
						<tbody class="input-field">
							<tr>
								<td><div>Current e-mail:</div></td>
								<td><input type="email" name="c_email" class=""></td>
							</tr>
							<tr>
								<td><div>New e-mail:</div></td>
								<td><input type="email" name="n_email" class=""></td>
							</tr>
							<tr>
								<td><div>Admin password:</div></td>
								<td><input type="password" name="password" class=""></td>
							</tr>
							<tr>
								<td>
									<button class="btn grey darken-2 waves-effect waves-dark" type="submit">Update</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</main>

</html>