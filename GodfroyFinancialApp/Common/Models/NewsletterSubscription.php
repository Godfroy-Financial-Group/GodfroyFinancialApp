<?php

/**
 * Newsletter short summary.
 *
 * Newsletter description.
 *
 * @version 1.0
 * @author andre
 */
class NewsletterSubscription
{
    public $ID;
    public $Name;
    public $EmailAddress;
    public $DateSubscriptionStarted;

    public function __construct(?int $id, string $name, string $emailAddress, $dateSubscriptionStarted) {
        $this->ID = $id;
        $this->Name = $name;
        $this->EmailAddress = $emailAddress;
        $this->DateSubscriptionStarted = $dateSubscriptionStarted;
    }
}

?>