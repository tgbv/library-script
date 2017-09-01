<?php

// index.php
//
// Started: 23/08/2017

require_once("inc/lib.php");

header("Cache-Control: no-cache, no-store", true);

if(!USER :: check_login())
{
	header("Location: /index.php", true, 301);
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

if(!isset($_POST["author"]) && !isset($_POST["title"]) && !isset($_POST["number"]) && !isset($_POST["description"]))
{
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
	if($_POST["author"] && $_POST["title"] && $_POST["number"] && $_POST["description"])
	{
		$ret = REGISTER :: reg_book($_POST["author"], $_POST["title"], $_POST["number"], $_POST["description"]);
		
		print('Materialize.toast("' . $ret . '", 5000)');
	}
?>
		</script>
		
		<main>
			<div class="section">			
				<div class="row">
					<div class="col s5 m5 l3">
						<div class="__titles">
							Search for book
						</div>
						
						<br>
						
						<form action="library.php" method="GET">
							<table>
								<tbody>
									<tr>
										<td>
											<input type="text" name="search" placeholder="Can be authors, titles, numbers ...">
										</td>
									</tr>
									<tr>
										<td>
											<button class="btn" type="submit">Search</button>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					
					<div class="col s10 l6">
						<div class="__titles">
							Search results:
						</div>
						
						<br>
					
<?php 
	if(isset($_GET["search"]) && $_GET["search"])
	{
		if(isset($_GET["search_order"]) && isset($_GET["asc"]) && $_GET["search_order"] && $_GET["asc"])
		{
			$ret = SEARCH :: get_results($_GET["search"], $_GET["search_order"], $_GET["asc"]);
			
			is_array($ret) ? $ret = array_reverse($ret) : null;
		}
		else
		{
			$ret = SEARCH :: get_results($_GET["search"], "id", 1);
			
			is_array($ret) ? $ret = array_reverse($ret) : null;
		}
		
		if($ret !== false)
		{
			print('<table class="striped responsive-table">
					<thead>
						<tr>
							<td>
								<center>
									<a href="' . $_COOKIE["history"] . '&search_order=id&asc=1" id="search_id"><b style="color: green;">ID</b></a>
								</center>
							</td>
							<td>
								<center>
									<a href="' . $_COOKIE["history"] . '&search_order=author&asc=1" id="search_author"><b style="color: green;">Author</b></a>
								</center>
							</td>
							<td>
								<center>
									<a href="' . $_COOKIE["history"] . '&search_order=title&asc=1" id="search_title"><b style="color: green;">Title</b></a>
								</center>
							</td>
							<td>
								<center>
									<a href="' . $_COOKIE["history"] . '&search_order=number&asc=1" id="search_number"><b style="color: green;">Number</b></a>
								</center>
							</td>
						</tr>
					</thead>
					<tbody>');
			
			foreach($ret as $a => $b)
			{
				print("<tr>");
				
				foreach($b as $x => $y)
				{
					if($x === 0)
					{
						print('
						<td>
							<center>
								<a href="library.php?search=bid:' . $y . '">' . $y . '</a>
							</center>
						</td>
						');
					}
					else if($x === 2)
					{
						print('
							<td>
								<center>
									<a href="book.php?id=' . $b[0] . '">' . $y . '</a>
								</center>
							</td>
						');
					}
					else if($x === 4)
					{
						// Do nothing.
					}
					else
					{
						print('
							<td>
								<center>
									<a href="library.php?search=' . $y . '">' . $y . '</a>
								</center>
							</td>
						');
					}
				}
				
				print("</tr>");
			}
			
			print('		</tbody>
					</table>');
		}
		else
		{
			print('<span style="color:red;">No records found...</span>');
		}
	}
?>
					</div>
				</div>
			</div>
			
			<!------------->
			
			<div class="divider"></div>
			<br>
			<div class="section">
				<div class="row">
					<div class="col s10 m10 l3">
						<div class="__titles">
							Register book
						</div>
						
						<form action="<?php print($_COOKIE["history"]); ?>" method="POST">
							<table class="responsive-table">
								<tbody>
									<tr>
										<td>
											<div>Author</div>
										</td>
										<td>
											<input type="text" name="author" class="validate" placeholder="...">
										</td>
									</tr>
									<tr>
										<td>
											<div>Title</div>
										</td>
										<td>
											<input type="text" name="title" class="validate" placeholder="...">
										</td>
									</tr>
									<tr>
										<td>
											<div>Number</div>
										</td>
										<td>
											<input type="text" name="number" class="validate" placeholder="...">
										</td>
									</tr>
									<tr>
										<td>
											<div>Description</div>
										</td>
										<td>
											<textarea name="description" class="validate" style="height: 100px;" maxlength="256"></textarea>
										</td>
									</tr>
									<tr>
										<td>
											<button class="btn" type="submit">Register</button>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					
					<div class="col s10 l6">
						<div class="__titles">
							Latest registered books
						</div>
						<br>
						<table class="striped responsive-table">
							<thead>
								<tr>
									<td>
										<center>
											<a href="?latest_order=id&asc=1" id="latest_id"><b style="color: green;">ID</b></a>
										</center>
									</td>
									<td>
										<center>
											<a href="?latest_order=author&asc=1" id="latest_author"><b style="color: green;">Author</b></a>
										</center>
									</td>
									<td>
										<center>
											<a href="?latest_order=title&asc=1" id="latest_title"><b style="color: green;">Title</b></a>
										</center>
									</td>
									<td>
										<center>
											<a href="?latest_order=number&asc=1" id="latest_number"><b style="color: green;">Number</b></a>
										</center>
									</td>
								</tr>
							</thead>
							
							<tbody>
<?php 
	if(isset($_GET["latest_order"]) && isset($_GET["asc"]) && $_GET["latest_order"] && $_GET["asc"])
	{
		$ret = array_reverse(REGISTER :: get_latest_regs($_GET["latest_order"], $_GET["asc"]));
	}
	else
	{
		$ret = array_reverse(REGISTER :: get_latest_regs("id", 1));
	}

	foreach($ret as $a => $b)
	{
		print("<tr>");
		
		foreach($b as $x => $y)
		{
			if($x === 0)
			{
				print('
				<td>
					<center>
						<a href="library.php?search=bid:' . $y . '">' . $y . '</a>
					</center>
				</td>
				');
			}
			else if($x === 2)
			{
				print('
					<td>
						<center>
							<a href="book.php?id=' . $b[0] . '">' . $y . '</a>
						</center>
					</td>
				');
			}
			else if($x === 4)
			{
				// Do nothing.
			}
			else
			{
				print('
					<td>
						<center>
							<a href="library.php?search=' . $y . '">' . $y . '</a>
						</center>
					</td>
				');
			}
		}
		
		print("</tr>");
	}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>
		
		<footer>
		</footer>
		
		<script type="text/javascript">
			$(document).ready(function(){
<?php
	if(isset($_GET["latest_order"]) && isset($_GET["asc"]) && $_GET["latest_order"] && $_GET["asc"])
	{
		if($_GET["latest_order"] === "id" && $_GET["asc"] == 1)
		{
			print("$('#latest_id').attr('href', '?latest_order=id&asc=-1');");
		}
		
		if($_GET["latest_order"] === "author" && $_GET["asc"] == 1)
		{
			print("$('#latest_author').attr('href', '?latest_order=author&asc=-1');");
		}
		
		if($_GET["latest_order"] === "title" && $_GET["asc"] == 1)
		{
			print("$('#latest_title').attr('href', '?latest_order=title&asc=-1');");
		}
		
		if($_GET["latest_order"] === "number" && $_GET["asc"] == 1)
		{
			print("$('#latest_number').attr('href', '?latest_order=number&asc=-1');");
		}
	}
	
	if(isset($_GET["search_order"]) && isset($_GET["asc"]) && $_GET["search_order"] && $_GET["asc"])
	{
		if($_GET["search_order"] === "id" && $_GET["asc"] == 1)
		{
			print("$('#search_id').attr('href', '?search=$_GET[search]&search_order=id&asc=-1');");
		}
		
		if($_GET["search_order"] === "author" && $_GET["asc"] == 1)
		{
			print("$('#search_author').attr('href', '?search=$_GET[search]&search_order=author&asc=-1');");
		}
		
		if($_GET["search_order"] === "title" && $_GET["asc"] == 1)
		{
			print("$('#search_title').attr('href', '?search=$_GET[search]&search_order=title&asc=-1');");
		}
		
		if($_GET["search_order"] === "number" && $_GET["asc"] == 1)
		{
			print("$('#search_number').attr('href', '?search=$_GET[search]&search_order=number&asc=-1');");
		}
	}
?>
			});
		</script>
	</body>
</html>
