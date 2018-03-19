<?php
// Include important pages here
// ================================================================================
$supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
date_default_timezone_set("America/Toronto");

// ========= ===== Settings ===============
include_once("LocalSettings.php");

// ================ Models ================
include_once("Models/User.php");
include_once("Models/Testimony.php");
include_once("Models/NewsletterSubscription.php");

// ================ Database ==============
include_once("DataAccess/DBManager.php");
include_once("DataAccess/DBGenericRepository.php");
include_once("DataAccess/Repositories/DBUserRepository.php");
include_once("DataAccess/Repositories/DBTestimonyRepository.php");
include_once("DataAccess/Repositories/DBNewsletterSubscriptionRepository.php");

?>