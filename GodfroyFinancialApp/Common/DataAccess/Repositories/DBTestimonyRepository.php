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

    public function insert(Testimony $item) : bool {
        try {
            $query = "INSERT INTO $this->tableName (Name, Review, Timestamp, Active) ".
                        "VALUES(:Name, :Review, :Timestamp, :Active)";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
              ':Name' => $item->Name,
              ':Review' => $item->Review,
              ':Timestamp' => $item->Timestamp,
              ':Active' => $item->Active
            ));
            return true;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return false;
    }

    public function update(Testimony $item) : bool{
        try {
            $query = "UPDATE $this->tableName SET ".
                        "Name = :Name, ".
                        "Review = :Review, ".
                        "Timestamp = :Timestamp, ".
                        "Active = :Active, ".
                    "WHERE ID = :ID";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                  ':ID' => $item->ID,
                  ':Name' => $item->Name,
                  ':Review' => $item->Review,
                  ':Timestamp' => $item->Timestamp,
                  ':Active' => $item->Active
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