<?php

$huntTitle = $_POST['huntTitle'];
$huntLocations = $_POST['huntLocations'];
$huntClues = $_POST['huntClues'];

// print_r($huntClues);
//
$servername = "localhost";
$dbname = "salhacks_main";
$username = "salhacks_main";
$password = "eTZJ]5#riQ.P5kHH";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=3306", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO mlane_hunts (huntTitle, huntLocations, huntClues) VALUES (?, ?, ?)");
    $stmt->execute([$huntTitle, $huntLocations, $huntClues]);

} catch(PDOException $e) {
  echo $sql . '<br>' . $e->getMessage();
}

$conn = null;

header("Location: index.php");
