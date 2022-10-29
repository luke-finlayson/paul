<?php
  include "globals.php";

  // Get current table
  $current_table = $tables[$_COOKIE["current_table"]];

  $id = intval($_GET['id']);

  echo "<p>Trying... {$_GET['id']}, {$id}</p>";

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

  // Check connection
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $sql="DELETE FROM {$current_table->table_name} WHERE id='".$id."'";

  if($pdo->query($sql) === TRUE) {
    echo "Success";
  }
?>
