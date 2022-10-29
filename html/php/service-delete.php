<?php

  include "globals.php";

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create connection to database
    $db = new mysqli($servername, $username, $password, $database);

    // Decode JSON data recieved from post
    $json = $_POST["json"];
    $decoded = json_decode(file_get_contents('php://input'), true);

    // Delete row that matchs given id
    $row = $decoded["id"];

    $delete_data = "DELETE FROM flat_costs WHERE id=?";
    $query = $db->prepare($delete_data);
    $query->bind_param("s", $row);
    // Execute Query
    $query->execute();

    // Query new table data from databse
    $get_data = "SELECT * FROM flat_costs";
    $result = $db->query($get_data);

    $resultArray = array();

    // Process each table row
    while($row = $result->fetch_object()) {
      array_push($resultArray, $row);
    }

    echo json_encode($resultArray);

    $db->close();
  }
  else {
    header("Location: https://paulthecollector.herokuapp.com");
		exit();
  }

?>
