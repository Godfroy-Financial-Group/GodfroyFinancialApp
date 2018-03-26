<?php
class DBApplicationSettingRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "application_settings");
    }

    public function getAll() : array {
        try {
            $result = $this->dbManager->QueryAll($this->tableName);
            $result->setFetchMode(PDO::FETCH_CLASS, 'ApplicationSetting');
            $fetch = $result->fetchAll();
            return $fetch;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return array();
    }

    public function getID($key) : ?ApplicationSetting {
        try {
            $result = $this->dbManager->QueryByFilter($this->tableName, "ID", $this->dbManager->escapeString($key));
            $result->setFetchMode(PDO::FETCH_CLASS, 'ApplicationSetting');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getName($name) : ?ApplicationSetting {
        try {
            $result = $this->dbManager->QueryByFilter($this->tableName, "Name", $this->dbManager->escapeString($name));
            $result->setFetchMode(PDO::FETCH_CLASS, 'ApplicationSetting');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getGroup($group) : ?ApplicationSetting {
        try {
            $result = $this->dbManager->QueryByFilter($this->tableName, "Grouping", $this->dbManager->escapeString($group));
            $result->setFetchMode(PDO::FETCH_CLASS, 'ApplicationSetting');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insert(ApplicationSetting $item): bool {
        try {
            $query = "INSERT INTO $this->tableName (Name, Grouping, Value) ".
                    "VALUES(:Name, :Group, :Value)";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                ':Name' => $item->Name,
                ':Group' => $item->Grouping,
                ':Value' => $item->Value
            ));

            return true;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return false;
    }

    public function update(ApplicationSetting $item) : bool{
        try {
            $query = "UPDATE $this->tableName SET ".
                        "Name = :Name, ".
                        "Grouping = :Group, ".
                        "Value = :Value ".
                    "WHERE ID = :ID";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                  ':ID' => $item->ID,
                  ':Name' => $item->Name,
                  ':Group' => $item->Grouping,
                  ':Value' => $item->Value,
                ));
            return true;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return false;

    }
}
?>