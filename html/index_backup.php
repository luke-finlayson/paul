<!--<!DOCTYPE html>-->
	<head>
		<title>paul - not a tax collector</title>
		<link href="https://paulthecollector.ddns.net/images/favicon.png" rel="icon" type="image/x-icon" />
	</head>
	<body>
		<h1>paul - not a tax collector</h1>
		<?php
			$servername = "172.17.0.3";
			$username = "paul_user";
			$password = "Hppv50277";
			$dbname = "paul";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			$get_data = "SELECT description, cost, date FROM flat_costs";

			if(isset($_POST['description'])) {
				$de = $_POST["description"];
				$c = $_POST["cost"];
				$p = $_POST["purchaser"];

				$sql = "INSERT INTO flat_costs (description, date, purchaser, cost) VALUES (?, now(), ?, ?)";
				$query = $conn->prepare($sql);
				$query->bind_param("sss", $de, $p, $c);
				$query->execute();
			}
		?>
		<form action="index.php" method="post">
			Item: <input type="text" name="description"><br>
			Cost: <input type="text" name="cost"><br>
			Paid for by: <input type="text" name="purchaser">
			<input type="submit">
		</form>
		<br>

		<?php
			$result = $conn->query($get_data);

			while($row = $result->fetch_assoc()) {
				// Convert date to clearer format
				$sqlDate = strtotime($row["date"]);
				$date = date("M j", $sqlDate);
				echo $date . ", " .  $row["description"]. " $" . $row["cost"] . "<br>";
			}
		?>
	</body>
</html>
