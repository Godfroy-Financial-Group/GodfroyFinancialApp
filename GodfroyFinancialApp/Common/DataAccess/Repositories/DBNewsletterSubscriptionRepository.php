<?php
class DBNewsletterSubscriptionRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "newsletter_subscriptions");
    }

    public function getAll() : array {
        try {
            $result = $this->dbManager->QueryAll($this->tableName);
            $result->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
            $fetch = $result->fetchAll();
            return $fetch;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return array();
    }

    public function getID($key) : ?NewsletterSubscription {
        try {
            $result = $this->dbManager->QueryByFilter($this->tableName, "ID", $this->dbManager->escapeString($key));
            $result->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insert(NewsletterSubscription $item): bool {
        try {
            $query = "INSERT INTO $this->tableName (Name, EmailAddress, DateSubscriptionStarted) ".
                    "VALUES(:Name, :EmailAddress, :DateSubscriptionStarted)";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                ':Name' => $item->Name,
                ':EmailAddress' => $item->EmailAddress,
                ':DateSubscriptionStarted' => $item->DateSubscriptionStarted,
            ));

            return true;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return false;
    }

    public function update(NewsletterSubscription $item) : bool{
        try {
            $query = "UPDATE $this->tableName SET ".
                        "Name = :Name, ".
                        "EmailAddress = :EmailAddress, ".
                        "DateSubscriptionStarted = :DateSubscriptionStarted, ".
                    "WHERE ID = :ID";

            $stmt = $this->dbManager->GetConnection()->prepare($query);
            $stmt->execute(array(
                  ':ID' => $item->ID,
                  ':Name' => $item->Name,
                  ':EmailAddress' => $item->EmailAddress,
                  ':DateSubscriptionStarted' => $item->DateSubscriptionStarted,
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