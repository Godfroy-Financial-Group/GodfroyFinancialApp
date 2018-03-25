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

    public function ValidateUsernameFormat($username) : bool {
        if (preg_match('/^[a-zA-Z0-9\s\_\-\.\!]*', $username)) return false;
        return true;
    }
    public function ValidateEmailFormat($email) : bool {
        if (preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$', $email)) return false;
        return true;
    }
    public function ValidatePasswordFormat($password) : bool {
        if (strlen($password) < 6) return false;
        //if (preg_match_all('/[0-9]*', $password)) {
        //    if (preg_match_all('/[a-zA-Z]*', $password)) {
        //        if (preg_match_all('/[.!@#$%^&*\-\+=]*', $password)) {
        //            return true;
        //        }
        //    }
        //}
        return true;
    }

    public function ValidateUsernameDuplicate($username) : bool {
        if (empty($this->userRepo->getUsername($username))) return true;
        return false;
    }
    public function ValidateEmailDuplicate($email) : bool {
        if (empty($this->userRepo->getEmail($email))) return true;
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