<?php
/**
 * AuthenticationService short summary.
 *
 * AuthenticationService description.
 *
 * @version 1.0
 * @author andre
 */
class UserCreationService
{
    private $dbManager;
    private $userRepo;
    public function __construct() {
        $this->dbManager = new DBManager();
        $this->dbManager->connect();
        $this->userRepo = new DBUserRepository($this->dbManager);
    }

    public static function UserCreationEnabled() : bool {
        if (LocalSettings::$publicUserCreationEnabled) return true;
        if (LocalSettings::$adminUserCreationEnabled && isset($_SESSION["LoggedInUser"])) return true;
        return false;
    }

    private $validationError = "";
    public function GetValidationError() { return $this->validationError; }

    public function ValidateUsernameFormat($username) : bool {
        if(preg_match('/^\w{5,}$/', $username)) { // \w equals "[0-9A-Za-z_]"
            // valid username, alphanumeric & longer than or equals 5 chars
            return true;
        }

        $this->validationError = "Username must be greater than or equal to 5 characters and can only contain alphanumeric characters and underscore";
        return false;
    }
    public function ValidateEmailFormat($email) : bool {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //$emailErr = "Valid email format";
            return true;
        }

        $this->validationError = "The email address you entered is invalid";
        return false;
    }
    public function ValidatePasswordFormat($password) : bool {
        if (strlen($password) < 6) {
            $this->validationError = "The password you entered is less than 6 characters long";
            return false;
        }
        return true;
    }

    public function ValidateUsernameDuplicate($username) : bool {
        if (empty($this->userRepo->getUsername($username))) return true;
        $this->validationError = "A user with this username already exists";
        return false;
    }
    public function ValidateEmailDuplicate($email) : bool {
        if (empty($this->userRepo->getEmail($email))) return true;
        $this->validationError = "A user with this email already exists";
        return false;
    }


    public function CreateUser(string $username, string $email, string $password) : ?User {
        if (!UserCreationService::UserCreationEnabled()) return null;

        $date = date('Y-m-d H:i:s');
        $newUser = User::FromAll(null, $username, User::HashPassword($password), $email, $date, $date, User::HashAPIKey($username, $password, $date, $date));
        $this->userRepo->insert($newUser);
        return $newUser;
    }

    public function EditUser(User $currentUser, string $username, string $email, string $newPassword, string $currentPassword) {
        $newDate = date('Y-m-d H:i:s');
        $currentUser->Username = $username;
        $currentUser->Email = $email;
        $currentUser->DateModified = $newDate;

        $passwordToUse = "";
        if (!empty($newPassword)) { $passwordToUse = $newPassword; }
        else { $passwordToUse = $currentPassword; }

        $currentUser->Password = User::HashPassword($passwordToUse);
        $currentUser->AuthToken = User::HashAPIKey($currentUser->Username, $passwordToUse, $currentUser->DateCreated, $currentUser->DateModified);
        $this->userRepo->update($currentUser);

    }
}