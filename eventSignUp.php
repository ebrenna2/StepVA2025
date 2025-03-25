<?php
    session_cache_expire(30);
    session_start();

    require_once('database/dbEvents.php');
    include_once('database/dbinfo.php'); 

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];

        if (isset($_GET['childID'])) {
            $userID = $_GET['childID'];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        
        $args = sanitize($_POST, null);
        $required = array(
            "event-id", "account-name", "start-time", "departure-time"
        );

        if (!wereRequiredFieldsSubmitted($args, $required)) {
            if (isset($_GET['childID'])) {
                header("Location: eventFailure.php?childID=" . urlencode($_GET['childID']));
            } else {
                header("Location: eventFailure.php");
            }
            die();
        }

        $validated = validate12hTimeRangeAndConvertTo24h($args["start-time"], "11:59 PM");
        if (!$validated) {
            if (isset($_GET['childID'])) {
                header("Location: eventFailure.php?childID=" . urlencode($_GET['childID']));
            } else {
                header("Location: eventFailure.php");
            }
            die();
        }
        $startTime = $args['start-time'] = $validated[0];

        $validated2 = validate12hTimeRangeAndConvertTo24h($args["departure-time"], "11:59 PM");
        if (!$validated2) {
            if (isset($_GET['childID'])) {
                header("Location: eventFailure.php?childID=" . urlencode($_GET['childID']));
            } else {
                header("Location: eventFailure.php");
            }
            die();
        }
        $departureTime = $args['departure-time'] = $validated2[0];

        $validatedDepartureTimeAfterStartTime = validate24hTimeRange($startTime, $departureTime);
        if (!$validatedDepartureTimeAfterStartTime) {
            if (isset($_GET['childID'])) {
                header("Location: eventFailureBadDepartureTime.php?childID=" . urlencode($_GET['childID']));
            } else {
                header("Location: eventFailureBadDepartureTime.php");
            }
            die();
        }

        $eventId = $args['event-id'];
        if (isset($_GET['childID'])) {
            $account_name = $_GET['childID'];
        } else {
            $account_name = htmlspecialchars_decode($args['account-name']);
        }
    
        $role = $args['role'];
        $notes = "Skills: " . ($args['skills'] ?? '') . " | Dietary restrictions: " . ($args['restrictions'] ?? '') . 
             " | Disabilities: " . ($args['disabilities'] ?? '') . " | Materials: " . ($args['materials'] ?? '');
    
        $restricted = htmlspecialchars(isset($_GET['restricted']) ? $_GET['restricted'] : '');

        if ($restricted == "Yes") {
            $result = request_event_signup_by_id($eventId, $account_name, $role, $notes);
            if ($result === false) { // Explicitly check for false
                error_log("Request signup failed for eventId=$eventId, user=$account_name");
                if (isset($_GET['childID'])) {
                    header("Location: requestFailed.php?childID=" . urlencode($_GET['childID']));
                } else {
                    header('Location: requestFailed.php');
                }
                die();
            }
            require_once('database/dbMessages.php');
            $eventName = get_event_name_by_id($eventId);
            send_system_message($userID, "Your request to sign up for $eventName has been sent to an admin.", 
                              "Your request to sign up for $eventName will be reviewed by an admin shortly.");
            if (isset($_GET['childID'])) {
                header('Location: signupPending.php?childID=' . urlencode($_GET['childID']));
            } else {
                header('Location: signupPending.php');
            }
            die();
        } else {
            $result = sign_up_for_event_by_id($eventId, $account_name, $role, $notes);
            if ($result === false) { // Explicitly check for false
                error_log("Direct signup failed for eventId=$eventId, user=$account_name");
                if (isset($_GET['childID'])) {
                    header("Location: eventFailure.php?childID=" . urlencode($_GET['childID']));
                } else {
                    header("Location: eventFailure.php");
                }
                exit();
            }
            require_once('database/dbMessages.php');
            $eventName = get_event_name_by_id($eventId);
            send_system_message($userID, "You are now signed up for $eventName!", 
                              "Thank you for signing up for $eventName!");
            if (isset($_GET['childID'])) {
                header("Location: signupSuccess.php?childID=" . urlencode($_GET['childID']));
            } else {
                header('Location: signupSuccess.php');
            }
            die();
        }
    }

    $con = connect();  

    $event_id = isset($_GET['event_id']) ? htmlspecialchars($_GET['event_id']) : '';
    $event_name = '';
    if ($event_id) {
        $event_name = get_event_name_by_id($event_id);
        // Debug output - remove after testing
        if (empty($event_name)) {
            error_log("Event name not found for ID: " . $event_id);
        } else {
            error_log("Event name found: " . $event_name . " for ID: " . $event_id);
        }
}
    
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = '';
    }

    if (isset($_SESSION['_id'])) {
        $account_name = $_SESSION['_id'];
        if (isset($_GET['childID'])) {
            $account_name = $_GET['childID'];
        }
    } else {
        $account_name = '';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Step VA | Sign-Up for Event</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Sign-Up for Event</h1>
        <main class="date">
            <h2>Sign-Up for Event Form</h2>
            <form id="new-event-form" method="post">

                <!-- In the form section -->
                <label for="event-name">* Event Name </label>
                <input type="text" id="event-name" name="event-name" required 
                    value="<?php echo htmlspecialchars($event_name ? $event_name : 'Event Not Found (ID: ' . $event_id . ')'); ?>" 
                    placeholder="Event name" readonly>
                <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($event_id); ?>">

                <label for="account-name">* Your Account Name </label>
                <input type="text" id="account-name" name="account-name" 
                    <?php echo ($accessLevel >= 2) ? '' : 'readonly'; ?> 
                    value="<?php echo htmlspecialchars($account_name); ?>" 
                    placeholder="Enter account name">

                <label for="start-time">* What Time Will You Arrive? </label>
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter arrival time. Ex. 12:00 PM">
                <label for="departure-time">* What Time Will You Leave? </label>
                <input type="text" id="departure-time" name="departure-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter departure time. Ex. 3:00 PM">
                <label for="skills"> Do You Have Any Skills To Share? </label>
                <input type="text" id="skills" name="skills" placeholder="Enter skills. Ex. crochet, tap dancer">
                <label for="diet-restrictions"> Do You Have Any Dietary Restrictions? </label>
                <input type="text" id="restrictions" name="restrictions" placeholder="Enter restrictions">
                <label for="disabilities"> Do You Have Any Disabilities We Should Be Aware Of? </label>
                <input type="text" id="disabilities" name="disabilities" placeholder="Enter disabilities">
                <label for="materials"> Are You Bringing Any Materials (e.g. snacks, craft supplies)? </label>
                <input type="text" id="materials" name="materials" placeholder="Enter materials. Ex. felt, pipe cleaners">
                
                <fieldset>
                    <label for="role">* Are you a volunteer or a participant? </label>
                    <div class="radio-group">
                        <input type="radio" id="v" name="role" value="v" required><label for="v">Volunteer</label>
                        <input type="radio" id="p" name="role" value="p" required><label for="p">Participant</label>
                    </div>
                </fieldset>
                
                <p></p>
                <br/>
                <p></p>
                <input type="submit" value="Sign up for Event">
            </form>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </body>
</html>