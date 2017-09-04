<?php 

// installer.php
//
// Started: 04/09/2017

require_once("inc/ml_html_data.php");

?>

<!DOCTYPE HTML>

<html lang="EN">
<?php

print(HTML_GENERATE :: head("Welcome!"));

?>
	
	<body>
<?php 

print(HTML_GENERATE :: header("Welcome!", "index.php", "t___1"));

?>
		<br>
		
		<main>
			<div class="container">
				<div style="font-size: 25px;">
					Welcome to My Library script setup!<br><br>
					The following processes will ask you to input the minimal required information in order to run 
					My Library System into your server machine.<br><br>
					Clicking the below button means you agree with the <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_BALNK">General Public License</a> 
					which applies to this web application as well.
				</div>
				<br><br>
				<button class="btn grey darken-2 waves-effect" onclick="window.location = 'config.php';">Continue Setup</button>
			</div>
		</main>
	
	</body>

</html>