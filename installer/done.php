<?php 

// done.php
//
// Started: 04/09/2017

require_once("inc/ml_html_data.php");

?>

<!DOCTYPE HTML>

<html lang="EN">
<?php

print(HTML_GENERATE :: head("Installation completed!"));

?>
	
	<body>
<?php 

print(HTML_GENERATE :: header("Done!", "index.php", "t___1"));

?>
		<br>
		
		<main>
			<div class="container">
				<div style="font-size: 25px;">
					Good job! The installation process has finished successfully!<br><br>
					Please leave a feedback note on the <a href="http://my-library.ml">official website</a>. Thank you for using my web application!<br><br>
				</div>
				<br>
				<button class="btn grey darken-2 waves-effect" onclick="window.location = '../index.php';">Click here to continue</button>
			</div>
		</main>
	
	</body>

</html>