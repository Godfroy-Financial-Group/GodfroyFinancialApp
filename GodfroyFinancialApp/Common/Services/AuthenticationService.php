<?php
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

    private $validationError = "";
    public function GetValidationError() { return $this->validationError; }
    private function SetLoginError() { $this->validationError = "Incorrect Username and/or Password"; }
    private function SetAuthTokenError() { $this->validationError = "The authtoken you supplied is incorrect"; }

    public function Login(string $username, string $password) : ?SafeUser {
        $user = $this->userRepo->getUsername($username);

        // Check if a user with this username exists
        if (empty($user) || $user == null) { $this->SetLoginError(); return null; }

        // Verify if the password is correct
        if (User::VerifyPassword($password, $user->Password)) {
            return SafeUser::FromUser($user);
        }

        // If not, return null for a failed login
        $this->SetLoginError();
        return null;
    }

    public function VerifyLogin(string $username, string $password) : bool {
        $user = $this->userRepo->getUsername($username);

        // Check if a user with this username exists
        if (empty($user) || $user == null) { $this->SetLoginError(); return false; }

        // Verify if the password is correct
        if (User::VerifyPassword($password, $user->Password)) {
            return true;
        }

        // If not, return null for a failed login
        $this->SetLoginError();
        return false;
    }

    public function VerifyAuthToken(string $username, string $authToken) : bool {
        $user = $this->userRepo->getUsername($username);
        if (empty($user) || $user == null) { $this->SetAuthTokenError(); return false; }

        if ($user->AuthToken == $authToken) return true;
        $this->SetAuthTokenError();
        return false;
    }
}