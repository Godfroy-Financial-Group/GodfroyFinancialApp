<?php
class DBGenericRepository
{
    public $dbManager;
    public $tableName;

    function __construct(DBManager $dbManager, $tableName) {
        $this->dbManager = $dbManager;
        $this->tableName = $tableName;
    }

    function getAll() {
        return $this->dbManager->queryAll($this->tableName);
    }

    public function delete(int $id) {
        try {
            $stmt = $this->dbManager->GetConnection()->prepare("DELETE FROM $this->tableName WHERE ID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>