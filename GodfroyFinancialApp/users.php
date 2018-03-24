<?php $pageTitle = "Home"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$userRepo = new DBUserRepository($dbManager);

// Get all the Users
$users = $userRepo->getAll();
?>

<main role="main" class="container">
    <h1>Users</h1>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Date Created</th>
                <th>Date Modified</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $value) :?>
                <tr>
                    <td><?php echo $value->ID ?></td>
                    <td><?php echo $value->Username ?></td>
                    <td><?php echo $value->Email ?></td>
                    <td><?php echo $value->DateCreated ?></td>
                    <td><?php echo $value->DateModified ?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</main>

<?php include_once("Common/Footer.php"); ?>
