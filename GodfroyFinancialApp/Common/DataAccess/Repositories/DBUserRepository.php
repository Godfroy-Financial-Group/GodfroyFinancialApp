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
        return array();
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

    public function getUsername($username) : ?User {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "Username", $this->dbManager->escapeString($username));
            $result->setFetchMode(PDO::FETCH_CLASS, 'User');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getEmail($email) : ?User {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "Email", $this->dbManager->escapeString($email));
            $result->setFetchMode(PDO::FETCH_CLASS, 'User');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getAuthToken($username) : ?User {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "AuthToken", $this->dbManager->escapeString($username));
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
            $query = "INSERT INTO $this->tableName (Username, Password, Email, DateCreated, DateModified, AuthToken) ".
                "VALUES(:Username, :Password, :Email, :DateCreated, :DateModified, :AuthToken)";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                ':Username' => $item->Username,
                ':Password' => $item->Password,
                ':Email' => $item->Email,
                ':DateCreated' => $item->DateCreated,
                ':DateModified' => $item->DateModified,
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
            $query = "UPDATE $this->tableName SET ".
                "Username = :Username, ".
                "Password = :Password, ".
                "Email = :Email, ".
                "DateCreated = :DateCreated, ".
                "DateModified = :DateModified, ".
                "AuthToken = :AuthToken ".
                "WHERE ID = :ID";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                ':ID' => $item->ID,
                ':Username' => $item->Username,
                ':Password' => $item->Password,
                ':Email' => $item->Email,
                ':DateCreated' => $item->DateCreated,
                ':DateModified' => $item->DateModified,
                ':AuthToken' => $item->AuthToken
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