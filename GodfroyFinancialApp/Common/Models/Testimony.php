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
    public $Approved;

    public function __construct() { }

    public static function FromAll(?int $id, string $name, string $review, $timestamp, bool $active, bool $approved) : Testimony {
        $testimony = new Testimony();
        $testimony->ID = $id;
        $testimony->Name = $name;
        $testimony->Review = $review;
        $testimony->Timestamp = $timestamp;
        $testimony->Active = $active;
        $testimony->Approved = $approved;
        return $testimony;
    }

}

?>