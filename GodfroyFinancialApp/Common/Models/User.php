<?php

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author andre
 */
class User
{
    public $ID;
    public $Username;
    public $Password;
    public $Email;
    public $DateCreated;
    public $AuthToken;

    public function __construct(?int $id, string $username, string $password, string $email, $dateCreated, string $authToken) {
        $this->ID = $id;
        $this->Username = $username;
        $this->Password = $password;
        $this->Email = $email;
        $this->DateCreated = $dateCreated;
        $this->AuthToken = $authToken;
    }


    public static function HashPassword(string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function VerifyPassword(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }
}

?>