<?php
date_default_timezone_set(@date_default_timezone_get());

$guisettingsFile = dirname(__FILE__) . '/../config/config.php';
if (file_exists($guisettingsFile)) {
	require_once($guisettingsFile);
} else {
	error_log('PlexWatchWeb :: Config file not found.');
	echo "Config file not found";
	exit;
}

if (!isset($_POST['id'])) {
	error_log('PlexWatchWeb :: POST parameter "id" not found.');
	echo "id field is required.";
	exit;
}

if (isset($_POST['table']) &&
		($_POST['table'] === 'grouped' || $_POST['table'] === 'processed')) {
	$plexWatchDbTable = $_POST['table'];
} else {
	$plexWatchDbTable = dbTable();
}

$database = dbconnect();
$query = "SELECT xml FROM :table WHERE id = :id";
$results = getResults($database, $query, [
		'table'=>$plexWatchDbTable,
		'id'=>$_POST['id']
	]);
$xml = $results->fetchColumn();
$xmlfield = simplexml_load_string($xml);
printStreamDetails($xmlfield);
?>