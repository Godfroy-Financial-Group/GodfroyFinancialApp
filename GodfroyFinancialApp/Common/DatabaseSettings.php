<?php
include_once("Models/ApplicationSetting.php");
include_once("DataAccess/DBManager.php");
include_once("DataAccess/DBGenericRepository.php");
include_once("DataAccess/Repositories/DBApplicationSettingRepository.php");

class DatabaseSettings
{
    // Singleton
    private static $instance;
    public static function GetInstance() : DatabaseSettings {
        if (empty(DatabaseSettings::$instance)) { DatabaseSettings::$instance = new DatabaseSettings(); }
        return DatabaseSettings::$instance;
    }

    // DB Information
    public static $db_dbEngine = "mysql";
    public static $db_Host = "localhost";
    public static $db_Username = "root";
    public static $db_Password = "password";
    public static $db_dbName = "GodfroyFinancialGroup";
    public static $db_Port = 3306;

    private function __construct() {
        $this->LoadSettings();
    }

    public function LoadSettings() { }
}

?>