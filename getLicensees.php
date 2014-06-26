<?php
require('config.inc.php');

$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
//By using PDO and prepare, everything is automagically escaped
$stmt = $db->prepare("SELECT `LicenseeName` FROM `Spills` GROUP BY `LicenseeName` ORDER BY `Spills`.`LicenseeName` ASC LIMIT 2000");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

echo header('Content-type: application/json');
echo json_encode($result);

?>