<?php

// logout.php
//
// Started: 30/08/2017

header("Cache-Control: no-cache, no-store", true);

setcookie("key", "expired", time()-1, "/");

header("Location: /index.php", true, 301);