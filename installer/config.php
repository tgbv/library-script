<?php 

// config.php
//
// Started: 04/09/2017

require_once("inc/ml_html_data.php");
require_once("../inc/ml_lib.php");

MISC :: no_cache();

!isset($_POST["mysql_db_host"]) ? $_POST["mysql_db_host"] = "" : null;
!isset($_POST["mysql_db_username"]) ? $_POST["mysql_db_username"] = "" : null;
!isset($_POST["mysql_db_password"]) ? $_POST["mysql_db_password"] = "" : null;
!isset($_POST["mysql_db_name"]) ? $_POST["mysql_db_name"] = "" : null;
!isset($_POST["a_email"]) ? $_POST["a_email"] = "" : null;
!isset($_POST["a_password"]) ? $_POST["a_password"] = "" : null;

ob_start();
?>

<!DOCTYPE HTML>

<html lang="EN">
<?php

print(HTML_GENERATE :: head("Configuration"));

?>
	
	<body>
<?php 

print(HTML_GENERATE :: header("Configuration", "config.php", "t___1"));

if(	$_POST["mysql_db_host"] && $_POST["mysql_db_username"] && $_POST["mysql_db_password"] && $_POST["mysql_db_name"] && 
	$_POST["a_email"] && $_POST["a_password"])
{
	if(@$mysql = mysqli_connect($_POST["mysql_db_host"], $_POST["mysql_db_username"], $_POST["mysql_db_password"], $_POST["mysql_db_name"]))
	{
		/* Set up variables */
		$query[0] = "
CREATE TABLE ml_books (
  id int(254) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  author varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  number int(255) NOT NULL,
  description varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$query[1] = "
CREATE TABLE ml_rents (
  id int(254) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer varchar(512) NOT NULL,
  phone_number varchar(255) NOT NULL,
  book_id varchar(512) NOT NULL,
  notes varchar(1024) NOT NULL,
  time varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$query[2] = "
CREATE TABLE ml_users (
  id int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  ip varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$DB_HOST = $_POST["mysql_db_host"];
		$DB_USERNAME = $_POST["mysql_db_username"];
		$DB_PASSWORD = $_POST["mysql_db_password"];
		$DB_NAME = $_POST["mysql_db_name"];
		
		$s = str_replace("\\", "/", realpath(getcwd() . '/../'));
		$HOME_DIR = str_replace($_SERVER["DOCUMENT_ROOT"], "/", $s);
		
		$CRYPT_STRING = crypt(rand(99999, 999999999999), rand(99999, 999999999999)).crypt(rand(99999, 999999999999), rand(99999, 999999999999));

		$data = '<?php

define("DB_HOST", "' . $DB_HOST . '");
define("DB_USERNAME", "' . $DB_USERNAME . '");
define("DB_PASSWORD", "' . $DB_PASSWORD . '");
define("DB_NAME", "' . $DB_NAME . '");
define("HOME_PATH", "' . $HOME_DIR . '");
define("WEBSITE_ADDRESS", $_SERVER["HTTP_HOST"]);
define("CRYPT_STRING", "' . $CRYPT_STRING . '");
';
		$query[3] = "INSERT INTO ml_users(email, password, ip) VALUES('$_POST[a_email]', '" . hash("sha256", $_POST["a_email"] . $CRYPT_STRING . $_POST["a_password"]) . "', '$_SERVER[REMOTE_ADDR]');";
		
		/* Start job */
		if(@$fh = fopen("../inc/ml_config.php", "wb"))
		{
			flock($fh, LOCK_UN);
			fwrite($fh, $data);
			flock($fh, LOCK_EX);
			fclose($fh);
			
			$error = null;
			
			foreach($query as $a => $b)
			{
				if(!$ret = mysqli_query($mysql, $b))
				{
					$error .= mysqli_error($mysql) . " / ";
				}
			}
			
			if($error === null)
			{
				file_put_contents("done.txt", "1");
				
				MISC :: redirect("/done.php");
				ob_end_flush();
			}
			else
			{
				print('<script type="text/javascript">Materialize.toast("MySQL error. Please try again later or contact the developer.", 5000)</script>');
			}
		}
		else
		{
			print('<script type="text/javascript">Materialize.toast("fwrite() error: please make sure /lib/ml_config.php is allowed to be written.", 5000)</script>');
		}
	}
	else
	{
		print('<script type="text/javascript">Materialize.toast("The MySQL credentials are incorrect.", 5000)</script>');
	}
}

?>
		<br>
		
		<main>
			<div class="container">
				<div style="font-size: 23px;">
					Please fill in the following form with the proper information.<br><br>
					If you don't know what to input, please contact your hosting provider or your system administrator 
					and ask for help.
				</div>
				
				<br><br>
				
				<form action="config.php" method="POST">
				
					<div class="divider"></div>
					<table class="striped">
						<tbody class="input-field">
							<tr>
								<td><div style="font-size: 20px;"><code>MySQL database host:</code></div></td>
								<td style="width: 50%;"><input type="text" name="mysql_db_host" placeholder="localhost" value="<?php print($_POST["mysql_db_host"]); ?>"></td>
							</tr>
							<tr>
								<td><div style="font-size: 20px;"><code>MySQL database username:</code></div></td>
								<td><input type="text" name="mysql_db_username" placeholder="root" value="<?php print($_POST["mysql_db_username"]); ?>"></td>
							</tr>
							<tr>
								<td><div style="font-size: 20px;"><code>MySQL database password:</code></div></td>
								<td><input type="password" name="mysql_db_password" placeholder="root" value="<?php print($_POST["mysql_db_password"]); ?>"></td>
							</tr>
							<tr>
								<td><div style="font-size: 20px;"><code>MySQL database name:</code></div></td>
								<td><input type="text" name="mysql_db_name" placeholder="my-library" value="<?php print($_POST["mysql_db_name"]); ?>"></td>
							</tr>
						</tbody>
					</table>
					
					<div class="divider"></div>
					<table class="striped">
						<tbody class="input-field">
							<tr>
								<td><div style="font-size: 20px;"><code>Your admin e-mail:</code></div></td>
								<td style="width: 50%;"><input type="text" name="a_email" placeholder="email@provider.com" value="<?php print($_POST["a_email"]); ?>"></td>
							</tr>
							<tr>
								<td><div style="font-size: 20px;"><code>Your admin password:</code></div></td>
								<td><input type="password" name="a_password" placeholder="1234567890" value="<?php print($_POST["a_password"]); ?>"></td>
							</tr>
						</tbody>
					</table>
					<br>
					<button class="btn grey darken-2 waves-effect" onclick="window.location = 'config.php';">Submit</button>
				</form>
			</div>
		</main>
	</body>

</html>