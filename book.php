<?php 

// book.php
//
// Started: 28/08/2017

require_once("inc/lib.php");

header("Cache-Control: no-cache, no-store", true);

if(!USER :: check_login())
{
	header("Location: /index.php", true, 301);
}

if(!isset($_POST["id"]) && !isset($_POST["author"]) && !isset($_POST["title"]) && !isset($_POST["number"]) && !isset($_POST["description"]))
{
	$_POST["id"] = "";
	$_POST["author"] = "";
	$_POST["title"] = "";
	$_POST["number"] = "";
	$_POST["description"] = "";
}

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
				<b>[</b>
					<a href="logout.php">log out</a>
				<b>]</b>
				<br>
				<br>
			</div>
		</header>
		
		<br>
		
		<script type="text/javascript">
<?php 
if($_GET["id"] && $_POST["author"] && $_POST["title"] && $_POST["number"] && $_POST["description"])
{
	$ret = REGISTER :: edit_book($_GET["id"], $_POST["author"], $_POST["title"], $_POST["number"], $_POST["description"]);
	
	print('Materialize.toast("' . $ret . ' &nbsp; <a href=\'book.php?id=' . $_GET["id"] . '&action=edit\'>Re-edit</a>?", 5000)');
}
?>
		</script>

<?php

if(isset($_GET["id"]))
{
	$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$mysql -> connect();
	
	$ret = $mysql -> check("SELECT * FROM books WHERE books.id = '$_GET[id]';");
	
	if($ret)
	{
		$ret = mysqli_fetch_array($ret);

		define("BOOK_AUTHOR", $ret["author"]);
		define("BOOK_TITLE", $ret["title"]);
		define("BOOK_NUMBER", $ret["number"]);	
		define("BOOK_DESCRIPTION", $ret["description"]);
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
	
	$mysql -> query("DELETE FROM books WHERE books.id = '$_GET[id]';");
	
	die("<script>alert('Book deleted with success!'); window.location = '$_COOKIE[history]';</script>");
}

?>
		
		<main>
			<div class="row">
				
<?php 

if(isset($_GET["action"]) && $_GET["action"] === "edit")
{
	print('
		<div class="col">
			<div class="__titles">Edit</div>
			
			<br>
			
			<form action="book.php?id=' . $_GET["id"] . '" method="POST">
				<table class="responsive-table">
					<tbody>
						<tr>
							<td>
								<div>Author</div>
							</td>
							<td>
								<input type="text" name="author" class="validate" placeholder="..." value="' . BOOK_AUTHOR . '">
							</td>
						</tr>
						<tr>
							<td>
								<div>Title</div>
							</td>
							<td>
								<input type="text" name="title" class="validate" placeholder="..." value="' . BOOK_TITLE . '">
							</td>
						</tr>
						<tr>
							<td>
								<div>Number</div>
							</td>
							<td>
								<input type="text" name="number" class="validate" placeholder="..." value="' . BOOK_NUMBER . '">
							</td>
						</tr>
						<tr>
							<td>
								<div>Description</div>
							</td>
							<td>
								<textarea name="description" class="validate" style="height: 100px;" maxlength="256">' . BOOK_DESCRIPTION . '</textarea>
							</td>
						</tr>
						<tr>
							<td>
								<button class="btn" type="submit">Edit!</button>
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
				</tbody>
			</table>
		</div>
	');
}
?>
			<div class="col">
				<div class="__titles">Actions</div>
				
				<br>
				
				<button class="btn" onclick="window.location = 'book.php?id=<?php print($_GET["id"]); ?>&action=delete'">Delete book</button>
				
				<br><br>
				
				<button class="btn" onclick="window.location = 'book.php?id=<?php print($_GET["id"]); ?>&action=edit'">Edit book</button>
				
				<br><br>
				
				<button class="btn" onclick="window.location = '<?php print($_COOKIE["history"]); ?>'">Go back</button>
			</div>
		</main>
	</body>
</html>
