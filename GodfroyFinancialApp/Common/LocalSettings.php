<?php
include_once("Models/ApplicationSetting.php");
include_once("DataAccess/DBManager.php");
include_once("DataAccess/DBGenericRepository.php");
include_once("DataAccess/Repositories/DBApplicationSettingRepository.php");

class LocalSettings
{
    // Singleton
    private static $instance;
    public static function GetInstance() : LocalSettings {
        if (empty(LocalSettings::$instance)) { LocalSettings::$instance = new LocalSettings(); }
        return LocalSettings::$instance;
    }

    // App Information
    public $publicUserCreationEnabled = false;
    public $adminUserCreationEnabled = true;

    // API Keys
    public static $MailChimpAPIKeySettingName = "MailChimpAPIKey";
    public $MailChimpAPIKey = "";
    public function IsMailChimpSetup() : bool {
        return !empty($this->MailChimpAPIKey);
    }

    private $dbManager;
    private $appSettingsRepo;

    private function __construct() {
        $this->dbManager = new DBManager();
        $this->appSettingsRepo = new DBApplicationSettingRepository($this->dbManager);
        $this->dbManager->connect();

        $this->LoadSettings();
    }


    public function LoadSettings() {
        $this->MailChimpAPIKey = $this->appSettingsRepo->getName(self::$MailChimpAPIKeySettingName);
    }
}

?>