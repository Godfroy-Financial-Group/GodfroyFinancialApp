<?php
include_once("Common/IncludeAll.php");

$dbManager = new DBManager();
$dbManager->connect();

$userRepo = new DBUserRepository($dbManager);
//$date = date('Y-m-d H:i:s');
//$user = new User(null, "killerrin", User::HashPassword("password"), "andrew.godfroy@outlook.com", $date, User::HashPassword("password".$date));
//$userRepo->insert($user);


//echo "Hello World!";

?>