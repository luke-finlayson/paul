<?php

  include "globals.php";

  // Create connection to database
  $db = parse_url(getenv("DATABASE_URL"));

  $pdo = new PDO("pgsql:" . sprintf(
    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
    $db["host"],
    $db["port"],
    $db["user"],
    $db["pass"],
    ltrim($db["path"], "/")
  ));

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode JSON data recieved from post
    $json = $_POST["json"];
    $decoded = json_decode(file_get_contents('php://input'), true);

    // Only add new data if there is data to add
    if($decoded["description"] != "") {
      // Create SQL Query to insert data into table
      $sql = "INSERT INTO flat_costs (description, date, purchaser, cost) VALUES (:description, now(), :purchaser, :cost)";
      $query = $pdo->prepare($sql);
      $query->bindParam(':description', $description);
      $query->bindParam(':purchaser', $purchaser);
      $query->bindParam(':cost', $cost);

      // Get data to insert
      $description = $decoded["description"];
      $cost = $decoded["cost"];
      $purchaser = $decoded["user"];

      // Execute Query
      $query->execute();
    }

    // Query new table data from databse
    $sql = "SELECT * FROM flat_costs";
    $query = $pdo->query($sql);

    $resultArray = array();

    // Process each table row
    while($row = $query->fetchObject()) {
      array_push($resultArray, $row);
    }

    echo json_encode($resultArray);
  }
  else {
    header("Location: https://paulthecollector.herokuapp.com");
		exit();
  }

?>
