<?php include_once("Common/Header.php"); ?>

<div class="container">
    <h1>Logout</h1>
</div>

<?php
// Completely annihilate the session
session_unset();
session_destroy();
// Redirect to Index.php
header("Location: login.php");
die();
?>


<?php include_once("Common/Footer.php"); ?>