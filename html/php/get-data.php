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

  // Create and execute SQL Query
  $sql = "SELECT * FROM {$current_table->table_name}";
  $query = $pdo->query($sql);

  // Get all purchases
  $purchases = $query->fetchAll(PDO::FETCH_ASSOC);

  // Create total spent counter
  $total = 0;

  if ($purchases) {
    foreach ($purchases as $purchase) {
      // Convert date format
      $sqlDate = strtotime($purchase["date"]);
      $date = date("M j", $sqlDate);
      // Increase counter
      $total += $purchase["cost"];
      // Print row to html
      printtableitem($purchase["id"], $purchase["description"], $date, $purchase["purchaser"], $purchase["cost"], true);
    }
  }

  echo "<div class='table-headers total table-item-container rounded-right table-footer'>";
  echo "<p class='bold table-item a accent-color-font'>Total Spent This Month</p>";
  echo "<p class='bold table-item d accent-color-font'>$$total</p>";
  echo "</div>";

  echo "<div class='table-headers'><p class='history-header a header-font'>History</p></div>";

  // Create and execute SQL Query
  $sql = "SELECT * FROM {$current_table->table_name}_history";
  $query = $pdo->query($sql);

  // Get all purchases
  $purchases = $query->fetchAll(PDO::FETCH_ASSOC);

  if ($purchases) {
    foreach ($purchases as $purchase) {
      // Convert date format
      $sqlDate = strtotime($purchase["date"]);
      $date = date("M j", $sqlDate);
      // Print row to html
      printtableitem($purchase["id"], $purchase["description"], $date, $purchase["purchaser"], $purchase["cost"], true);
    }
  }

?>
