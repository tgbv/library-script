<?php 

// rents.php
//
// Started: 02/09/2017

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

if(!isset($_POST["customer_name"]) && !isset($_POST["customer_phone_number"]) && !isset($_POST["book_id"]) && !isset($_POST["notes"]))
{
	$_POST["customer_name"] = "";
	$_POST["customer_phone_number"] = "";
	$_POST["book_id"] = "";
	$_POST["notes"] = "";
}

?>

<!DOCTYPE HTML>

<html lang="EN">
<?php 
	//////////////// <head>
	print(HTML_GENERATE :: head("My Library - Rents")); 
	//////////////// </head>
?>

<body>
<?php 
	//////////////// <header>
	print(HTML_GENERATE :: header("The Rents", "rents.php", "t___2"));
	//////////////// </header>
	
	print(__SEPARATOR_H_M);
	
	if($_POST["customer_name"] && $_POST["customer_phone_number"] && $_POST["book_id"] && $_POST["notes"])
	{
		$ret = REGISTER :: reg_rent($_POST["customer_name"], $_POST["customer_phone_number"], $_POST["book_id"], $_POST["notes"]);
		
		print('<script type="text/javascript">Materialize.toast("' . $ret . '", 5000)</script>');
	}
?>
	<main>
		<div class="section">			
			<div class="row">
				<div class="col s12 m12 l4">
					<div class="__titles">
						Search for rents
					</div>
					
					<br>
					
					<form action="rents.php" method="GET">
						<table>
							<tbody class="input-field">
								<tr>
									<td>
										<input type="text" name="search" placeholder="Can be usernames, titles, numbers ...">
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
				
				<div class="col s12 m12 l5">
					<div class="__titles">
						Search results:
					</div>
					
					<br>
<?php 

if(isset($_GET["search"]) && $_GET["search"])
{
	if(isset($_GET["search_order"]) && isset($_GET["asc"]) && $_GET["search_order"] && $_GET["asc"])
	{
		$ret = SEARCH :: get_rents($_GET["search"], $_GET["search_order"], $_GET["asc"]);
		
		is_array($ret) ? $ret = array_reverse($ret) : null;
	}
	else
	{
		$ret = SEARCH :: get_rents($_GET["search"], "id", "1");
		
		is_array($ret) ? $ret = array_reverse($ret) : null;
	}
	
	if(is_array($ret))
	{
		print('<table class="striped responsive-table">
				<thead>
					<tr>
						<td>
							<center>
								<a href="' . $_COOKIE["history"] . '&search_order=id&asc=1" id="latest_id"><b style="color: green;">ID</b></a>
							</center>
						</td>
						<td>
							<center>
								<a href="' . $_COOKIE["history"] . '&search_order=customer&asc=1" id="latest_customer"><b style="color: green;">Customer</b></a>
							</center>
						</td>
						<td>
							<center>
								<a href="' . $_COOKIE["history"] . '&search_order=phone_number&asc=1" id="latest_phone_number"><b style="color: green;">Phone number</b></a>
							</center>
						</td>
						<td>
							<center>
								<a href="' . $_COOKIE["history"] . '&search_order=book_id&asc=1" id="latest_book_title"><b style="color: green;">Books rented (IDs)</b></a>
							</center>
						</td>
						<td>
							<center>
								<a href="' . $_COOKIE["history"] . '&search_order=time&asc=1" id="latest_renting_date"><b style="color: green;">Renting date/time</b></a>
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
					print('<td>
							<center><a href="rents.php?search=rid:' . $y . '">' . $y . '</a></center>
						</td>');
				}
				else if($x === 1)
				{
					print('<td>
							<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
						</td>');
				}
				else if($x === 2)
				{
					print('<td>
							<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
						</td>');
				}
				else if($x === 3)
				{
					print('<td>
							<center><a href="rent.php?id=' . $b[0] . '">' . $y . '</a></center>
						</td>');
				}
				else if($x === 5)
				{
					print('<td>
							<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
						</td>');
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
				
		<!----------------->
		
		<div class="divider"></div>
		<br>
		<div class="section">
			<div class="row">
				<div class="col s12 m12 l4">
					<div class="__titles">
						Register rent
					</div>
					
					<form action="<?php print($_COOKIE["history"]); ?>" method="POST">
						<table class="responsive-table">
							<tbody class="input-field">
								<tr>
									<td>
										<div>Customer name:</div>
									</td>
									<td>
										<input type="text" name="customer_name" class="validate" placeholder="...">
									</td>
								</tr>
								<tr>
									<td>
										<div>Customer phone number:</div>
									</td>
									<td>
										<input type="text" name="customer_phone_number" class="validate" placeholder="...">
									</td>
								</tr>
								<tr>
									<td>
										<div>Book(s) ID:</div>
									</td>
									<td>
										<input type="text" name="book_id" class="validate" placeholder="...">
									</td>
								</tr>
								<tr>
									<td>
										<div>Notes:</div>
									</td>
									<td>
										<textarea name="notes" class="validate" style="height: 100px;" maxlength="256"></textarea>
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
						Latest registered rents
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
										<a href="?latest_order=customer&asc=1" id="latest_customer"><b style="color: green;">Customer</b></a>
									</center>
								</td>
								<td>
									<center>
										<a href="?latest_order=phone_number&asc=1" id="latest_phone_number"><b style="color: green;">Phone number</b></a>
									</center>
								</td>
								<td>
									<center>
										<a href="?latest_order=book_id&asc=1" id="latest_book_id"><b style="color: green;">Books rented (IDs)</b></a>
									</center>
								</td>
								<td>
									<center>
										<a href="?latest_order=time&asc=1" id="latest_time"><b style="color: green;">Renting date/time</b></a>
									</center>
								</td>
							</tr>
						</thead>
						<tbody>
<?php 

	if(isset($_GET["latest_order"]) && isset($_GET["asc"]) && $_GET["latest_order"] && $_GET["asc"])
	{
		$ret = array_reverse(RENTS :: get_latest_rents($_GET["latest_order"], $_GET["asc"]));
	}
	else
	{
		$ret = array_reverse(RENTS :: get_latest_rents("id", "1"));
	}
	
	foreach($ret as $a => $b)
	{
		print("<tr>");
		
		foreach($b as $x => $y)
		{
			if($x === 0)
			{
				print('<td>
						<center><a href="rents.php?search=rid:' . $y . '">' . $y . '</a></center>
					</td>');
			}
			else if($x === 1)
			{
				print('<td>
						<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
					</td>');
			}
			else if($x === 2)
			{
				print('<td>
						<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
					</td>');
			}
			else if($x === 3)
			{
				print('<td>
						<center><a href="rent.php?id=' . $b[0] . '">' . $y . '</a></center>
					</td>');
			}
			else if($x === 5)
			{
				print('<td>
						<center><a href="rents.php?search=' . $y . '">' . $y . '</a></center>
					</td>');
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
	<script type="text/javascript">
			$(document).ready(function(){
<?php
	if(isset($_GET["latest_order"]) && isset($_GET["asc"]) && $_GET["latest_order"] && $_GET["asc"])
	{
		if($_GET["latest_order"] === "id" && $_GET["asc"] == 1)
		{
			print("$('#latest_id').attr('href', '?latest_order=id&asc=-1');");
		}
		
		if($_GET["latest_order"] === "customer" && $_GET["asc"] == 1)
		{
			print("$('#latest_customer').attr('href', '?latest_order=customer&asc=-1');");
		}
		
		if($_GET["latest_order"] === "phone_number" && $_GET["asc"] == 1)
		{
			print("$('#latest_phone_number').attr('href', '?latest_order=phone_number&asc=-1');");
		}
		
		if($_GET["latest_order"] === "book_id" && $_GET["asc"] == 1)
		{
			print("$('#latest_book_id').attr('href', '?latest_order=book_id&asc=-1');");
		}
		
		if($_GET["latest_order"] === "time" && $_GET["asc"] == 1)
		{
			print("$('#latest_time').attr('href', '?latest_order=time&asc=-1');");
		}
	}
	
	if(isset($_GET["search_order"]) && isset($_GET["asc"]) && $_GET["search_order"] && $_GET["asc"])
	{
		if($_GET["latest_order"] === "id" && $_GET["asc"] == 1)
		{
			print("$('#latest_id').attr('href', '?latest_order=id&asc=-1');");
		}
		
		if($_GET["latest_order"] === "customer" && $_GET["asc"] == 1)
		{
			print("$('#latest_customer').attr('href', '?latest_order=customer&asc=-1');");
		}
		
		if($_GET["latest_order"] === "phone_number" && $_GET["asc"] == 1)
		{
			print("$('#latest_phone_number').attr('href', '?latest_order=phone_number&asc=-1');");
		}
		
		if($_GET["latest_order"] === "book_id" && $_GET["asc"] == 1)
		{
			print("$('#latest_book_id').attr('href', '?latest_order=book_id&asc=-1');");
		}
		
		if($_GET["latest_order"] === "time" && $_GET["asc"] == 1)
		{
			print("$('#latest_time').attr('href', '?latest_order=time&asc=-1');");
		}
	}
?>
			});
	</script>
</body>