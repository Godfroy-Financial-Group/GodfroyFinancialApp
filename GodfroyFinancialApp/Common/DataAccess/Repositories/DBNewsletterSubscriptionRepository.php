<?php
class DBNewsletterSubscriptionRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "newsletter_subscriptions");
    }

    public function getAll() : array {
        try {
            $result = $this->dbManager->queryAll($this->tableName);
            $result->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
            $fetch = $result->fetchAll();
            return $fetch;
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getID($key) : ?NewsletterSubscription {
        try {
            $result = $this->dbManager->queryByFilter($this->tableName, "ID", $this->dbManager->escapeString($key));
            $result->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
            $fetch = $result->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insert(NewsletterSubscription $item) {
        try {
            $stmt = $this->dbManager->GetConnection()->prepare("INSERT INTO $this->tableName VALUES(:Name, :EmailAddress, :DateSubscriptionStarted)");
            $stmt->execute(array(
              ':Name' => $item->Username,
              ':EmailAddress' => $item->Password,
              ':DateSubscriptionStarted' => $item->DateCreated,
            ));

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
            $fetch = $stmt->fetchAll();
            return $fetch[0];
        }
        catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return array();
    }

    public function update(NewsletterSubscription $item) {
        try {
            $query = "UPDATE $this->tableName
                  SET Name = '".$this->dbManager->escapeString($item->Name)."',
                      EmailAddress = '".$this->dbManager->escapeString($item->EmailAddress)."',
                      DateSubscriptionStarted = '".$this->dbManager->escapeString($item->DateSubscriptionStarted)."'
                  WHERE ID = ".$this->dbManager->escapeString($item->ID);

            $result = $this->dbManager->queryCustom($query);
            $result->setFetchMode(PDO::FETCH_CLASS, 'NewsletterSubscription');
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