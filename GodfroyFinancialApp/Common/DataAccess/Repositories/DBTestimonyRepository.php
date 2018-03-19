<?php
class DBTestimonyRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "testimonies");
    }

    public function getAll() : array {
        try {
            $result = $this->dbManager->queryAll($this->tableName);
            $result->setFetchMode(PDO::FETCH_CLASS, 'Testimony');
            $fetch = $result->fetchAll();
            return $fetch;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getID($key) : ?Testimony {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "ID", $this->dbManager->escapeString($key));
            $result->setFetchMode(PDO::FETCH_CLASS, 'Testimony');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insert(Testimony $item) {
        try {
            $stmt = $this->dbManager->GetConnection()->prepare("INSERT INTO $this->tableName VALUES(:Name, :Review, :Timestamp, :Active)");
            $stmt->execute(array(
              ':Name' => $item->Name,
              ':Review' => $item->Review,
              ':Timestamp' => $item->Timestamp,
              ':Active' => $item->Active
            ));

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Testimony');
            $fetch = $stmt->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return array();
    }

    public function update(Testimony $item) {
        try {
            $query = "UPDATE $this->tableName
                  SET Name = '".$this->dbManager->escapeString($item->Name)."',
                      Review = '".$this->dbManager->escapeString($item->Review)."',
                      Timestamp = '".$this->dbManager->escapeString($item->Timestamp)."'
                      Active = ".$this->dbManager->escapeString($item->Active)."
                  WHERE ID = ".$this->dbManager->escapeString($item->ID);

            $result = $this->dbManager->queryCustom($query);
            $result->setFetchMode(PDO::FETCH_CLASS, 'Testimony');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return array();
    }
}
?>