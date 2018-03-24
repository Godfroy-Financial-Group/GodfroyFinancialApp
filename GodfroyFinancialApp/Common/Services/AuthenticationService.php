<?php
include_once("../IncludeAll.php");

/**
 * AuthenticationService short summary.
 *
 * AuthenticationService description.
 *
 * @version 1.0
 * @author andre
 */
class AuthenticationService
{
    private $dbManager;
    private $userRepo;
    public function __construct() {
        $this->dbManager = new DBManager();
        $this->dbManager->connect();
        $this->userRepo = new DBUserRepository($this->dbManager);
    }

    public function Login(string $username, string $password) : ?SafeUser {
        $user = $this->userRepo->getUsername($username);

        // Check if a user with this username exists
        if (empty($user) || $user == null) return null;

        // Verify if the password is correct
        if (User::VerifyPassword($password, $user->Password)) {
            return SafeUser::FromUser($user);
        }

        // If not, return null for a failed login
        return null;
    }

    public function VerifyAuthToken(string $username, string $authToken) : bool {
        $user = $this->userRepo->getUsername($username);
        if (empty($user) || $user == null) return false;

        if ($user->AuthToken == $authToken) return true;
        return false;
    }
}