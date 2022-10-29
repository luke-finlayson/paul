<?php
  include "globals.php";

  $t = intval($_GET['table']);

  $current_table = $tables[$t];
  setcookie("current_table", $t, time() + 3600, "/");

  foreach ($tables as $table) {
    if ($table->id == $current_table->id) {
      echo "<button class='nav-link-container blue rounded-left' name='table{$table->id}'>";
      echo "<p class='nav-link header-font bold'>{$table->nav_label}</p></button>";
    } else {
      echo "<button onclick='changeTable({$table->id})' class='nav-link-container nav-link-dormant rounded-left' name='table{$table->id}'>";
      echo "<p class='nav-link grey-font'>{$table->nav_label}</p></button>";
    }
  }
  unset($table);
?>
