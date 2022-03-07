<?php
	// Sets the user cookie to a given value
	function createcookie($value) {
		setcookie("user", $value, time() + (86400 * 30), "/");
		header("Refresh:0");
	}
	// Outputs a table item div populated with the given data
	function printtableitem($description, $date, $purchaser, $cost) {
		echo "<div class='table-headers table-item-container rounded-right grey'>";
		echo "<p class='bold table-item a grey-font'>$description</p>";
		echo "<p class='table-item b grey-font'>$date</p>";
		echo "<p class='table-item c grey-font'>$purchaser</p>";
		echo "<p class='bold table-item d grey-font'>$$cost</p>";
		echo "</div>";
	}

	// Parameters to user when conecting to database
	$servername = "172.17.0.3";
	$username = "paul_user";
	$password = "Hppv50277";
	$database = "paul";

	// Create connection to database
	$db = new mysqli($servername, $username, $password, $database);

	$cookie_name = "user";
	$cookie_value = "";

	// Sign user in with cookie if applicable
	if(isset($_POST["cameron"])) {
		// Create cookie for Cameron
		createcookie("Cameron");
	}
	if(isset($_POST["cole"])) {
		// Create cookie for Cole
		createcookie("Cole");
	}
	if(isset($_POST["luke"])) {
		// Create cookie for Luke
		createcookie("Luke");
	}
	if(isset($_POST["oscar"])) {
		// Create cookie for Oscar
		createcookie("Oscar");
	}

	// If site was requested via POST with data to create new purchase
	if(isset($_POST["description"]) && isset($_COOKIE["user"])) {
		// Get data from POST
		$description = $_POST["description"];
		$cost = $_POST["cost"];
		// Create SQL query
		$query = "INSERT INTO flat_costs (description, date, purchaser, cost) VALUES (?, now(), ?, ?)";
		$sql = $db->prepare($query);
		// Bind parameters to query
		$sql->bind_param("sss", $description, $_COOKIE["user"], $cost);
		// Execute query
		$sql->execute();
	}
?>
<html>
	<head>
		<title>paul - not a tax collector</title>
		<link href="https://paulthecollector.ddns.net/images/favicon.png" rel="icon" type="image/x-icon" />
		<link href="https://paulthecollector.ddns.net/css/styles.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="login-overlay" <?php if(isset($_COOKIE["user"])) {?> style="display:none" <?php } ?>>
			<h1 class="white-font">Select your name to begin</h1>
			<form class="login-options" action="index.php" method="post">
				<button type="submit" name="cameron" class="rounded-left">Cameron</button>
				<button type="submit" name="cole">Cole</button>
				<button type="submit" name="luke">Luke</button>
				<button type="submit" name="oscar" class="rounded-right">Oscar</button>
			</form>
		</div>
		<div class="nav-container">
			<div class="logo-container">
				<p class="logo-text blue-font">paul</p>
				<div class="logo-image">
					<img src="https://paulthecollector.ddns.net/images/ellipsis.png" alt="Menu" />
				</div>
			</div>
			<div class="nav-links">
				<div class="nav-link-container rounded-left" style="background: #3581B8;">
					<p class="nav-link white-font bold">Flat</p>
				</div>
				<div class="nav-link-container rounded-left">
					<p class="nav-link grey-font">Cameron, Cole & Luke</p>
				</div>
				<div class="nav-link-container rounded-left">
					<p class="nav-link grey-font">Cole & Luke</p>
				</div>
			</div>
			<p class="table-header sign-in-message light-grey-font" <?php if(!isset($_COOKIE["user"])) {?> style="display:none" <?php } ?>>
				Signed in as <?php echo $_COOKIE["user"] ?>
			</p>
		</div>
		<div class="main-container">
			<div class="main-header-section">
				<h1 class="blue-font">Flat Purchases</h1>
				<button class="blue-font">+</button>
			</div>
			<div class="table-container">
				<div class="table-headers">
					<p class="table-header a light-grey-font">Description</p>
					<p class="table-header b light-grey-font">Date</p>
					<p class="table-header c light-grey-font">Purchased By</p>
					<p class="table-header d light-grey-font">Cost</p>
				</div>

				<?php
					// Create the sql query
					$get_data = "SELECT description, date, purchaser, cost FROM flat_costs";
					// Execute the query
					$result = $db->query($get_data);
					// Create total spent counter
					$total = 0;
					// Process each table row
					while($row = $result->fetch_assoc()) {
						// Convert date format
						$sqlDate = strtotime($row["date"]);
						$date = date("M j", $sqlDate);
						// Increase counter
						$total += $row["cost"];
						// Print row to html
						printtableitem($row["description"], $date, $row["purchaser"], $row["cost"]);
					}
					echo "<div class='table-headers table-item-container rounded-right blue'>";
					echo "<p class='bold table-item a white-font'>Total Spent</p>";
					echo "<p class='bold table-item d white-font'>$$total</p>";
					echo "</div>";
				?>
			</div>
		</div>
	</body>
</html>
