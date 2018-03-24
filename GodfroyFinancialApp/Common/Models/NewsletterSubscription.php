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

    public function __construct() { }

    public static function FromAll(?int $id, string $name, string $emailAddress, $dateSubscriptionStarted) : NewsletterSubscription{
        $newsletterSubscription = new NewsletterSubscription();
        $newsletterSubscription->ID = $id;
        $newsletterSubscription->Name = $name;
        $newsletterSubscription->EmailAddress = $emailAddress;
        $newsletterSubscription->DateSubscriptionStarted = $dateSubscriptionStarted;
        return $newsletterSubscription;
    }
}

?>