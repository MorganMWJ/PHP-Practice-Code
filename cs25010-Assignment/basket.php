<?php
	// Start the session
	session_start();
	
	//if the user just checked out then empty the old shopping basket
	//all fields must be completed correctly for the user to check out 
	//therefore it is sufficient to only check if one of the post variables is set
	if(isset($_POST["email"])){
		unset($_SESSION["gameBasket"]);
		$_SESSION["basketCount"] = 0;
	}
	
	//If the user just removed an item from their Basket then decrement Basket count & remove from storage
	if(isset($_POST["removeFromBasket"])){
		$_SESSION["basketCount"] -= 1;
		foreach (array_keys($_SESSION["gameBasket"]) as $key) {
			//when the key's value is = to the ref number posted remove that game from the list
			if($_SESSION["gameBasket"][$key] == $_POST["removeFromBasket"]){
				unset($_SESSION["gameBasket"][$key]);
				//break after one remove to preventing removing multiples of one game
				break;
			}
		}		
	}
	
	//print_r($_SESSION["gameBasket"]);DEBUG
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Games - <?php echo $_SESSION["userName"] . "'s Basket"?></title>
		<link rel="stylesheet" type="text/css" href="http://users.aber.ac.uk/mwj7/cs25010/gameStyle.css"/>
		<script src="http://users.aber.ac.uk/mwj7/cs25010/gameScript.js" ></script>
	</head>
	
	<body>
		<div class="header">
			<div class="headerText">
				<h1>Your Shopping Basket</h1>
				<?php
					echo "<h3>" . $_SESSION["userName"] . "'s Session" . "<em>[This is not a real web shop do not enter any real card details]</em></h3>";
				?>
			</div>
			<div class="headerLinks">
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/gamesShop.php">Back to Games List</a>
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/index.php">Log out</a>
			</div>
		</div>
		<div class="mainContent">
			<div class="basketContent">
			<?php
				if($_SESSION["basketCount"]>0){
					$totalPrice = 0.00;
					//text for user information
					echo "<h3 id='info'>Below to the left is your basket contents and to the right is the checkout form.<br />Please fill out the form with your details to purchase your games.</h3>";
					//build html table headers
					echo "<div class='basketTable'>";
					echo "<table border='1'>";
					echo	'<tr>';
					echo		'<th>Title</th>';
					echo		'<th>Platform</th>';
					echo		'<th>Price(£)</th>';
					echo		'<th>Quantity</th>';
					echo	'</tr>';	
					//////////////////////////////////////////////////////////////////////////////////////////
					//Set up database connection
					$conn = pg_connect("host=db.dcs.aber.ac.uk port=5432 dbname=teaching user=csguest password=r3p41r3d");		
					if(!$conn){
						die('Could not connect to shop database: ' . pg_error());
					}
					//////////////////////////////////////////////////////////////////////////////////////////
					//Query all needed data from CSGames table within teaching database
					//Implode to turn array from key=>value format into (x,y,z) format
					$query = "SELECT title,platform,price,refnumber FROM CSGames WHERE refnumber IN (" . implode ( ', ', $_SESSION["gameBasket"]) . ") ORDER BY price ASC";
					$res = pg_query($conn, $query);
					//////////////////////////////////////////////////////////////////////////////////////////
					//Count the quantity of each item in the array
					$quantity = array_count_values($_SESSION["gameBasket"]);
					//Add HTML table rows from queried data
					while($currentRow = pg_fetch_row($res)){
						echo "<tr>";
						$fields = pg_num_fields($res)-1;
						for ($i=0;$i<$fields;$i++){
							echo "<td>" . $currentRow[$i] . "</td>";
							if($i==2){
								$totalPrice += ($currentRow[$i] * $quantity[$currentRow[$fields]]);
							}
						}
						echo "<td>" .  $quantity[$currentRow[$fields]] ."</td>";
						//remove from Basket button
						echo '<td><form action="basket.php" method="post"><input type="hidden" name="removeFromBasket" value="' . $currentRow[$fields] . '"><input type="submit" value="Remove From Basket"/></form></td>';
						echo "</tr>";
					}
					echo "</table>";
					echo '<h4>Total Quantity: '. $_SESSION["basketCount"] . '<br />Total Price: £'. $totalPrice . '</h4>';
					echo "</div>";
					//////////////////////////////////////////////////////////////////////////////////////////////
					//check out form
					echo "<div class='purchaseForm'>";
					echo "<div id='startOfCheckout'>";
					echo '<form name="checkOut" action="http://users.aber.ac.uk/mwj7/cs25010/basket.php" method="post" onsubmit="return isValidForm()">';
					//postal address
					echo 'Address Line 1:<br /><input type="text" class="formBox" required name="address1" placeholder="34 Gamer drive"/><br />';
					echo 'Address Line 2:<br /><input type="text" class="formBox" required name="address2" placeholder="Digit grove"/><br />';
					echo 'Town:<br /><input type="text" class="formBox" required name="town" placeholder="Hypertext-On-Wye"/><br />';
					echo 'Post Code:<br /><input type="text" class="formBox" required name="code" placeholder="NP44 3DX"/><br />';
					echo '</div>';
					echo '<div id="endOfCheckout">';
					//email
					echo 'Email Address:<br /><input type="text" class="formBox" required name="email" placeholder="xyz@mail.com"/><br />';
					//credit/debit card number
					echo 'Card Number:<br /><input type="text" class="formBox" required name="card" placeholder="xxxx-xxxx-xxxx-xxxx"/><br />';
					echo '<h4>Total Quantity: '. $_SESSION["basketCount"] . '<br />Total Price: £'. $totalPrice . '</h4>';
					echo '<input id="purchaseButton" type="submit" value="Check Out" />';
					echo '</div>';
					echo '</form>';	
					echo '</div>';
				}
				elseif(isset($_POST["email"])){
					echo '<h2>All done!<br />Your games have been purchased and a confirmation email has been sent to <strong>' .  $_POST["email"] . '</strong><br />Thanks for shopping with us ' .  $_SESSION["userName"] . ' :-)</h2>';
				}
				else{
					echo '<h2>You have no games in your Shopping Basket :-( <br />Go on back to our <a href="http://users.aber.ac.uk/mwj7/cs25010/gamesShop.php">Games List Page</a> to grab yourself a game. </h2>';
				}
			?>
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