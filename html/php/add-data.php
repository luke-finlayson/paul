<?php
  include "globals.php";

  // Get current table
  $current_table = $tables[$_COOKIE["current_table"]];

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

  // Create SQL Query to insert data into table
  $sql = "INSERT INTO {$current_table->table_name} (description, date, purchaser, cost) VALUES (:description, now(), :purchaser, :cost)";
  $query = $pdo->prepare($sql);
  $query->bindParam(':description', $description);
  $query->bindParam(':purchaser', $purchaser);
  $query->bindParam(':cost', $cost);

  // Get data to insert
  $description = $_GET['description'];
  $cost = $_GET['cost'];
  $purchaser = $_COOKIE["user"];

  // Execute Query
  $query->execute();
?>
