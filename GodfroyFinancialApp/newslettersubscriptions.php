<?php $pageTitle = "Newsletter Subscriptions"; include_once("Common/Header.php"); ?>
<?php include_once("Vendor/mailchimp-api/MailChimp.php"); ?>
<?php

// If user is logged in, assign User object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Setup Mailchimp MailChimp to get Subscriptions
use \DrewM\MailChimp\MailChimp;

try {
    // Load the Lists
    if (LocalSettings::GetInstance()->IsMailChimpSetup()) {
        $mailChimp = new MailChimp(LocalSettings::GetInstance()->MailChimpAPIKey);
        $mailChimpLists = $mailChimp->get("lists", ["count" => 10])["lists"];

        $newsletterLists = array();
        foreach ($mailChimpLists as $value)
        {
            $count             = $value["member_count"];
            $unsubscribedCount = $value["unsubscribe_count"];
            $cleanedCount      = $value["cleaned_count"];
            $subscribedCount   = $count - $unsubscribedCount - $cleanedCount;

            $newList = [
                "listID"            => $value["id"],
                "name"              => $value["name"],
                "count"             => $count,
                "unsubscribedCount" => $unsubscribedCount,
                "cleanedCount"      => $cleanedCount,
                "subscribedCount"   => $subscribedCount,
                "currentPage"       => 0,
                "perPage"           => 10,
                "subscriptions"     => array()
            ];
            array_push($newsletterLists, $newList);
        }
    }
} catch(Exception $e) { }

if ($_POST) {
    // Delete Data
    $deletionError = "";
    $delete = $_POST["deleteItem"];

    // Subscribe Data
    $subscribe = $_POST["subscribe"];
    $name = $_POST["inputName"];
    $listID = $_POST["inputListID"];
    $email = $_POST["inputEmail"];

    // Page Change Data
    $pagePrev = $_POST["pagePrev"];
    $pageNext = $_POST["pageNext"];
    $pageChange = $_POST["pageChange"];
    $pageNumber = $_POST["inputPageNumber"];

    // Set the Page Numbers
    //if (empty($pageNumber)) { $pageNumber = 0; }
    if (empty($pagePrev) && empty($pageNext) && empty($pageChange)) { $pageNumber = 0; }
    else {
        for ($i = 0; $i < count($newsletterLists); $i++)
        {
            if ($newsletterLists[$i]["listID"] == $listID) {
                if (!empty($pagePrev)) { $newsletterLists[$i]["currentPage"] = (int)$pagePrev; }
                else if (!empty($pageNext)) { $newsletterLists[$i]["currentPage"] = (int)$pageNext; }
                else if (!empty($pageChange)) { $newsletterLists[$i]["currentPage"] = (int)$pageNumber; }
                break;
            }
        }
    }

    // Handle Deletion
    if (!empty($delete)) {
        $mailChimp->delete("lists/$listID/members/$delete");
    }

    // Handle Subscription
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

            $result = $mailChimp->post("lists/$listID/members", [
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

// Load the Subscribed Members
try {
    if (LocalSettings::GetInstance()->IsMailChimpSetup()) {
        foreach ($newsletterLists as $list)
        {
            $listID = $list["listID"];
            $currentPage = $list["currentPage"];
            $perPage = $list["perPage"];

            // Get all the Members
            $results = $mailChimp->get("lists/$listID/members", [
                    "count" => $perPage,
                    "offset" => $currentPage * $perPage
                ]);
            $newsletterSubscriptions = array();
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

            $list["subscriptions"] = $newsletterSubscriptions;
        }
    }
} catch(Exception $e) { }
?>

<main role="main" class="container">
    <h1>Newsletter Subscriptions</h1>
    <hr />
    <p>Newsletter services are managed by <a href="mailchimp.com">MailChimp</a>. Visit the link and login to your account in order to manage your account and start up campaigns</p>
    <?php foreach ($newsletterLists as $list) : ?>
    <hr />
    <form action="newslettersubscriptions.php" method="post">
        <h2><?php echo $list["name"]; ?></h2>

        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="submit" name="pagePrev" class="btn btn-secondary" value="<?php echo $list["currentPage"] - 1;?>">Previous</button>

            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Change
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupPageChangeDropDown">
                <?php
                    $subscribedCount = $list["subscribedCount"];
                    $perPage = $list["perPage"];
                    $maxPages = ceil($subscribedCount / $perPage);
                ?>
                <?php for ($i = 0; $i < $maxPages; $i++) : ?>
                <button type="submit" name="pageChange" class="dropdown-item" value="<?php echo $i; ?>"><?php echo $i + 1; ?></button>
                <?php endfor; ?>
            </div>
            <button type="submit" name="pageNext" class="btn btn-secondary" value="<?php echo $list["currentPage"] + 1;?>">Next</button>
        </div>

        <input type="hidden" name="inputListID" value="<?php echo $list["listID"]; ?>" />
        <input type="hidden" name="inputPageNumber" value="<?php echo $list["currentPage"]; ?>" />

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <!--<th>ID</th>-->
                    <th>Date Subscription Started</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletterSubscriptions as $value) :?>
                <tr>
                    <!--<td><?php echo $value->ID; ?></td>-->
                    <td>
                        <?php echo $value->DateSubscriptionStarted; ?>
                    </td>
                    <td>
                        <?php echo $value->Name; ?>
                    </td>
                    <td>
                        <?php echo $value->EmailAddress; ?>
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
                    <!--<th></th>-->
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
                </tr>
                <?php endif; ?>
                <tr>
                    <!--<th></th>-->
                    <th></th>
                    <th>
                        <label for="inputName" class="sr-only">Name</label>
                        <input type="text" name="inputName" class="form-control" placeholder="Name" value="<?php echo $name; ?>" />
                    </th>
                    <th>
                        <label for="inputEmail" class="sr-only">Email</label>
                        <input type="email" name="inputEmail" class="form-control" placeholder="Email Address" value="<?php echo $email; ?>" />
                    </th>
                    <th>
                        <button class="btn btn-md btn-primary btn-block" name="subscribe" type="submit" value="subscribe">Subscribe</button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </form>
    <?php endforeach; ?>
</main>

<?php include_once("Common/Footer.php"); ?>
