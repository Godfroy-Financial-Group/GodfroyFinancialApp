<?php include_once("Common/IncludeAll.php"); ?>
<?php
$loginError = false;

if (isset($_GET['returnUrl'])) {
    $_SESSION['returnUrl'] = urldecode($_GET['returnUrl']);
}

if ($_POST) {
    $username = $_POST["inputUsername"];
    $password = $_POST["inputPassword"];
    $rememberMe = $_POST["rememberMe"];

    // Default the Login Error
    $loginError = true;

    $authenticationService = new AuthenticationService();
    $safeUser = $authenticationService->Login($username, $password);
    if (!empty($safeUser)) {
        $loginError = false;
        $_SESSION["LoggedInUser"] = $safeUser;
        if (!empty($rememberMe)) { setcookie("user", $safeUser, 2147483647); }

        // Redirect to the proper page
        //ob_end_clean( ); // Run this if the Redirect doesn't work

        $returnUrl = $_SESSION['returnUrl'];
        unset($_SESSION['returnUrl']);
        if (empty($returnUrl)) {
            // Not specified defaults to Course Selection Page
            header("Location: index.php");
        }
        else {
            header("Location: $returnUrl");
        }
        die();
    }
}
?>
<?php $pageTitle = "Login"; include_once("Common/Header.php"); ?>


<main role="main" class="container">
    <div class="text-center">
        <form class="form-signin" method="post" action="login.php">
            <img class="mb-4" src="Content/Images/Logos/Black_Godfroy_Financial_Logo.png" alt="logo" width="300" />

            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
            <?php if($loginError): ?>
                <p class="alert alert-danger" role="alert">Incorrect Username and/or Password</p>
            <?php endif; ?>
            <label for="inputUsername" class="sr-only">Username</label>
            <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus />
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required />
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe" />Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            <?php if (LocalSettings::$userCreationEnabled) :?>
                <a href="createuser.php">Need an account? Sign up.</a>
            <?php endif;?>
        </form>
    </div>
</main>

<?php include_once("Common/Footer.php"); ?>