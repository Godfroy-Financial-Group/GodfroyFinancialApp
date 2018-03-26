<?php $pageTitle = "Testimonies"; include_once("Common/Header.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Create the DB Managers
$dbManager = new DBManager();
$dbManager->connect();
$appSettingsRepo = new DBApplicationSettingRepository($dbManager);

$settingID = $_GET["settingID"];
$setting = $appSettingsRepo->getID($settingID);
if (empty($setting)) {
    header("Location: settings.php");
    die();
}

$name = $setting->Name;
$group = $setting->Group;
$value = $setting->Value;

if ($_POST) {
    $editSetting = $_POST["editSetting"];
    $name = $_POST["inputName"];
    $group = $_POST["inputGroup"];
    $value = $_POST["inputValue"];

    if (!empty($editSetting)) {
        $nameValidationError = "";
        $valueValidationError = "";
        if (empty($name)) { $nameValidationError = "Please enter a name"; }
        if (!empty($appSettingsRepo->getName($name))) { $nameValidationError = "A setting with this name already exists"; }
        if (empty($value)) { $valueValidationError = "Please enter a review"; }
        if (empty($nameValidationError) && empty($valueValidationError)) {
            $setting->Name = $name;
            $setting->Value = $value;
            $appSettingsRepo->update($setting);
            $name = "";
            $value = "";

            header("Location: settings.php");
            die();
        }
    }
}
?>

<main role="main" class="container">
    <div class="text-center">
        <form class="form-centered form-editsetting" method="post" action="editsetting.php?settingID=<?php echo $settingID ?>">
            <h1 class="h3 mb-3 font-weight-normal">Edit Testimony</h1>
            <?php if (!empty($nameValidationError) || !empty($valueValidationError)) : ?>
                <?php if (!empty($nameValidationError)): ?>
                <span class="alert alert-danger">
                    <?php echo $nameValidationError;?>
                </span>
            <?php endif; ?>
            <?php if (!empty($valueValidationError)): ?>
                <span class="alert alert-danger">
                    <?php echo $valueValidationError;?>
                </span>
                <?php endif; ?>
            <?php endif; ?>

            <p>
                ID: <?php echo $settingID; ?>
            </p>

            <label for="inputGroup" class="sr-only">Group</label>
            <input type="text" id="inputGroup" name="inputGroup" class="form-control" placeholder="Group" value="<?php echo $group; ?>" />

            <label for="inputName" class="sr-only">Name</label>
            <input type="text" id="inputName" name="inputName" class="form-control" placeholder="Name" value="<?php echo $name; ?>" autofocus required/>

            <label for="inputValue" class="sr-only">Value</label>
            <input type="text" id="inputValue" name="inputValue" class="form-control" placeholder="Value" value="<?php echo $value; ?>" required/>

            <button class="btn btn-md btn-primary btn-block" name="editSetting" type="submit" value="editSetting">Submit</button>
        </form>
    </div>
</main>



<?php include_once("Common/Footer.php"); ?>
