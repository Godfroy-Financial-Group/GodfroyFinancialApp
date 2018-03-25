<?php include_once("Common/IncludeAll.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$dbManager->connect();

$userID = $_GET["userid"];
$user = $userRepo->getID($userID);

if ($_POST) {
    $username = $_POST["inputUsername"];
    $email = $_POST["inputEmail"];
    $newpassword = $_POST["inputNewPassword"];
    $currentpassword = $_POST["inputCurrentPassword"];

    $authenticationService = new AuthenticationService();
    $userCreationService = new UserCreationService();

    // Validate the inputs
    if (!$userCreationService->ValidateUsernameFormat($username)) {
        $usernameValidationError = "Username can only contain a-zA-Z0-9_-.! space";
    }
    else if ($user->Username != $username) {
        if (!$userCreationService->ValidateUsernameDuplicate($username)) {
            $usernameValidationError = "A user with this name already exists";
        }
    }

    if (!$userCreationService->ValidateEmailFormat($email)) {
        $emailValidationError = "Email is invalid";
    }
    else if ($user->Email != $email) {
        if (!$userCreationService->ValidateEmailDuplicate($email)) {
            $emailValidationError = "A user with this email already exists";
        }
    }

    if ($authenticationService->VerifyLogin($user->Username, $currentpassword)) {
        if (!empty($newpassword)) {
            if(!$userCreationService->ValidatePasswordFormat($newpassword)) {
                $passwordValidationError = "The password does not meet the requirements";
            }
        }
    }
    else {
        $passwordMatchValidationError = "The password you have entered is incorrect";
    }

    if (empty($usernameValidationError) && empty($emailValidationError) && empty($passwordValidationError) && empty($passwordMatchValidationError)) {
        $usernameValidationError = "";
        $emailValidationError = "";
        $passwordValidationError = "";
        $passwordMatchValidationError = "";

        $newUser = $userCreationService->EditUser($user, $username, $email, $newpassword, $currentpassword);

        // Redirect to the proper page
        //ob_end_clean( ); // Run this if the Redirect doesn't work

        header("Location: users.php");
        die();
    }
}
?>
<?php $pageTitle = "Edit User"; include_once("Common/Header.php"); ?>


<main role="main" class="container">
    <div class="text-center">
        <form class="form-signin" method="post" action="edituser.php?userid=<?php echo $userID ?>">
            <img class="mb-4" src="Content/Images/Logos/Black_Godfroy_Financial_Logo.png" alt="logo" width="300" />

            <h1 class="h3 mb-3 font-weight-normal">Edit User</h1>
            <?php if(!UserCreationService::UserCreationEnabled()): ?>
            <p class="alert alert-danger" role="alert">User Creation has been disabled by the system administrator</p>
            <?php endif; ?>

            <?php if(!empty($usernameValidationError)): ?>
            <p class="alert alert-danger" role="alert">
                <?php echo $usernameValidationError; ?>
            </p>
            <?php endif; ?>
            <?php if(!empty($emailValidationError)): ?>
            <p class="alert alert-danger" role="alert">
                <?php echo $emailValidationError; ?>
            </p>
            <?php endif; ?>
            <?php if(!empty($passwordValidationError)): ?>
            <p class="alert alert-danger" role="alert">
                <?php echo $passwordValidationError; ?>
            </p>
            <?php endif; ?>
            <?php if(!empty($passwordMatchValidationError)): ?>
            <p class="alert alert-danger" role="alert">
                <?php echo $passwordMatchValidationError; ?>
            </p>
            <?php endif; ?>

            <p>
                ID: <?php echo $user->ID; ?>
            </p>

            <label for="inputUsername" class="sr-only">Username</label>
            <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" value="<?php echo $user->Username; ?>" required autofocus />

            <label for="inputEmail" class="sr-only">Email</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email Address" value="<?php echo $user->Email; ?>" required />

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputNewPassword" name="inputNewPassword" class="form-control" placeholder="New Password" value="<?php echo $newpassword; ?>"/ />

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputCurrentPassword" class="form-control" placeholder="Current Password" value="<?php echo $currentpassword; ?>" required />

            <hr />
            <p>Password must be atleast 6 characters and can contain letters, numbers and symbols.</p>
            <hr />

            <button class="btn btn-lg btn-primary btn-block" type="submit">Edit User</button>
        </form>
    </div>
</main>

<?php include_once("Common/Footer.php"); ?>