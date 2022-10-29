<!-- paul the collector © 2022
		 	- A web app developed by Luke Finlayson -->
<?php
	session_start();

	include "php/globals.php";

	// Create current table id cookie
	setcookie("current_table", "0", time() + 3600, "/");
	$current_table = $tables[$_COOKIE["current_table"]];

	if( $_POST ) {
		$_SESSION["POST"] = $_POST;
		// Redirect and stop script immediatly
		header("Location: https://paulthecollector.herokuapp.com");
		exit();
	}
	if( isset($_SESSION["POST"]) ) {
		$_POST = $_SESSION["POST"];
		unset($_SESSION["POST"]);

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
	}
?>
<html>
	<head>
		<title>paul the collector</title>
		<link href="images/favicon.png" rel="icon" type="image/x-icon" />
		<link href="css/styles.css" rel="stylesheet" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="nav-container">
			<div class="logo-container">
				<p class="logo-text accent-color-font">paul</p>
				<div class="logo-image">
					<?php echo file_get_contents("images/ellipsis.svg"); ?>
				</div>
			</div>
			<div class="nav-links" action="index.php" method="post" id="nav">
				<?php
					foreach ($tables as $table) {
						if ($table->id == 0) {
							echo "<button class='nav-link-container blue rounded-left' name='table{$table->id}'>";
							echo "<p class='nav-link header-font bold'>{$table->nav_label}</p></button>";
						} else {
							echo "<button onclick='changeTable({$table->id})' class='nav-link-container nav-link-dormant rounded-left' name='table{$table->id}'>";
							echo "<p class='nav-link grey-font'>{$table->nav_label}</p></button>";
						}
					}
					unset($table);
				?>
			</div>
			<p class="table-header sign-in-message dark-grey-font" <?php if(!isset($_COOKIE["user"])) {?> style="display:none" <?php } ?>>
				Signed in as <?php echo $_COOKIE["user"] ?>. <a onclick="logout()" class="accent-color-font">Log out.</a>
			</p>
		</div>
		<div class="main-container">
			<div class="main-header-section">
				<h1 class="header-font" id="header"><?php echo $tables[0]->title; ?></h1>
				<button id="add-button" onclick="showForm()">
					<?php echo file_get_contents("images/plus.svg"); ?>
				</button>
				<div id="add-purchase-form" class="grey" onkeypress="addData(event)">
					<input type="text" name="description" placeholder="Item" class="textbox" id="description" maxLength="150" required>
					<input type="number" step=".01" name="cost" placeholder="Cost" class="textbox cost" id="cost" min="0.01" required>
					<button class="close-button" onclick="hideForm()" type="button">
						<?php echo file_get_contents("images/plus.svg"); ?>
					</button>
				</div>
			</div>
			<div class="table-container" id="table-container">
				<div class="table-headers top-headers">
					<p class="table-header a header-font">Description</p>
					<p class="table-header b header-font">Date</p>
					<p class="table-header c header-font">Purchased By</p>
					<p class="table-header d header-font">Cost</p>
				</div>
				<div id="data"></div>
			</div>
		</div>
		<div class="login-overlay" <?php if(isset($_COOKIE["user"])) {?> style="display:none" <?php } ?>>
			<h1 class="header-font">Let's Sign In.</h1>
			<form class="login-options" action="index.php" method="post">
				<button type="submit" name="cameron" class="accent-color-font right" style="top:200px">Cameron</button>
				<button type="submit" name="cole" class="accent-color-font left" style="top:290px">Cole</button>
				<button type="submit" name="luke" class="accent-color-font right" style="top:380px">Luke</button>
				<button type="submit" name="oscar" class="accent-color-font left" style="top:470px">Oscar</button>
			</form>
		</div>
		<script>
			var visible = false;

			// Clear textboxs
			function clearFields() {
				document.getElementById("description").value = '';
				document.getElementById("cost").value = '';
			}

			function showForm() {
				document.getElementById("add-purchase-form").style.display = "flex";
				document.getElementById("add-button").style.display = "none";
				document.getElementById("description").focus();
				visible = true;
			}

			function hideForm() {
				if(visible) {
					clearFields();
					document.getElementById("add-purchase-form").style.display = "none";
					document.getElementById("add-button").style.display = "flex";
					visible = false;
				}
			}

			$(document).ready(function() {
				// Start loading database data once page loads
				loaddata();
			})

			// Load database data every 2 seconds
			function loaddata() {
				$("#data").load("php/get-data.php");
				setTimeout(loaddata, 2000);
			}

			// Deletes the user cookie to log the user out
			function logout() {
				// Clear the user cookie
				document.cookie = "user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
				// Reload the page
				document.location.reload(true);
			}

			// Sends a AJAX request to delete a row from the database
			function deleteData(id) {
				// Create http request
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
		      if (this.readyState == 4 && this.status == 200) {
						$("#data").load("php/get-data.php");
		      }
		    };
				request.open("GET", "php/delete-data.php?id="+id,true);
				request.send();
			}

			// Add data to database via AJAX request
			function addData(event) {
				if(visible && event.keyCode == 13) {
					// Get data
					var description = document.getElementById("description").value;
					var cost = document.getElementById("cost").value;

					// Create http request
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
			      if (this.readyState == 4 && this.status == 200) {
							$("#data").load("php/get-data.php");
			      }
			    };
					request.open("GET", "php/add-data.php?description="+description+"&cost="+cost,true);
					request.send();

					// Hide the form
					hideForm();
				}
			}

			function changeTable(table) {
				// Create http request
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						// Update nav
						document.getElementById("nav").innerHTML = this.response;
						// Update title
						$("#header").load("php/change-header.php");
						$("#data").load("php/get-data.php");
					}
				};
				request.open("GET", "php/change-table.php?table="+table,true);
				request.send();

				request = new XMLHttpRequest();

			}

			function test() {
				alert("<?php echo $current_table->title; ?>")
			}
		</script>
	</body>
</html>
