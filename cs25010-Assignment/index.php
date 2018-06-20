<?php
	session_start();
	//When returned to this log in page(user logs out) end the session
	session_unset();
	session_destroy();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Games - Login Page</title>
		<link rel="stylesheet" type="text/css" href="http://users.aber.ac.uk/mwj7/cs25010/gameStyle.css"/>
		<script src="http://users.aber.ac.uk/mwj7/cs25010/gameScript.js" ></script>
	</head>

	<body>
		<div class="container">
			<div class="header">
				<div id="loginHeader">
					<h1>Welcome To Games</h1>
					<form name="logIn" action="http://users.aber.ac.uk/mwj7/cs25010/gamesShop.php" method="post" onsubmit="return isValidName()">
						<input class="textBox" type="text" name="name" placeholder="Name Here..."/>
						<input class="submitButton" type="submit" value="Log on" />
					</form>
				</div>
			</div>
			<div class="footer">
				<p class="disclaim"><i>The information provided on this and other pages by me, Morgan Jones (mwj7@aber.ac.uk),<br />
				is under my own personal responsibility and not that of Aberystwyth University.<br />
				Similarly, any opinions expressed are my own and are in no way to be taken as those of A.U.
				</i></p>
				
				<p class="disclaim"><i><strong>This is not a real web shop</strong>; it is created as part of my university coursework.<br />
				Please <strong>do not</strong> attempt to buy anything from this site, or <strong>enter any real card details</strong>.
				</i></p>
				
				<p id="valid">
					<!--Below is external code used for the validation images within the footer -->
					<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
					<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://www.w3.org/Icons/valid-css" alt="Valid CSS!" height="31" width="88" /></a>  
				</p>				
			</div>
		</div>
	</body>
</html>
