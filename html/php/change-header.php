<?php
  include "globals.php";
  // Get current table
  $current_table = $tables[$_COOKIE["current_table"]];
  // Output current title
  echo $current_table->title;
?>
