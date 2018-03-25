<?php $pageTitle = "Newsletter Subscriptions"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$newsletterSubscriptionRepo = new DBNewsletterSubscriptionRepository($dbManager);

if ($_POST) {
    $deletionError = "";
    $delete = $_POST["deleteItem"];

    if (!empty($delete)) {
        $newsletterSubscriptionRepo->delete($delete);
    }
}

// Get all the Testimonies
$newsletterSubscriptions = $newsletterSubscriptionRepo->getAll();
?>

<main role="main" class="container">
    <h1>Newsletter Subscriptions</h1>
    <hr />
    <h2>Quick Actions</h2>
    <hr />
    <h2>Active Subscriptions</h2>
    <form action="newslettersubscriptions.php" method="post">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Date Subscription Started</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletterSubscriptions as $value) :?>
                <tr>
                    <td>
                        <?php echo $value->ID; ?>
                    </td>
                    <td>
                        <?php echo $value->Name; ?>
                    </td>
                    <td>
                        <?php echo $value->EmailAddress; ?>
                    </td>
                    <td>
                        <?php echo $value->DateSubscriptionStarted; ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="usersDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Danger
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDangerDropDown">
                                <button class="dropdown-item deleteItemButton" type="submit" name="deleteItem" value="<?php echo $value->ID ?>">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </form>
</main>

<?php include_once("Common/Footer.php"); ?>
