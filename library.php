<?php

// index.php
//
// Started: 23/08/2017

require_once("inc/ml_lib.php");
require_once("inc/ml_html_data.php");

header("Cache-Control: no-cache, no-store", true);

if(!($ret = new ADMIN()) -> check_login())
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

	print('
	<!DOCTYPE HTML>
		<html lang="EN">');

	////////////////////<head>
	print(HTML_GENERATE :: head("My Library"));
	////////////////////</head>
	
	print('<body>');
	print(HTML_GENERATE :: header("The library", "library.php", "t___1"));
	print(__SEPARATOR_H_M);

	if($_POST["author"] && $_POST["title"] && $_POST["number"] && $_POST["description"])
	{
		$ret = REGISTER :: reg_book($_POST["author"], $_POST["title"], $_POST["number"], $_POST["description"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000)</script>');
	}
?>
	<main>
		<div class="section">			
			<div class="row">
				<div class="col s12 m12 l4">
					<div class="__titles">
						Search for book
					</div>
					
					<br>
					
					<form action="library.php" method="GET">
						<table>
							<tbody class="input-field">
								<tr>
									<td>
										<input type="text" name="search" placeholder="Can be authors, titles, numbers ...">
									</td>
								</tr>
								<tr>
									<td>
										<button class="btn grey darken-2 waves-effect waves-dark" type="submit">Search</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				
				<div class="col s12 s12 l6">
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
		
		if(is_array($ret))
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
		else if($ret === false)
		{
			print('<span style="color:red;">No records found...</span>');
		}
		else
		{
			print('<span style="color:red;">' . $ret . '</span>');
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
				<div class="col s12 m12 l4">
					<div class="__titles">
						Register book
					</div>
					
					<form action="<?php print($_COOKIE["history"]); ?>" method="POST">
						<table class="responsive-table">
							<tbody class="input-field">
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
										<button class="btn grey darken-2 waves-effect waves-dark" type="submit">Register</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				
				<div class="col s12 m12 l6">
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
