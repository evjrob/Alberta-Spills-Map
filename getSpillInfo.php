<?php

$incidentNumber = $_POST['incidentnumber'];

require('config.inc.php');
$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
//By using PDO and prepare, everything is automagically escaped
$stmt = $db->prepare("SELECT * FROM `Spills` WHERE `IncidentNumber` = :incidentNumber");
$stmt->bindValue(':incidentNumber', strval($incidentNumber), PDO::PARAM_STR);
/*** execute the prepared statement ***/
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo header('Content-type: application/json');
echo json_encode($result);

?>