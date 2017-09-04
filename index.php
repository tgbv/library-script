<?php

// index.php
//
// Started: 04/08/2017

require_once("inc/ml_lib.php");

MISC :: no_cache();

$check = file_get_contents("/installer/done.txt");

if($check === "0")
{
	MISC :: redirect("/installer/index.php");
}
else
{
	MISC :: redirect("/login.php");
}