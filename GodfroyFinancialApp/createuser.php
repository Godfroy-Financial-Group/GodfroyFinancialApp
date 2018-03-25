<?php include_once("Common/IncludeAll.php"); ?>
<?php

if ($_POST) {
    $username = $_POST["inputUsername"];
    $email = $_POST["inputEmail"];
    $password = $_POST["inputPassword"];
    $confirmPassword = $_POST["inputConfirmPassword"];

    $userCreationService = new UserCreationService();

    // Validate the inputs
    if (!$userCreationService->ValidateUsernameFormat($username)) {
        $usernameValidationError = $userCreationService->GetValidationError();
    }
    else if (!$userCreationService->ValidateUsernameDuplicate($username)) {
        $usernameValidationError = $userCreationService->GetValidationError();
    }

    if (!$userCreationService->ValidateEmailFormat($email)) {
        $emailValidationError = $userCreationService->GetValidationError();
    }
    else if (!$userCreationService->ValidateEmailDuplicate($email)) {
        $emailValidationError = $userCreationService->GetValidationError();
    }

    if(!$userCreationService->ValidatePasswordFormat($password)) {
        $passwordValidationError = $userCreationService->GetValidationError();
    }
    else if ($password != $confirmPassword) {
        $passwordValidationError = "The passwords you have entered in do not match";
    }

    // Create the DB Managers
    $dbManager = new DBManager();
    $dbManager->connect();
    $userRepo = new DBUserRepository($dbManager);

    if (empty($usernameValidationError) && empty($emailValidationError) && empty($passwordValidationError)) {
        $usernameValidationError = "";
        $emailValidationError = "";
        $passwordValidationError = "";

        if (UserCreationService::UserCreationEnabled()) {
            $newUser = $userCreationService->CreateUser($username, $email, $password);

            // Redirect to the proper page
            //ob_end_clean( ); // Run this if the Redirect doesn't work

            if (!isset($_SESSION["LoggedInUser"])) header("Location: login.php");
            else header("Location: users.php");
            die();
        }
    }
}
?>
<?php $pageTitle = "Create User"; include_once("Common/Header.php"); ?>


<main role="main" class="container">
    <div class="text-center">
        <form class="form-centered form-createuser" method="post" action="createuser.php">
            <img class="mb-4" src="Content/Images/Logos/Black_Godfroy_Financial_Logo.png" alt="logo" width="300" />

            <h1 class="h3 mb-3 font-weight-normal">User Creation</h1>
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

            <label for="inputUsername" class="sr-only">Username</label>
            <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required autofocus />

            <label for="inputEmail" class="sr-only">Email</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email Address" value="<?php echo $email; ?>" required />

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required />

            <label for="inputConfirmPassword" class="sr-only">Password</label>
            <input type="password" id="inputConfirmPassword" name="inputConfirmPassword" class="form-control" placeholder="Confirm Password" value="" required />

            <hr />
            <p>Password must be atleast 6 characters and can contain letters, numbers and symbols.</p>
            <hr />

            <?php if (UserCreationService::UserCreationEnabled()) :?>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Create User</button>
            <?php endif;?>

            <?php if (!isset($_SESSION["LoggedInUser"])) :?>
            <a href="login.php">Have an account? Login.</a>
            <?php endif;?>
        </form>
    </div>
</main>

<?php include_once("Common/Footer.php"); ?>