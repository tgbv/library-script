<?php 

// rent.php
//
// Started: 03/09/2017

require_once("inc/ml_lib.php");
require_once("inc/ml_html_data.php");

MISC :: no_cache();

if(!($ret = new ADMIN()) -> check_login())
{
	MISC :: redirect("/index.php");
}

if(!isset($_POST["id"]) && !isset($_POST["customer"]) && !isset($_POST["phone_number"]) && !isset($_POST["book_id"]) && !isset($_POST["notes"]))
{
	$_POST["id"] = "";
	$_POST["customer"] = "";
	$_POST["phone_number"] = "";
	$_POST["book_id"] = "";
	$_POST["notes"] = "";
}

	print('
		<!DOCTYPE HTML>
			<html lang="EN">');
	
	/////////////////// <head>
	print(HTML_GENERATE :: head("My Library - Rent details"));
	/////////////////// </head>
	
	print('<body>');

	/////////////////// <header>
	print(HTML_GENERATE :: header("Rent details", HOME_PATH . "rent.php?id=$_GET[id]", "@\$__nothing"));
	/////////////////// </header>	
	
	print(__SEPARATOR_H_M);

	if($_GET["id"] && $_POST["customer"] && $_POST["phone_number"] && $_POST["book_id"] && $_POST["notes"])
	{
		$ret = REGISTER :: edit_rent($_GET["id"], $_POST["customer"], $_POST["phone_number"], $_POST["book_id"], $_POST["notes"], $_POST["book_id"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000)/* &nbsp; <a href=\'rent.php?id=' . $_GET["id"] . '&action=edit\'>Re-edit</a>?*/</script>');
	}

	if(isset($_GET["id"]))
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$ret = $mysql -> check("SELECT * FROM ml_rents WHERE ml_rents.id = '$_GET[id]';");
		
		if($ret)
		{
			$ret = mysqli_fetch_array($ret);

			define("RENT_CUSTOMER", $ret["customer"]);
			define("RENT_PHONE_NUMBER", $ret["phone_number"]);
			define("RENT_BOOK_ID", $ret["book_id"]);	
			define("RENT_NOTES", $ret["notes"]);
			define("RENT_TIME", $ret["time"]);
			
			if($ret = $mysql -> check("SELECT id, title FROM ml_books WHERE ml_books.id REGEXP '" . RENT_BOOK_ID . "';"))
			{
				$ret = mysqli_fetch_all($ret);
				
				foreach($ret as $k => $v)
				{
					foreach($v as $x => $y)
					{
						if($x === 0) $BOOK_ID[$k] = $y;
						if($x === 1) $BOOK_TITLE[$k] = $y;
					}
				}
			}
		}
		else
		{
			die("<script>alert('Please provide a valid rent record ID.'); window.location = 'rents.php';</script>");
		}
	}
	else
	{
		die("<script>alert('Please provide a valid rent record ID.'); window.location = 'rents.php';</script>");
	}


	if(isset($_GET["action"]) && $_GET["action"] === "delete")
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$mysql -> query("DELETE FROM ml_rents WHERE ml_rents.id = '$_GET[id]';");
		
		die("<script>alert('Rent record deleted with success!'); window.location = '$_COOKIE[history]';</script>");
	}
	
	/////////////////// <main>
	
	print('<main><div class="row">');

	if(isset($_GET["action"]) && $_GET["action"] === "edit")
	{
		print('
			<div class="col">
				<div class="__titles">Edit</div>
				
				<br>
				
				<form action="rent.php?id=' . $_GET["id"] . '" method="POST">
					<table class="responsive-table">
						<tbody class="input-field">
							<tr>
								<td>
									<div>Customer</div>
								</td>
								<td>
									<input type="text" name="customer" class="" placeholder="..." value="' . RENT_CUSTOMER . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Phone number</div>
								</td>
								<td>
									<input type="text" name="phone_number" class="" placeholder="..." value="' . RENT_PHONE_NUMBER . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Books ID</div>
								</td>
								<td>
									<input type="text" name="book_id" class="" placeholder="..." value="' . RENT_BOOK_ID . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Notes</div>
								</td>
								<td>
									<textarea name="notes" class="" style="height: 100px;" maxlength="256">' . RENT_NOTES . '</textarea>
								</td>
							</tr>
							<tr>
								<td>
									<button class="btn grey darken-2 waves-effect waves-dark" type="submit">Edit!</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		');
	}
	else
	{
		print('
			<div class="col">
				<div class="__titles">Details</div>
				
				<br>
				
				<table>
					<tbody>
						<tr>
							<td><div><b>Customer:</b></div></td>
							<td><div><code>' . RENT_CUSTOMER . '</code></div></td>
						</tr>
						<tr>
							<td><div><b>Phone number:</b></div></td>
							<td><div><code>' . RENT_PHONE_NUMBER . '</code></div></td>
						</tr>
						<tr>
							<td><div><b>Books:</b></div></td>
							<td><div><code>');	
		
		$ret = null;

		foreach($BOOK_ID as $x => $y)
		{
			@$ret .= '<a href="book.php?id=' . $y . '&tab=close" target="_BLANK">' . $BOOK_TITLE[$x] . '</a>, ';
		}
		
		$ret = trim($ret, ", ");
		
		print($ret);
					
		print('				</code></div></td>
						</tr>
						<tr>
							<td><div><b>Notes:</b></div></td>
							<td><div style="max-width: 400px; word-wrap: break-word;"><code>' . RENT_NOTES . '</code></div></td>
						</tr>
					</tbody>
				</table>
			</div>
		');
	}

	if(isset($_GET["tab"]) && $_GET["tab"] === "close")
	{
		print(HTML_GENERATE :: rent_actions($_GET["id"], "close"));
	}
	else
	{
		print(HTML_GENERATE :: rent_actions($_GET["id"], $_COOKIE["history"]));
	}

	print("</div></main>");
	
	/////////////////////// </main>
?>
	</body>
</html>