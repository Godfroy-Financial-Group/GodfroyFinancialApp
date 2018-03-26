<?php $pageTitle = "Edit Testimony"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$testimonyRepo = new DBTestimonyRepository($dbManager);

$testimonyID = $_GET["testimonyID"];
$testimony = $testimonyRepo->getID($testimonyID);
if (empty($testimony)) {
    header("Location: testimonies.php");
    die();
}

$name = $testimony->Name;
$review = $testimony->Review;

if ($_POST) {
    $editTestimony = $_POST["editTestimony"];
    $name = $_POST["inputName"];
    $review = $_POST["inputReview"];

    if (!empty($editTestimony)) {
        $nameValidationError = "";
        $reviewValidationError = "";
        if (empty($name)) { $nameValidationError = "Please enter a name"; }
        if (empty($review)) { $reviewValidationError = "Please enter a review"; }
        if (empty($nameValidationError) && empty($reviewValidationError)) {
            $testimony->Name = $name;
            $testimony->Review = $review;
            $testimonyRepo->update($testimony);
            $name = "";
            $review = "";

            header("Location: testimonies.php");
            die();
        }
    }
}
?>

<main role="main" class="container">
    <div class="text-center">
        <form class="form-centered form-edittestimony" method="post" action="edittestimony.php?testimonyID=<?php echo $testimonyID ?>">
            <h1 class="h3 mb-3 font-weight-normal">Edit Testimony</h1>
            <?php if (!empty($nameValidationError) || !empty($reviewValidationError)) : ?>
                <?php if (!empty($nameValidationError)): ?>
                <span class="alert alert-danger">
                    <?php echo $nameValidationError;?>
                </span>
                <?php endif; ?>

                <?php if (!empty($reviewValidationError)): ?>
                <span class="alert alert-danger">
                    <?php echo $reviewValidationError;?>
                </span>
                <?php endif; ?>
            <?php endif; ?>

            <p>
                ID: <?php echo $testimonyID; ?>
            </p>

            <label for="inputName" class="sr-only">Name</label>
            <input type="text" id="inputName" name="inputName" class="form-control" placeholder="Name" value="<?php echo $name; ?>" autofocus required/>

            <label for="inputReview" class="sr-only">Email</label>
            <textarea id="inputReview" cols="40" rows="3" name="inputReview" class="form-control" placeholder="Review" required><?php echo $review; ?></textarea>

            <button class="btn btn-md btn-primary btn-block" name="editTestimony" type="submit" value="submitReview">Submit</button>
        </form>
    </div>
</main>



<?php include_once("Common/Footer.php"); ?>
