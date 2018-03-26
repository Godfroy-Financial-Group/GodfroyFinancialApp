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
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // DB Information
    public static $db_dbEngine = "mysql";
    public static $db_Host = "localhost";
    public static $db_Username = "root";
    public static $db_Password = "password";
    public static $db_dbName = "GodfroyFinancialGroup";
    public static $db_Port = 3306;

    // App Information
    public $publicUserCreationEnabled = false;
    public $adminUserCreationEnabled = true;

    // API Keys
    public $MailChimpAPIKey = "";
    public $MailChimpListID = "";
    public function IsMailChimpSetup() :bool {
        return !empty($this->MailChimpAPIKey) && !empty($this->MailChimpListID);
    }

    private $dbManager;
    public $appSettingsRepo;

    public function __construct() {
        $this->dbManager = new DBManager();
        $this->appSettingsRepo = new DBApplicationSettingRepository($this->dbManager);
    }
}

?>