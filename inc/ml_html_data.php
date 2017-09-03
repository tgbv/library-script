<?php

// html_data.php
//
// Started: 02/09/2017

define(
	'__HEAD',
	'<head>
		<title>@$__title</title>
		
		<link type="text/css" rel="stylesheet" href="static/css/google.1.css">
		<link type="text/css" rel="stylesheet" href="static/css/google.2.css">
		<link type="text/css" rel="stylesheet" href="static/css/materialize.min.css">
		<link type="text/css" rel="stylesheet" href="static/css/body.css">
		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
		<meta name="android-mobile-web-app-capable" content="yes" />	
		
		<script type="text/javascript" src="static/js/jquery.min.js"></script>
		<script type="text/javascript" src="static/js/materialize.min.js"></script>
	</head>');

define(
	'__HEADER', 
	'<header>
		<div class="navbar-fixed">
			<nav class="nav-extended">
				<div class="nav-wrapper grey darken-3">
					<ul class="brand-logo">
						<li><a href="@$__href" style="text-decoration: none; color: white; font-size: 35px;">@$__title</a></li>
					</ul>
					
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li><a href="library.php" class="__a_head">Library</a></li>
						<li><a href="rents.php" class="__a_head">Rents</a></li>
						<li><a href="settings.php" class="__a_head">Settings</a></li>
						<li><a href="logout.php" class="__a_head">Log out</a></li>
					</ul>
				</div>
				<div class="nav-content hide-on-large-only grey darken-3">
					<ul class="tabs tabs-transparent">
						<li class="tab"><a class="__a_tab_head t___1" href="library.php" onclick="window.location = \'library.php\'">Library</a></li>
						<li class="tab"><a class="__a_tab_head t___2" href="rents.php" onclick="window.location = \'rents.php\'">Rents</a></li>
						<li class="tab"><a class="__a_tab_head t___3" href="settings.php" onclick="window.location = \'settings.php\'">Settings</a></li>
						<li class="tab"><a class="__a_tab_head t___4" href="logout.php" onclick="window.location = \'logout.php\'">Log out</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</header>');
	
define(
	'__SEPARATOR_H_M',
	'<div class="hide-on-large-only">
		<br>
		<br>
	</div>
		
	<br>');
	
define(
	'__BOOK_ACTIONS',
	'<div class="col">
		<div class="__titles">Actions</div>
		
		<br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'book.php?id=@$__id&action=delete\'">Delete book</button>
		
		<br><br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'book.php?id=@$__id&action=edit\'">Edit book</button>
		
		<br><br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'@$__history\'">Go back</button>
	</div>');

define(
	'__RENT_ACTIONS',
	'<div class="col">
		<div class="__titles">Actions</div>
		
		<br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'rent.php?id=@$__id&action=delete\'">Delete record</button>
		
		<br><br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'rent.php?id=@$__id&action=edit\'">Edit record</button>
		
		<br><br>
		
		<button class="btn grey darken-2 waves-effect waves-dark" onclick="window.location = \'@$__history\'">Go back</button>
	</div>');

class HTML_GENERATE
{
	static function head($a)
	{
		$ret = preg_replace('/@\$__title/', $a, __HEAD);
		
		return $ret;
	}
	
	static function header($a, $b, $c)
	{
		$ret = preg_replace('/@\$__title/', $a, __HEADER);
		$ret = preg_replace('/@\$__href/', $b, $ret);
		$ret = preg_replace("/$c/", "active", $ret);
		
		return $ret;
	}
	
	static function book_actions($a, $b)
	{
		$ret = preg_replace('/@\$__id/', $a, __BOOK_ACTIONS);
		$ret = preg_replace('/@\$__history/', $b, $ret);
		
		return $ret;
	}
	
	static function rent_actions($a, $b)
	{
		$ret = preg_replace('/@\$__id/', $a, __RENT_ACTIONS);
		
		if($b === "close")
		{
			$ret = preg_replace('/window.location = \'@\$__history\'/', 'window.close()', $ret);
		}
		else
		{
			$ret = preg_replace('/@\$__history/', $b, $ret);
		}
		
		return $ret;
	}
}