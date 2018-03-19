<?php
include_once("../LocalSettings.php");

class DBManager
{
    public $Connection;
    private $m_connectionString;

    public function __construct() { }
    public function Connect() {
        try {
            $this->m_connectionString = LocalSettings::$db_dbEngine.":host=".LocalSettings::$db_Host.";dbname=".LocalSettings::$db_dbName;
            $this->Connection = new PDO($this->m_connectionString, LocalSettings::$db_Username, LocalSettings::$db_Password);
            $this->Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            die("System is currently unavailable. Please try again later");
        }

        return true;
    }

    function GetConnection() : ?PDO { return $this->Connection; }
    function GetError() : array { return $this->GetConnection->errorInfo(); }
    function GetErrorCode() { return $this->GetConnection->errorCode(); }

    function IsConnected() {
        return isset($this->Connection);
    }

    function QueryCustom($query) {
        try {
            //echo $query;
            $result = $this->GetConnection()->query($query);
            return $result;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    function QueryAll($tableName) {
        try {
            $query = "SELECT * FROM $tableName";
            //echo $query;
            $result = $this->GetConnection()->query($query);
            return $result;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    function QueryByFilter($tableName, $filterName, $filterValue) {
        try {
            $query = "SELECT *
                  FROM $tableName
                  WHERE $filterName = '$filterValue'";
            //echo $query;
            $result = $this->GetConnection()->query($query);
            return $result;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function EscapeString(string $string) : string {
        $escaped = $string;//$this->GetConnection()->quote($string);
        return $escaped;
    }
}
?>