<?php $pageTitle = "Home"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();
?>

<main role="main" class="container">
    <h1>Testimonies</h1>
</main>

<?php include_once("Common/Footer.php"); ?>
