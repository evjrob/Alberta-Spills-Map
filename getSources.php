<?php
require('config.inc.php');

$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
//By using PDO and prepare, everything is automagically escaped, not that it's necessary here
$stmt = $db->prepare("SELECT `Source` FROM `Spills` GROUP BY `Source` ORDER BY `Spills`.`Source` ASC LIMIT 100");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_NUM);

echo header('Content-type: application/json');
echo json_encode($result);

?>