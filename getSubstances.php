<?php
require('config.inc.php');

$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
//By using PDO and prepare, everything is automagically escaped
$stmt = $db->prepare("SELECT `Substance Released` FROM `Spills` GROUP BY `Substance Released` ORDER BY `Spills`.`Substance Released` ASC LIMIT 100");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_NUM);

echo header('Content-type: application/json');
echo json_encode($result);

?>