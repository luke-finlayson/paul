<?php
class Table {
  // Properties
  public $title;
  public $nav_label;
  public $table_name;
  public $id;
}
// Create table for whole flat
$table1 = new Table();
$table1->title = "Flat Purchases";
$table1->nav_label = "Flat";
$table1->table_name = "flat_costs";
$table1->id = 0;

// Create table for Cole & Luke
$table2 = new Table();
$table2->title = "Cole & Luke's Purchases";
$table2->nav_label = "Cole & Luke";
$table2->table_name = "cole_luke";
$table2->id = 1;

// Create table for Cameron, Cole & Luke
$table3 = new Table();
$table3->title = "Cameron, Cole & Luke's Purchases";
$table3->nav_label = "Cameron, Cole & Luke";
$table3->table_name = "cameron_cole_luke";
$table3->id = 2;

$tables = array($table1, $table2, $table3);

$cookie_name = "user";

// Sets the user cookie to a given value
function createcookie($value) {
  setcookie("user", $value, time() + (86400 * 30), "/");
  header("Refresh:0");
}

// Outputs a table item div populated with the given data
function printtableitem($id, $description, $date, $purchaser, $cost, $current) {
  echo "<div class='table-headers table-item-container rounded-right grey'>";
  echo "<p class='table-item a table-item-font'>$description</p>";
  echo "<p class='table-item b table-item-font'>$date</p>";
  echo "<p class='table-item c table-item-font'>$purchaser</p>";
  echo "<p class='table-item d table-item-font'>$$cost</p>";
  if ($current && $purchaser == $_COOKIE["user"]) {
    echo "<div class='delete-form'><button class='delete' onclick='deleteData({$id})'>";
    echo file_get_contents("../images/trash.svg");
    echo "</button></div>";
  }
  echo "</div>";
}
?>
