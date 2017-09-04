<?php

// logout.php
//
// Started: 30/08/2017

require_once("inc/ml_lib.php");

header("Cache-Control: no-cache, no-store", true);

setcookie("key", "expired", time()-1, "/");

MISC :: redirect("/index.php");