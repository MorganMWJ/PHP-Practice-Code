<?php
	// Start the session
	session_start();

	//Save the users name in the session variable
	if(isset($_POST["name"])){
		$_SESSION["userName"] = $_POST["name"];
		//Set default/starting search criteria
		$_SESSION["priceCriteria"]="lowest";
		$_SESSION["platformCriteria"]="all";
		$_SESSION["priceLimit"]=0.00;
		$_SESSION["lowerOrHigher"]="higher";
		//start counting Basket items from zero
		$_SESSION["basketCount"] = 0;
	}
	
	//Overwrite with any previous search criteria
	if(isset($_POST["orderByPrice"])){
		$_SESSION["priceCriteria"] = $_POST["orderByPrice"];
	}
	if(isset($_POST["orderByPlatform"])){
		$_SESSION["platformCriteria"] = $_POST["orderByPlatform"];
	}
	if(isset($_POST["cutOff"])){
		if($_POST["cutOff"] != ""){
			$_SESSION["priceLimit"] = $_POST["cutOff"];
		}
	}
	if(isset($_POST["lowerOrHigher"])){
		$_SESSION["lowerOrHigher"] = $_POST["lowerOrHigher"];
	}
	if($_SESSION["lowerOrHigher"]=="higher"){
		$_SESSION["isHigher"] = true;
	}
	else{
		$_SESSION["isHigher"] = false;
	}
	
	//If the user just added an item to their Basket then increment Basket count
	if(isset($_POST["addToBasket"])){
		$_SESSION["basketCount"] += 1;
		if($_SESSION["basketCount"]==1){
		//create array to store selected game(s)
		$_SESSION["gameBasket"] = array();
		}
		//thereafter append to that array
		array_push($_SESSION["gameBasket"],$_POST["addToBasket"]);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Games - Games List Page</title>
		<link rel="stylesheet" type="text/css" href="http://users.aber.ac.uk/mwj7/cs25010/gameStyle.css"/>
		<script src="http://users.aber.ac.uk/mwj7/cs25010/gameScript.js" ></script>
	</head>

	<body>
		<div class="header">
			<div class="headerText">
				<h1>Games In Stock</h1>
				<?php
					echo "<h3>" . $_SESSION["userName"] . "'s Session" . "</h3>";
				?>
			</div>
			<div class="headerLinks">
				<a class="navLink" id="basketLink" href="http://users.aber.ac.uk/mwj7/cs25010/basket.php">Shopping Basket (<?php echo $_SESSION["basketCount"] ?> items)</a>
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/about.php">About site</a>
				<a class="navLink" href="http://users.aber.ac.uk/mwj7/cs25010/index.php">Log out</a>
			</div>
		</div>
		<div class="mainContent">
			<form id="searchForm" name="search" action="gamesShop.php" method="post" onsubmit="return validatePrice();">
				Price Cut-Off:
				<input type="text" name="cutOff" placeholder=<?php echo '"Price Limit: £' . $_SESSION["priceLimit"] . '"'; ?> />
				<input type="radio" name="lowerOrHigher" value="lower" <?php if($_SESSION["lowerOrHigher"] == "lower") echo 'checked' ?> />Lesser
				<input type="radio" name="lowerOrHigher" value="higher" <?php if($_SESSION["lowerOrHigher"] == "higher") echo 'checked' ?> />Greater
				| Price Order:
				<select name="orderByPrice">
					<option value="lowest"  <?php if ($_SESSION["priceCriteria"] == "lowest")	  echo 'selected="selected" '; ?>>Lowest - Highest</option>
					<option value="highest" <?php if ($_SESSION["priceCriteria"] == "highest")  echo 'selected="selected" '; ?>>Highest - Lowest</option>
				</select>
				| Platform:
				<select name="orderByPlatform">
					<option value="all"  <?php if ($_SESSION["platformCriteria"] == "all")  echo 'selected="selected" '; ?>>Display All Platforms</option>
					<option value="pc"   <?php if ($_SESSION["platformCriteria"] == "pc")   echo 'selected="selected" '; ?>>PC</option>
					<option value="ps3"  <?php if ($_SESSION["platformCriteria"] == "ps3")  echo 'selected="selected" '; ?>>Playstation 3</option>
					<option value="xbox" <?php if ($_SESSION["platformCriteria"] == "xbox") echo 'selected="selected" '; ?>>Xbox 360</option>
					<option value="wii"  <?php if ($_SESSION["platformCriteria"] == "wii")  echo 'selected="selected" '; ?>>Nintendo Wii</option>
					<option value="3ds"  <?php if ($_SESSION["platformCriteria"] == "3ds")  echo 'selected="selected" '; ?>>Nintendo 3DS</option>
					<option value="sega" <?php if ($_SESSION["platformCriteria"] == "sega") echo 'selected="selected" '; ?>>Sega CD</option>
				</select>
				<input type="submit" value="Display" />
			</form>
			<div class="mainTable">
				<table id="listTable" border='1'>
					<tr>
						<th>Title</th>
						<th>Platform</th>
						<th>Game Description</th>
						<th>Price(£)</th>
					</tr>
					<div class="tableRows">
					<?php
						//Set up database connection
						$conn = pg_connect("host=db.dcs.aber.ac.uk port=5432 dbname=teaching user=csguest password=r3p41r3d");		
						if(!$conn){
							die('Could not connect to shop database: ' . pg_error());
						}
						//////////////////////////////////////////////////////////////////////////////////////////
						//Query all needed data from CSGames table within teaching database
						//decide what games to query dependent on user's previous search choice.
						if((isset($_SESSION["priceCriteria"])) || (isset($_SESSION["platformCriteria"]))){
							$priceOptions = ["lowest"=>"ASC","highest"=>"DESC"];
							$platformOptions = ["pc"=>"PC","ps3"=>"Playstation 3","xbox"=>"Xbox 360","wii"=>"Nintendo Wii","3ds"=>"Nintendo 3DS","sega"=>"Sega CD"];
							if($_SESSION["platformCriteria"]=="all"){
								$userQuery = "SELECT title,platform,description,price,refnumber FROM CSGames ORDER BY price " . $priceOptions[$_SESSION["priceCriteria"]];
								$res = pg_query($conn, $userQuery);
							}
							else{
								foreach ($priceOptions as $priceOrder => $priceQuery){
									foreach ($platformOptions as $platformChoice => $platformQuery){
										if(($_SESSION["platformCriteria"] == $platformChoice) && ($_SESSION["priceCriteria"] == $priceOrder)){
											$userQuery = "SELECT title,platform,description,price,refnumber FROM CSGames WHERE platform='" . $platformQuery . "' ORDER BY price " . $priceQuery;
											$res = pg_query($conn, $userQuery);
										}
									}
								}
							}
						}
						//If no previous search choice then query default
						else{
							$res = pg_query($conn, "SELECT title,platform,description,price,refnumber FROM CSGames ORDER BY price ASC");
						}
						//////////////////////////////////////////////////////////////////////////////////////////
						//Add HTML table rows from queried data
						while($currentRow = pg_fetch_row($res)){
							//Only display rows within the price limit
							if((($currentRow[3] >= $_SESSION["priceLimit"]) == $_SESSION["isHigher"])){
								echo "<tr>";
								$fields = pg_num_fields($res)-1;
								for ($i=0;$i<$fields;$i++){
									echo "<td>" . $currentRow[$i] . "</td>";
								}
								//last column of every row is an add to Basket button
								echo '<td><form action="gamesShop.php" method="post"><input type="hidden" name="addToBasket" value="' . $currentRow[$fields] . '"><input class="addBasketButton" type="submit" value="Add to Basket"/></form></td>';
								echo "</tr>";
							}
						}
					?>
					</div>
				</table>
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
				<!--Below to external code used for the validation images within the footer -->
				<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://www.w3.org/Icons/valid-css" alt="Valid CSS!" height="31" width="88" /></a>  
			</p>				
		</div>
	</body>
</html>