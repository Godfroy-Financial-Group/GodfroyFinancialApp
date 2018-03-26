<?php $pageTitle = "Newsletter Subscriptions"; include_once("Common/Header.php"); ?>
<?php include_once("Vendor/mailchimp-api/MailChimp.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Setup Mailchimp MailChimp to get Subscriptions
use \DrewM\MailChimp\MailChimp;

try {
    if (LocalSettings::GetInstance()->IsMailChimpSetup()) {
        $mailChimp = new MailChimp(LocalSettings::GetInstance()->MailChimpAPIKey);
        $mailChimpListID = LocalSettings::GetInstance()->MailChimpListID;
    }
}
catch(Exception $e) { }

if ($_POST) {
    $deletionError = "";
    $delete = $_POST["deleteItem"];

    $subscribe = $_POST["subscribe"];
    $name = $_POST["inputName"];
    $email = $_POST["inputEmail"];

    if (!empty($delete)) {
        $mailChimp->delete("lists/$mailChimpListID/members/$delete");
    }

    if (!empty($subscribe)) {
        $nameValidationError = "";
        $emailValidationError = "";
        if (empty($name)) { $nameValidationError = "Please enter a username"; }
        if (empty($email)) { $emailValidationError = "Please enter an email"; }
        if (empty($nameValidationError) && empty($emailValidationError)) {
            $namesArray = explode(" ", $name);
            $firstName = array_shift($namesArray);
            $lastName = join(" ", $namesArray);
            if (empty($lastName)) { $lastName = "No Last Name"; }

            $result = $mailChimp->post("lists/$mailChimpListID/members", [
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => [
                                    'FNAME' => $firstName,
                                    'LNAME' => $lastName
                                   ]
            ]);

            $name = "";
            $email = "";
        }
    }

}

try {
    if (LocalSettings::GetInstance()->IsMailChimpSetup()) {
        // Get all the Testimonies
        $newsletterSubscriptions = array();
        $results = $mailChimp->get("lists/$mailChimpListID/members");
        foreach ($results["members"] as $value)
        {
            if ($value["status"] != "subscribed") continue;
            $subscription = new NewsletterSubscription();
            $subscription->ID = $value["id"];
            $subscription->Name = $value["merge_fields"]["FNAME"]." ".$value["merge_fields"]["LNAME"];
            $subscription->EmailAddress = $value["email_address"];
            $subscription->DateSubscriptionStarted = date("Y-m-d H:i:s", strtotime($value["last_changed"]));
            array_push($newsletterSubscriptions, $subscription);
        }
    }
}
catch(Exception $e) { }

//$newsletterSubscriptions = $newsletterSubscriptionRepo->getAll();
?>

<main role="main" class="container">
    <h1>Newsletter Subscriptions</h1>
    <hr />
    <p>Newsletter services are managed by <a href="mailchimp.com">MailChimp</a>. Visit the link and login to your account in order to manage your account and start up campaigns</p>
    <hr />
    <form action="newslettersubscriptions.php" method="post">
        <h2>Active Subscriptions</h2>
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
                            <button id="newsletterSubscriptionsDangerButton" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            <tfoot>
                <?php if (!empty($nameValidationError) || !empty($emailValidationError)) : ?>
                <tr>
                    <th></th>
                    <th>
                        <?php if (!empty($nameValidationError)): ?>
                        <span class="alert alert-danger">
                            <?php echo $nameValidationError;?>
                        </span>
                        <?php endif; ?>
                    </th>
                    <th>
                        <?php if (!empty($emailValidationError)) : ?>
                        <span class="alert alert-danger">
                            <?php echo $emailValidationError;?>
                        </span>
                        <?php endif; ?>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
                <?php endif; ?>
                <tr>
                    <th></th>
                    <th>
                        <label for="inputName" class="sr-only">Name</label>
                        <input type="text" id="inputName" name="inputName" class="form-control" placeholder="Name" value="<?php echo $name; ?>" autofocus required/>
                    </th>
                    <th>
                        <label for="inputEmail" class="sr-only">Email</label>
                        <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email Address" value="<?php echo $email; ?>" required/>
                    </th>
                    <th></th>
                    <th>
                        <button class="btn btn-md btn-primary btn-block" name="subscribe" type="submit" value="subscribe">Subscribe</button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </form>
</main>

<?php include_once("Common/Footer.php"); ?>
