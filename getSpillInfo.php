<?php
require('config.inc.php');

$atsLocation = $_POST['Location'];
$currentlicensee = $_POST['currentLicensee'];
$currentsubstance = $_POST['currentSubstance'];
$currentsource = $_POST['currentSource'];
$currentfailure = $_POST['currentFailure'];
$yearmin = $_POST['yearMin'];
$yearmax = $_POST['yearMax'];
$volumemin = $_POST['volumeMin'];
$volumemax = $_POST['volumeMax'];
$injurymin = $_POST['injuryMin'];
$injurymax = $_POST['injuryMax'];
$fatalitymin = $_POST['fatalityMin'];
$fatalitymax = $_POST['fatalityMax'];

// Fix the years to go from start of first year to end of the last.
$datemin = $yearmin."-01-01";
$datemax = $yearmax."-12-31";

//By using PDO and prepare, everything is automagically escaped
$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

//Start building the statement with the base of the query
$stmtString = "SELECT * FROM `Spills` WHERE (`Location` = :ATSLocation AND (`IncidentDate` BETWEEN :dateMin AND :dateMax) AND (`Volume Released` BETWEEN :volumeMin AND :volumeMax) AND (`InjuryCount` BETWEEN :injuryMin AND :injuryMax) AND (`FatalityCount` BETWEEN :fatalityMin AND :fatalityMax)";

//Add in the filters if they're set
if ($currentlicensee !== "All") { 
    $stmtString .= " AND `LicenseeName` = :licensee";
}
if ($currentsubstance !== "All") {
    $stmtString .= " AND `Substance Released` = :substance";
}
if ($currentsource !== "All") {
    $stmtString .= " AND `Source` = :source";
}
if ($currentfailure !== "All") {
    $stmtString .= " AND `FailureType` = :failure";
}

//Finish the statement with the sorting part
$stmtString .= ") ORDER BY `IncidentDate` DESC";

//Bind all of the parameters
$stmt = $db->prepare($stmtString);
if (strpos($stmtString,':licensee') !== false) {
    $stmt->bindValue(':licensee', strval($currentlicensee), PDO::PARAM_STR);
}
if (strpos($stmtString,':source') !== false) {
    $stmt->bindValue(':source', strval($currentsource), PDO::PARAM_STR);
}
if (strpos($stmtString,':substance') !== false) {
    $stmt->bindValue(':substance', strval($currentsubstance), PDO::PARAM_STR);
}
if (strpos($stmtString,':failure') !== false) {
    $stmt->bindValue(':failure', strval($currentfailure), PDO::PARAM_STR);
}
$stmt->bindValue(':dateMin', strval($datemin), PDO::PARAM_STR);
$stmt->bindValue(':dateMax', strval($datemax), PDO::PARAM_STR);
$stmt->bindValue(':volumeMin', strval($volumemin), PDO::PARAM_STR);
$stmt->bindValue(':volumeMax', strval($volumemax), PDO::PARAM_STR);
$stmt->bindValue(':ATSLocation', strval($atsLocation), PDO::PARAM_STR);
$stmt->bindValue(':injuryMin', strval($injurymin), PDO::PARAM_STR);
$stmt->bindValue(':injuryMax', strval($injurymax), PDO::PARAM_STR);
$stmt->bindValue(':fatalityMin', strval($fatalitymin), PDO::PARAM_STR);
$stmt->bindValue(':fatalityMax', strval($fatalitymax), PDO::PARAM_STR);
$stmt->execute();

//Get the results of the query
$result;
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo header('Content-type: application/json');
echo json_encode($result);

?>