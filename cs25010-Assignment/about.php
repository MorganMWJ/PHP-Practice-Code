<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Games - About Page</title>
		<link rel="stylesheet" type="text/css" href="http://users.aber.ac.uk/mwj7/cs25010/gameStyle.css"/>
	</head>
	
	<body>
		<div class="header">
			<div class="headerText">
				<h1>About Site</h1>
				<?php
					echo "<h3>" . $_SESSION["userName"] . "'s Session" . "</h3>";
				?>
			</div>
			<div class="headerLinks">
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/gamesShop.php">Back to Games List</a>
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/index.php">Log out</a>
			</div>
		</div>
		<div class="mainContent">
			<div class="aboutTextArea">
				<h2>Assignment Description</h2>
				<p><strong>Design Choices:</strong><br />
				Search criteria results are used by PHP to build SQL queries. As extra I have added drop down options for users to display relevant games based on search criteria of 'Price Order' and 'Platform'
				these criteria allow the user to display games in ascending or descending price order and display a platform of choice.<br />
				<br />
				Each game shown in the main table has a button to add it to the users basket. Once added nothing will change on that page apart from a number that keeps count of the amount of games added.
				For the user to view the added games the user has to go to the Shopping Basket page via the navigation in the header. As games are added their references are added to an array held as a session variable.
				Once the 'basket.php' is loaded only the games that have references equal to those in the array are queried from the database. <br />
				<br />
				Instead of showing repeat rows in the basket table I have added a quantity column if the user adds more than one of the same game. 
				I used the PHP 'array_count_values()' function to get the quantity of each game and also used this in calculating the final price the user has to pay.
				The user has the ability to remove games from his/her basket once viewing them on the basket.php page, if all games are removed the content of the page changes 
				from a checkout form and table to a notification telling the user that they have no selected games to purchase.
				Once the user has successfully filled out the form the page changes to a notification saying a confirmation email has been sent. (does not actually send an email).
				There is a warning in the footer of every page stating that this is not a real web shop.<br />
				<br />
				If the user logs out at any time the session is destroyed and all session variables lost.
				All my pages style is linked in an external .css file and all JavasScript is linked in an external .js file, this is done to make the code more tidier/readable.<br />
				<br />
				<strong>Form validation design:</strong><br />
				My client side form checking uses regular expressions in JavaScript to tell if input is correct or not. I tested these regular expressions using an online regex tester (regex101.com). Below are my decisions for all form inputs that are validated by the client.
				<br />
				<em>Login Input:</em>	Alphabetical or the special character '-' must be either one or two words representing a first and last name.<br />
				<em>Price Cut-Off:	</em>A floating point value no higher than 99.99.<br />
				<em>Email: 	</em>		Any number of any characters except an '@' then one'@' then any number of characters except and '@' then one '.' then any number of characters except an '@'.<br />
				<em>Card number: </em>	In the form xxxx-xxxx-xxxx-xxxx where x represents a number in the range [0-9] and - represents the literal '-'<br />
				<em>Address lines:</em> 	Only alphanumeric characters and the special '-' character<br />
				<em>Town: </em>			Only alphabet characters and the special '-' character<br />
				<em>Post code:</em> 		2 capital letter then 2 digits then one space then one digit then 2 capital letters (CC99 9CC)<br />
				<br />
				<strong>Possible Improvement:</strong><br />
				My login page (index.php) is blank in the middle so as an aesthetic improvement I would add an image or some graphics to fill the space. 
				The regular expressions for both address inputs are very lose allowing many things to be entered that would be unusual for address text, having more strict regular expressions here would be an improvement.
				When the search criteria in the 'gameShop.php' returns no games the table is just empty, it would be nice to have a message notifying the user that the criteria found no matches instead of just an empty table.<br />
				<br />
				The 'Price Cut-Off' Search criteria isn't factored in when building an SQL query, instead it is used in the for-each loop when displaying the result of the query(the table rows).
				If a rows price fits within the rules of the Price Cut-Off then the row is echoed out and displayed, otherwise the row is omitted from the table. 
				I have used this implementation because I added this functionality towards the end and the alternative would have required more code be re-written, which would have taken time I didn't have.
				If I were to redo the assignment I would have this search criteria build more SQL queries to save querying unneeded data from the database.<br />
				</p>
			</div>
			<div class="aboutTextArea">
				<h2>Declaration of Originality</h2>
				<p>This submission is my own work, except where clearly indicated. I understand that there are severe penalties for plagiarism and other unfair practice, which can lead to loss of marks or even the withholding of a degree.<br />
				I have read the sections on unfair practice in the Students' Examinations Handbook and the relevant sections of the current Student Handbook of the Department of Computer Science.<br />
				I  understand  and  agree  to  abide  by  the  University's  regulations  governing  these issues.
				</p>
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
	</body>
</html>