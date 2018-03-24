<?php include_once("Common/IncludeAll.php"); ?>
<?php
if (isset($_SESSION["LoggedInUser"])) {
    header("Location: index.php");
    die();
}
if (!LocalSettings::$userCreationEnabled) {
    header("Location: index.php");
    die();
}

$usernameValidationError = "";
$emailValidationError = "";
$passwordValidationError = "";

if ($_POST) {
    $username = $_POST["inputUsername"];
    $email = $_POST["inputEmail"];
    $password = $_POST["inputPassword"];

    // Create the DB Managers
    $dbManager = new DBManager();
    $dbManager->connect();
    $userRepo = new DBUserRepository($dbManager);

    if (empty($usernameValidationError) && empty($emailValidationError) && empty($passwordValidationError)) {
        $usernameValidationError = "";
        $emailValidationError = "";
        $passwordValidationError = "";

        if (LocalSettings::$userCreationEnabled) {
            $date = date('Y-m-d H:i:s');
            $newUser = User::FromAll(null, $username, User::HashPassword($password), $email, $date, $date, User::HashAPIKey($username, $password, $date, $date));
            $userRepo->insert($newUser);

            // Redirect to the proper page
            //ob_end_clean( ); // Run this if the Redirect doesn't work

            header("Location: login.php");
            die();
        }
    }
}
?>
<?php $pageTitle = "Create User"; include_once("Common/Header.php"); ?>


<main role="main" class="container">
    <div class="text-center">
        <form class="form-signin" method="post" action="createuser.php">
            <img class="mb-4" src="Content/Images/Logos/Black_Godfroy_Financial_Logo.png" alt="logo" width="300" />

            <h1 class="h3 mb-3 font-weight-normal">User Creation</h1>
            <?php if(!LocalSettings::$userCreationEnabled): ?>
            <p class="alert alert-danger" role="alert">User Creation has been disabled by the system administrator</p>
            <?php endif; ?>

            <?php if(!empty($usernameValidationError)): ?>
            <p class="alert alert-danger" role="alert"><?php echo $usernameValidationError; ?></p>
            <?php endif; ?>
            <?php if(!empty($emailValidationError)): ?>
            <p class="alert alert-danger" role="alert"><?php echo $emailValidationError; ?></p>
            <?php endif; ?>
            <?php if(!empty($passwordValidationError)): ?>
            <p class="alert alert-danger" role="alert"><?php echo $passwordValidationError; ?></p>
            <?php endif; ?>

            <label for="inputUsername" class="sr-only">Username</label>
            <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus />

            <label for="inputEmail" class="sr-only">Email</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email Address" required autofocus />

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required />

            <button class="btn btn-lg btn-primary btn-block" type="submit">Create User</button>
            <a href="login.php">Have an account? Login.</a>
        </form>
    </div>
</main>

<?php include_once("Common/Footer.php"); ?>