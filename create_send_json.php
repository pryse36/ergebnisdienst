<?php

//$file = 'data.json';
//header("Content-type: application/json");
//header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
//header('Content-Length: ' . filesize($file));
//readfile($file);
// Anpassen wenn Produktiv genommen:
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "djk_ergebnisdienst";
// Fehlermeldung ausgeben falls vergessen
if ($servername == 'localhost') {
    //  echo "Achtung: DB Servername noch auf localhost" . "<br>";
}

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM liga_tabelle";

$result = $conn->query($sql);

$myArray = array();


while ($row = mysqli_fetch_assoc($result)) {
    $myArray[] = $row;
}

echo json_encode($myArray);
