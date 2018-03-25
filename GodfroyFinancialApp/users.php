<?php $pageTitle = "Home"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$userRepo = new DBUserRepository($dbManager);

if ($_POST){
    $deletionError = "";
    $delete = $_POST["deleteItem"];
    if ($LoggedInUser->ID == $delete) {
        $deletionError = "You can not delete yourself";
    }
    else {
        if (!empty($delete)) {
            $userRepo->delete($delete);
        }
    }
}

// Get all the Users
$users = $userRepo->getAll();
?>

<main role="main" class="container">
    <h1>Users</h1>
    <hr />

    <?php if(!empty($deletionError)): ?>
    <p class="alert alert-danger" role="alert">
        <?php echo $deletionError; ?>
    </p>
    <?php endif; ?>

    <form action="users.php" method="post">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $value) :?>
                <tr>
                    <td>
                        <?php echo $value->ID ?>
                    </td>
                    <td>
                        <?php echo $value->Username ?>
                    </td>
                    <td>
                        <?php echo $value->Email ?>
                    </td>
                    <td>
                        <?php echo $value->DateCreated ?>
                    </td>
                    <td>
                        <?php echo $value->DateModified ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="usersDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Danger
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDangerDropDown">
                                <a class="dropdown-item" href="edituser.php?userid=<?php echo $value->ID ?>">Edit</a>
                                <button class="dropdown-item deleteItemButton" type="submit" name="deleteItem" value="<?php echo $value->ID ?>">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </form>
    <hr />
    <h2>Quick Actions</h2>
    <a href="createuser.php" class="btn btn-primary">Create User</a>
    <hr />
</main>

<?php include_once("Common/Footer.php"); ?>
