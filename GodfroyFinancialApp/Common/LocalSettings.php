<?php

class LocalSettings
{
    // DB Information
    public static $db_dbEngine = "mysql";
    public static $db_Host = "localhost";
    public static $db_Username = "root";
    public static $db_Password = "password";
    public static $db_dbName = "GodfroyFinancialGroup";
    public static $db_Port = 3306;

    // App Information
    public static $userCreationEnabled = false;

    public function __construct() { }
}

?>