<?php 
session_cache_expire(30);
session_start();

// Make session information accessible, allowing us to associate data with the logged-in user.
ini_set("display_errors",1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} 

// Require admin privileges
if ($accessLevel < 2) {
    header('Location: login.php');
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');
    require_once('database/dbEvents.php');
    $args = sanitize($_POST, null);
    $required = array(
        "name", "date", "start-time", "end-time", "role", "description",
    );

    // Check required fields
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo 'bad form data';
        die();
    } else {
        $validated = validate12hTimeRangeAndConvertTo24h($args["start-time"], $args["end-time"]);
        if (!$validated) {
            echo 'bad time range';
            die();
        }

        $restricted_signup = $args['role'];
        $startTime = $args['start-time'] = $validated[0];
        $endTime = $args['end-time'] = $validated[1];
        $date = $args['date'] = validateDate($args["date"]);
        $recurrence = isset($args['recurrence']) ? $args['recurrence'] : null;
        $endDate = isset($args['end-date']) ? $args['end-date'] : null;

        if (!$startTime || !$endTime || !$date) {
            echo 'bad args';
            die();
        }

        // Capture restricted_volunteers (if provided)
        $restricted_volunteers = isset($args['restricted-volunteers']) ? intval($args['restricted-volunteers']) : NULL;
        
        // If event is recurring, call the set_recurring function
        if (isset($args['recurring']) && $args['recurring'] == 'y') {
            $args['end-date'] = $endDate;
            $args['recurrence'] = $recurrence;
            $args['date'] = $date;
            $args['start-time'] = $startTime;
            $args['end-time'] = $endTime;
            $args['role'] = $restricted_signup;
            $args['restricted-volunteers'] = $restricted_volunteers;

            $createdEventIds = set_recurring($args);

            if ($createdEventIds) {
                header('Location: eventSuccess.php');
                exit();
            } else {
                echo 'Error creating recurring events.';
                die();
            }
        } else {
            // Create a single event if not recurring
            $args['restricted-volunteers'] = $restricted_volunteers;
            $id = create_event($args);

            if (!$id) {
                echo 'Error creating event';
                die();
            } else {
                header('Location: eventSuccess.php');
                exit();
            }
        }
    }
}

$date = null;
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
    $timeStamp = strtotime($date);
    if (!preg_match($datePattern, $date) || !$timeStamp) {
        header('Location: calendar.php');
        die();
    }
}

// Include the database connection
include_once('database/dbinfo.php'); 
$con = connect();  

// Get animal data from database for form (if necessary)
//$all_animals = mysqli_query($con, "SELECT * FROM `dbAnimals`");
//$all_locations = mysqli_query($con, "SELECT * FROM `dbLocations`");
//$all_services = mysqli_query($con, "SELECT * FROM `dbServices`");
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Step VA | Create Event</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Create Event</h1>
        <main class="date">
            <h2>New Event Form</h2>
            <form id="new-event-form" method="post">
                <label for="name">* Event Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 
                <!--
                <label for="name">* Abbreviated Name</label>
                <input type="text" id="abbrev-name" name="abbrev-name" maxlength="11" required placeholder="Enter name that will appear on calendar">
                --->
                <label for="name">* Date </label>
                <input type="date" id="date" name="date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">* Start Time </label>
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter start time. Ex. 12:00 PM">
                <label for="name">* End Time </label>
                <input type="text" id="end-time" name="end-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter end time. Ex. 1:00 PM">
                <fieldset>
                <label for="role"> * Restrictions </label>
            <div class="radio-group">
                <input type="radio" id="u" name="role" value="u" required><label for="role">Unrestricted</label>
                <input type="radio" id="r" name="role" value="r" required><label for="role">Restricted</label>
            </div>
                </fieldset>
            <fieldset>
                <label for="recurring"> * Is this event recurring? </label>
            <div class="radio-group">
                <input type="radio" id="y" name="recurring" value="y" required><label for="recurring">Yes</label>
                <input type="radio" id="n" name="recurring" value="n" required><label for="recurring">No</label>
            </div>
            <label for="name">Restricted Number of Volunteers</label>
            <input type="number" id="restricted-volunteers" name="restricted-volunteers" required placeholder="Enter restricted number (e.g. 1-99)">
                </fieldset>
                <label for="recurrence">Recurrence Frequency</label>
            <select id="recurrence" name="recurrence">
                <option value="Daily" <?php if (isset($status) && $status == 'Daily') echo 'selected' ?>>Daily</option>
                <option value="Weekly" <?php if (isset($status) && $status == 'Weekly') echo 'selected' ?>>Weekly</option>
                <option value="Biweekly" <?php if (isset($status) && $status == 'Biweekly') echo 'selected' ?>>Biweekly</option>
                <option value="Monthly" <?php if (isset($status) && $status == 'Monthly') echo 'selected' ?>>Monthly</option>
            </select>
                <label for="end-date">* End Date </label>
                <input type="date" id="end-date" name="end-date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">* Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
                <label for="name">Location </label>
                <input type="text" id="location" name="location" required placeholder="Enter location">
                <label for="name">Capacity </label>
                <input type="number" id="capacity" name="capacity" required placeholder="Enter capacity (e.g. 1-99)">
                <!-- Service function
                <fieldset>
                    <label for="name">* Service </label>
                    <?php 
                        /*
                        // fetch data from the $all_services variable
                        // and individually display as an option
                        echo '<ul>';
                        while ($service = mysqli_fetch_array(
                                $all_services, MYSQLI_ASSOC)):; 
                            echo '<li><input class="checkboxes" type="checkbox" name="service[]" value="' . $service['id'] . '" required/> ' . $service['name'] . '</li>';
                        endwhile;
                        echo '</ul>';
                        */
                    ?>
                </fieldset> 
                --->

                <!-- Location
                <label for="name">* Location </label>
                <select for="name" id="location" name="location" required>
                    <option value="">--</option>
                    <?php 
                        /*
                        // fetch data from the $all_locations variable
                        // and individually display as an option
                        while ($location = mysqli_fetch_array(
                                $all_locations, MYSQLI_ASSOC)):; 
                        */
                    ?>
                    <option value="
                        <?php //echo $location['id'];?>">
                        <?php //echo $location['name'];?>
                    </option>
                    <?php 
                        //endwhile; 
                        // terminate while loop
                    ?>
                </select><p></p>
                --->
  
                <!--
                <label for="name">* Animal</label>
                <select for="name" id="animal" name="animal" required>
                    <?php 
                        /*
                        // fetch data from the $all_animals variable
                        // and individually display as an option
                        while ($animal = mysqli_fetch_array(
                                $all_animals, MYSQLI_ASSOC)):; 
                        */
                    ?>
                    <option value="
                        <?php //echo $animal['id'];?>
                        ">
                        <?php //echo $animal['name'];?>
                    </option>
                    <?php 
                        // endwhile; 
                        // terminate while loop
                    ?>
                </select>
                <br/>
                <p></p>
                --->
                <input type="submit" value="Create Event">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>

                <!-- Require at least one checkbox be checked -->
                <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });
                </script>
        </main>
    </body>
</html>