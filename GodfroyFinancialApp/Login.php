<?php
include_once("Common/IncludeAll.php");

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$dbManager->connect();

?>