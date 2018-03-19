<?php

/**
 * Testimony short summary.
 *
 * Testimony description.
 *
 * @version 1.0
 * @author andre
 */
class Testimony
{
    public $ID;
    public $Name;
    public $Review;
    public $Timestamp;
    public $Active;

    public function __construct(?int $id, string $name, string $review, $timestamp, bool $active) {
        $this->ID = $id;
        $this->Name = $name;
        $this->Review = $review;
        $this->Timestamp = $timestamp;
        $this->Active = $active;
    }

}

?>