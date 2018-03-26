<?php
// Include important pages here
// ================================================================================
if (empty($pageTitle)) {
    $pageTitle = "";
}

//$supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
date_default_timezone_set("America/Toronto");

// ========= ===== Pre-Framework Settings ===============
include_once("DatabaseSettings.php");

// ================ Models ================
include_once("Models/User.php");
include_once("Models/Testimony.php");
include_once("Models/NewsletterSubscription.php");
include_once("Models/ApplicationSetting.php");

// ================ Database ==============
include_once("DataAccess/DBManager.php");
include_once("DataAccess/DBGenericRepository.php");
include_once("DataAccess/Repositories/DBUserRepository.php");
include_once("DataAccess/Repositories/DBTestimonyRepository.php");
include_once("DataAccess/Repositories/DBNewsletterSubscriptionRepository.php");
include_once("DataAccess/Repositories/DBApplicationSettingRepository.php");

// ========= ===== Post-Framework Settings ===============
include_once("LocalSettings.php");

// ================ Services ==============
include_once("Services/AuthenticationService.php");
include_once("Services/UserCreationService.php");

// ================================================================================
// Start the Session in the Header since the header is included in all the pages
session_start();
?>