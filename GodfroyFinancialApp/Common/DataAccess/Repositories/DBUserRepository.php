<?php
class DBUserRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "users");
    }

    public function getAll() : array {
        try {
            $result = $this->dbManager->queryAll($this->tableName);
            $result->setFetchMode(PDO::FETCH_CLASS, 'User');
            $fetch = $result->fetchAll();
            return $fetch;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getID($key) : ?User {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "ID", $this->dbManager->escapeString($key));
            $result->setFetchMode(PDO::FETCH_CLASS, 'User');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insert(User $item) {
        try {
            $query = "INSERT INTO $this->tableName (Username, Password, Email, DateCreated, AuthToken) VALUES(:Username, :Password, :Email, :DateCreated, :AuthToken)";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                ':Username' => $item->Username,
                ':Password' => $item->Password,
                ':Email' => $item->Password,
                ':DateCreated' => $item->DateCreated,
                ':AuthToken' => $item->AuthToken
            ));

            return true;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        return false;
    }

    public function update(User $item) {
        try {
            $query = "UPDATE $this->tableName
                  SET Username = '".$this->dbManager->escapeString($item->Username)."',
                      Password = '".$this->dbManager->escapeString($item->Password)."',
                      Email = '".$this->dbManager->escapeString($item->Email)."',
                      DateCreated = '".$this->dbManager->escapeString($item->DateCreated)."'
                      AuthToken = '".$this->dbManager->escapeString($item->AuthToken)."'
                  WHERE ID = ".$this->dbManager->escapeString($item->ID);

            $result = $this->dbManager->queryCustom($query);
            $result->setFetchMode(PDO::FETCH_CLASS, 'User');
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