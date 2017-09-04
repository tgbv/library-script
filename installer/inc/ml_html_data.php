<?php 

// ml_html_data.php
//
// Started: 04/09/2017

define(
	'__HEAD',
	'<head>
		<title>@$__title</title>
		
		<link type="text/css" rel="stylesheet" href="../static/css/google.1.css">
		<link type="text/css" rel="stylesheet" href="../static/css/google.2.css">
		<link type="text/css" rel="stylesheet" href="../static/css/materialize.min.css">
		<link type="text/css" rel="stylesheet" href="../static/css/body.css">
		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
		<meta name="android-mobile-web-app-capable" content="yes" />	
		
		<script type="text/javascript" src="../static/js/jquery.min.js"></script>
		<script type="text/javascript" src="../static/js/materialize.min.js"></script>
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
}