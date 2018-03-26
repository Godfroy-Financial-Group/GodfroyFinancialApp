<?php $pageTitle = "Settings"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$appSettingsRepo = new DBApplicationSettingRepository($dbManager);

if ($_POST) {
    $deletionError = "";
    $delete = $_POST["deleteItem"];

    $submitSetting = $_POST["submitSetting"];
    $group = $_POST["inputGroup"];
    $name = $_POST["inputName"];
    $value = $_POST["inputValue"];

    if (!empty($delete)) {
        $appSettingsRepo->delete($delete);
    }

    if (!empty($submitSetting)) {
        $nameValidationError = "";
        $groupValidationError = "";
        $valueValidationError = "";
        if (empty($name)) { $nameValidationError = "Please enter a name"; }
        if (!empty($appSettingsRepo->getName($name))) { $nameValidationError = "A setting with this name already exists"; }
        if (empty($value)) { $valueValidationError = "Please enter a value"; }
        if (empty($nameValidationError) && empty($valueValidationError)) {
            $appSetting = ApplicationSetting::FromAll(null, $name, $group, $value);
            $appSettingsRepo->insert($appSetting);
            $name = "";
            $group = "";
            $value = "";
        }
    }
}

// Reload the Settings
$instance = LocalSettings::GetInstance()->LoadSettings();

// Get all the Testimonies
$appSettings = $appSettingsRepo->getAll();
?>

<main role="main" class="container">
    <h1>Application Settings</h1>
    <hr />
    <form action="settings.php" method="post">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Group</th>
                    <th>Name</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appSettings as $valueSetting) :?>
                <tr>
                    <td><?php echo $valueSetting->ID; ?></td>
                    <td><?php echo $valueSetting->Grouping; ?></td>
                    <td><?php echo $valueSetting->Name; ?></td>
                    <td><?php echo $valueSetting->Value; ?></td>
                    <td>
                        <div class="btn-group" role="group">                           
                            <button id="settingsDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Danger
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDangerDropDown">
                                <a class="dropdown-item" href="editsetting.php?settingID=<?php echo $valueSetting->ID ?>">Edit</a>
                                <button class="dropdown-item deleteItemButton" type="submit" name="deleteItem" value="<?php echo $valueSetting->ID ?>">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <?php if (!empty($nameValidationError) || !empty($valueValidationError)) : ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th>
                        <?php if (!empty($nameValidationError)): ?>
                        <span class="alert alert-danger">
                            <?php echo $nameValidationError;?>
                        </span>
                        <?php endif; ?>
                    </th>
                    <th>
                        <?php if (!empty($valueValidationError)) : ?>
                        <span class="alert alert-danger">
                            <?php echo $valueValidationError;?>
                        </span>
                        <?php endif; ?>
                    </th>
                    <th></th>
                </tr>
                <?php endif; ?>
                <tr>
                    <th></th>
                    <th>
                        <label for="inputGroup" class="sr-only">Group</label>
                        <input type="text" id="inputGroup" name="inputGroup" class="form-control" placeholder="Group" value="<?php echo $group; ?>" />
                    </th>
                    <th>
                        <label for="inputName" class="sr-only">Name</label>
                        <input type="text" id="inputName" name="inputName" class="form-control" placeholder="Name" value="<?php echo $name; ?>" autofocus />
                    </th>
                    <th>
                        <label for="inputValue" class="sr-only">Value</label>
                        <input type="tel" id="inputValue" name="inputValue" class="form-control" placeholder="Value" value="<?php echo $value; ?>" />
                    </th>
                    <th>
                        <button class="btn btn-md btn-primary btn-block" name="submitSetting" type="submit" value="submitSetting">Add</button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </form>
</main>

<?php include_once("Common/Footer.php"); ?>
