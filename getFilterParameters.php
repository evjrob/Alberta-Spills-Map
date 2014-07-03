<?php
require('config.inc.php');


//By using PDO and prepare, everything is automagically escaped
$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

$volumeStmt = $db->prepare("SELECT MAX(`Volume Released`) FROM `Spills`");
$volumeStmt->execute();
$volumeResult = $volumeStmt->fetchAll(PDO::FETCH_COLUMN, 0);

$dateStmt = $db->prepare("SELECT EXTRACT(YEAR FROM MIN(`IncidentDate`)), EXTRACT(YEAR FROM MAX(`IncidentDate`)) FROM `Spills`");
$dateStmt->execute();
$dateResult = $dateStmt->fetch(PDO::FETCH_NUM);

$licenseeStmt = $db->prepare("SELECT `LicenseeName` FROM `Spills` GROUP BY `LicenseeName` ORDER BY `Spills`.`LicenseeName` ASC LIMIT 2000");
$licenseeStmt->execute();
$licenseeResult = $licenseeStmt->fetchAll(PDO::FETCH_COLUMN, 0);

$sourceStmt = $db->prepare("SELECT `Source` FROM `Spills` GROUP BY `Source` ORDER BY `Spills`.`Source` ASC LIMIT 100");
$sourceStmt->execute();
$sourceResult = $sourceStmt->fetchAll(PDO::FETCH_COLUMN, 0);

$substanceStmt = $db->prepare("SELECT `Substance Released` FROM `Spills` GROUP BY `Substance Released` ORDER BY `Spills`.`Substance Released` ASC LIMIT 100");
$substanceStmt->execute();
$substanceResult = $substanceStmt->fetchAll(PDO::FETCH_COLUMN, 0);

$failureStmt = $db->prepare("SELECT `FailureType` FROM `Spills` GROUP BY `FailureType` ORDER BY `Spills`.`FailureType` ASC LIMIT 150");
$failureStmt->execute();
$failureResult = $failureStmt->fetchAll(PDO::FETCH_COLUMN, 0);

$injuryStmt = $db->prepare("SELECT MAX(`InjuryCount`) FROM `Spills`");
$injuryStmt->execute();
$injuryResult = $injuryStmt->fetch(PDO::FETCH_NUM);

$fatalityStmt = $db->prepare("SELECT MAX(`FatalityCount`) FROM `Spills`");
$fatalityStmt->execute();
$fatalityResult = $fatalityStmt->fetch(PDO::FETCH_NUM);

$filterArray = array(

	'dates' => $dateResult,
	'volume' => $volumeResult,
	'injuryCount' => $injuryResult,
	'fatalityCount' => $fatalityResult,
	'licensees' => $licenseeResult,
	'sources' => $sourceResult,
	'substances' => $substanceResult,
	'failureTypes' => $failureResult,
);

echo header('Content-type: application/json');
echo json_encode($filterArray);
	
?>