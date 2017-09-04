<?php 

// book.php
//
// Started: 28/08/2017

require_once("inc/ml_lib.php");
require_once("inc/ml_html_data.php");

MISC :: no_cache();

if(!($ret = new ADMIN()) -> check_login())
{
	MISC :: redirect("/index.php");
}

if(!isset($_POST["id"]) && !isset($_POST["author"]) && !isset($_POST["title"]) && !isset($_POST["number"]) && !isset($_POST["description"]))
{
	$_POST["id"] = "";
	$_POST["author"] = "";
	$_POST["title"] = "";
	$_POST["number"] = "";
	$_POST["description"] = "";
}

	print('
		<!DOCTYPE HTML>
			<html lang="EN">');
	
	/////////////////// <head>
	print(HTML_GENERATE :: head("My Library - Book details"));
	/////////////////// </head>
	
	print('<body>');

	/////////////////// <header>
	print(HTML_GENERATE :: header("Book details", "book.php?id=$_GET[id]", "@\$__nothing"));
	/////////////////// </header>	
	
	print(__SEPARATOR_H_M);

	if($_GET["id"] && $_POST["author"] && $_POST["title"] && $_POST["number"] && $_POST["description"])
	{
		$ret = REGISTER :: edit_book($_GET["id"], $_POST["author"], $_POST["title"], $_POST["number"], $_POST["description"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . ' &nbsp; <a href=\'book.php?id=' . $_GET["id"] . '&action=edit\'>Re-edit</a>?", 5000)</script>');
	}

	if(isset($_GET["id"]))
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$ret = $mysql -> check("SELECT * FROM ml_books WHERE ml_books.id = '$_GET[id]';");
		
		if($ret)
		{
			$ret = mysqli_fetch_array($ret);

			define("BOOK_AUTHOR", $ret["author"]);
			define("BOOK_TITLE", $ret["title"]);
			define("BOOK_NUMBER", $ret["number"]);	
			define("BOOK_DESCRIPTION", $ret["description"]);

			if($ret = $mysql -> query("SELECT id, customer FROM ml_rents WHERE ml_rents.book_id REGEXP '$_GET[id]';"))
			{
				$ret = mysqli_fetch_all($ret);
				
				foreach($ret as $a => $b)
				{
					foreach($b as $x => $y)
					{
						if($x === 0) $RENT_ID[$a] = $y;
						if($x === 1) $RENT_CUSTOMER[$a] = $y;
					}
				}
			}
		}
		else
		{
			die("<script>alert('Please provide a valid book ID.'); window.location = 'library.php';</script>");
		}
	}
	else
	{
		die("<script>alert('Please provide a valid book ID.'); window.location = 'library.php';</script>");
	}


	if(isset($_GET["action"]) && $_GET["action"] === "delete")
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$mysql -> query("DELETE FROM ml_books WHERE ml_books.id = '$_GET[id]';");
		
		die("<script>alert('Book deleted with success!'); window.location = '$_COOKIE[history]';</script>");
	}
	
	/////////////////// <main>
	
	print('<main><div class="row">');

	if(isset($_GET["action"]) && $_GET["action"] === "edit")
	{
		print('
			<div class="col">
				<div class="__titles">Edit</div>
				
				<br>
				
				<form action="book.php?id=' . $_GET["id"] . '" method="POST">
					<table class="responsive-table">
						<tbody class="input-field">
							<tr>
								<td>
									<div>Author</div>
								</td>
								<td>
									<input type="text" name="author" class="" placeholder="..." value="' . BOOK_AUTHOR . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Title</div>
								</td>
								<td>
									<input type="text" name="title" class="" placeholder="..." value="' . BOOK_TITLE . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Number</div>
								</td>
								<td>
									<input type="text" name="number" class="" placeholder="..." value="' . BOOK_NUMBER . '">
								</td>
							</tr>
							<tr>
								<td>
									<div>Description</div>
								</td>
								<td>
									<textarea name="description" class="" style="height: 100px;" maxlength="256">' . BOOK_DESCRIPTION . '</textarea>
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
							<td><div><b>Author:</b></div></td>
							<td><div><code>' . BOOK_AUTHOR . '</code></div></td>
						</tr>
						<tr>
							<td><div><b>Title:</b></div></td>
							<td><div><code>' . BOOK_TITLE . '</code></div></td>
						</tr>
						<tr>
							<td><div><b>Number:</b></div></td>
							<td><div><code>' . BOOK_NUMBER . '</code></div></td>
						</tr>
						<tr>
							<td><div><b>Description:</b></div></td>
							<td><div style="max-width: 400px; word-wrap: break-word;"><code>' . BOOK_DESCRIPTION . '</code></div></td>
						</tr>
						');
	
		if(isset($RENT_ID) && isset($RENT_CUSTOMER))
		{
			$ret = null;
			
			print('<tr>
					<td><div><b>Rented to:</b></div></td>
					<td><div><code>');
			
			foreach($RENT_ID as $a => $b)
			{
				$ret .= '<a href="rent.php?id='. $b . '&tab=close" target="_BLANK">' . $RENT_CUSTOMER[$a] . '</a>, ';
			}
			$ret = trim($ret, ", ");
			
			print($ret);
			print('	</code></div></td>
				</tr>');
		}
			
		print('			</tbody>
				</table>
			</div>
		');
	}

	print(HTML_GENERATE :: book_actions($_GET["id"], $_COOKIE["history"]));

	print("</div></main>");
	
	/////////////////////// </main>
?>
	</body>
</html>