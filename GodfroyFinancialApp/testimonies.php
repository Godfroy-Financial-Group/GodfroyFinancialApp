<?php $pageTitle = "Home"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

$dbManager = new DBManager();
$dbManager->connect();
$testimonyRepo = new DBTestimonyRepository($dbManager);

if ($_POST) {
    $deletionError = "";
    $delete = $_POST["deleteItem"];
    $approve = $_POST["approveItem"];
    $activate = $_POST["activateItem"];
    $deactivate = $_POST["deactivateItem"];

    if (!empty($delete)) {
        $testimonyRepo->delete($delete);
    }

    if (!empty($approve)) {
        $testimony = $testimonyRepo->getID($approve);
        $testimony->Approved = true;
        $testimonyRepo->update($testimony);
    }

    if (!empty($activate)) {
        $testimony = $testimonyRepo->getID($activate);
        $testimony->Active = true;
        $testimonyRepo->update($testimony);
    }

    if (!empty($deactivate)) {
        $testimony = $testimonyRepo->getID($deactivate);
        $testimony->Active = false;
        $testimonyRepo->update($testimony);
    }
}

// Get all the Testimonies
$testimonies = $testimonyRepo->getAll();
?>

<main role="main" class="container">
    <h1>Testimonies</h1>
    <form action="testimonies.php" method="post">
        <hr />
        <h2>Approved Testimonies</h2>
        <hr />
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Review</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonies as $value) :?>
                <?php if (!$value->Approved) continue; ?>
                <tr>
                    <td>
                        <?php echo $value->ID; ?>
                    </td>
                    <td>
                        <?php echo $value->Name; ?>
                    </td>
                    <td>
                        <?php echo $value->Review; ?>
                    </td>
                    <td>
                        <?php echo $value->Timestamp; ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <?php if ($value->Active) : ?>
                            <button class="btn btn-danger" type="submit" name="deactivateItem" value="<?php echo $value->ID ?>">Deactivate</button>
                            <?php else : ?>
                            <button class="btn btn-success" type="submit" name="activateItem" value="<?php echo $value->ID ?>">Activate</button>
                            <?php endif;?>
                            
                            <button id="usersDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Danger
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDangerDropDown">
                                <a class="dropdown-item" href="edittestimony.php?testimonyID=<?php echo $value->ID ?>">Edit</a>
                                <button class="dropdown-item deleteItemButton" type="submit" name="deleteItem" value="<?php echo $value->ID ?>">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <hr />
        <h2>Unapproved Testimonies</h2>
        <hr />
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Review</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonies as $value) :?>
                <?php if ($value->Approved) continue; ?>
                <tr>
                    <td>
                        <?php echo $value->ID; ?>
                    </td>
                    <td>
                        <?php echo $value->Name; ?>
                    </td>
                    <td>
                        <?php echo $value->Review; ?>
                    </td>
                    <td>
                        <?php echo $value->Timestamp; ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-success" type="submit" name="approveItem" value="<?php echo $value->ID ?>">Approve</button>
                            <button id="usersDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Danger
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDangerDropDown">
                                <a class="dropdown-item" href="edittestimony.php?testimonyID=<?php echo $value->ID ?>">Edit</a>
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
