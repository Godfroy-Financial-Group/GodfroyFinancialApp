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
    public $DateModified;
    public $AuthToken;

    public function __construct() { }

    public static function FromAll(?int $id, string $username, string $password, string $email, $dateCreated, $dateModified, string $authToken) : User {
        $user = new User();
        $user->ID = $id;
        $user->Username = $username;
        $user->Password = $password;
        $user->Email = $email;
        $user->DateCreated = $dateCreated;
        $user->DateModified = $dateModified;
        $user->AuthToken = $authToken;
        return $user;
    }


    public static function HashPassword(string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function VerifyPassword(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }

    public static function HashAPIKey($username, $password, $dateCreated, $dateModified) {
        return password_hash($username.$password.$dateCreated.$dateModified, PASSWORD_DEFAULT);
    }
}

class SafeUser {
    public $ID;
    public $Username;
    public $Email;
    public $AuthToken;

    public function __construct() { }
    public static function FromAll(int $id, string $username, string $email, string $authToken) : SafeUser {
        $safeUser = new SafeUser();
        $safeUser->ID = $id;
        $safeUser->Username = $username;
        $safeUser->Email = $email;
        $safeUser->AuthToken = $authToken;
        return $safeUser;
    }
    public static function FromUser(User $user) : SafeUser {
        $safeUser = new SafeUser();
        $safeUser->ID = $user->ID;
        $safeUser->Username = $user->Username;
        $safeUser->Email = $user->Email;
        $safeUser->AuthToken = $user->AuthToken;
        return $safeUser;
    }
}

?>