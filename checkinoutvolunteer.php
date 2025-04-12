<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

if (!$loggedIn) {
    header('Location: login.php');
    die();
}

$isAdmin = ($accessLevel > 2);

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $args = sanitize($_GET);
    $id = $args['id'];
    $viewingSelf = ($id == $userID);
} else {
    $id = $userID; 
    $viewingSelf = true;
}

if ($isAdmin && (isset($_GET['first_name']) || isset($_GET['last_name']))) {
    $first_name = isset($_GET['first_name']) ? sanitize($_GET)['first_name'] : '';
    $last_name = isset($_GET['last_name']) ? sanitize($_GET)['last_name'] : '';
    
    $matching_volunteers = search_person_by_name($first_name, $last_name);

    if (empty($matching_volunteers)) {
        $message = "No volunteers found with that name.";
    }
}


if ($isAdmin && isset($_GET['search_id'])) {
    $search_id = sanitize($_GET)['search_id'];
    $volunteer = retrieve_person($search_id);
    if (empty($volunteer)) {
        $message = "No volunteer found with that name.";
    } else {
        $id = $volunteer->get_id();
        $viewingSelf = false;
    }
} else {
    $volunteer = retrieve_person($userID);
}


$volunteer = retrieve_person($id);

$check_in_history = get_events_attended_by_2($id);
$check_in_history = array_filter($check_in_history, function($entry) {
    $oneWeekAgo = strtotime('-7 days');
    return (strtotime($entry['start_time']) >= $oneWeekAgo);
});

usort($check_in_history, function($a, $b) {
    return strtotime($b['start_time']) <=> strtotime($a['start_time']);
});

$message = '';

if (!$isAdmin && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eventID'], $_POST['action'])) {
    $eventID = $_POST['eventID'];
    $action = $_POST['action'];
    $event_info = retrieve_event2($eventID);
    if ($action === "checkin") {
        $start = $_POST['start_time'];
        if (can_check_in($id, $event_info)) {
            check_in($id, $eventID, $start);
            $formatted_start = date("Y-m-d H:i:s", strtotime($start));
            $message = "Checked in successfully at " . $formatted_start;
        } else {
            $message = "You've already checked in for this event or it's not the correct time.";
        }
    } elseif ($action === "checkout") {
        $end = $_POST['end_time'];
        if (can_check_out($id, $event_info)) {
            check_out($id, $eventID, $end);
            $formatted_end = date("Y-m-d H:i:s", strtotime($end));
            $message = "Checked out successfully at " . $formatted_end;
        } else {
            $message = "You can't check out without checking in first.";
        }
    }
}

$events = get_signed_up_events_by($id);
$today = date('Y-m-d');
$events = array_filter($events, function($event) use ($today) {
    return ($event['date'] >= $today);
});

$selected_event_info = null;
if (isset($_POST['eventID']) && !empty($_POST['eventID'])) {
    $selected_event_info = retrieve_event2($_POST['eventID']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Volunteer Check In / Out</title>
    <link rel="stylesheet" href="css/hours-report.css">
</head>
<body>
<?php require('header.php'); ?>
<main class="hours-report">
    <h2>Volunteer Check-In / Check-Out</h2>

    <?php if ($isAdmin): ?>
    <form method="get" action="">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" placeholder="Enter first name">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" placeholder="Enter last name">

        <button type="submit" class="button">Search</button>
    </form>

        <br>

        <?php if (!empty($matching_volunteers)): ?>
    <h4>Matching Volunteers:</h4>
    <ul>
        <?php foreach ($matching_volunteers as $vol): ?>
            <li>
                <?= htmlspecialchars($vol->get_first_name() . ' ' . $vol->get_last_name()) ?>
                (<?= htmlspecialchars($vol->get_id()) ?>)
                <a href="?search_id=<?= htmlspecialchars($vol->get_id()) ?>">View</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

        <?php if (!$viewingSelf && !empty($volunteer)): ?>
            <h4><?= htmlspecialchars($volunteer->get_id()) ?>'s Check-In History (Past 7 Days)</h4>
            <?php if (!empty($check_in_history)): ?>
                <div class="scrollable-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($check_in_history as $entry): ?>
                            <tr>
                                <td><?= htmlspecialchars(get_event_from_id($entry['eventID'])) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y g:i A', strtotime($entry['start_time']))) ?></td>
                                <td>
                                    <?= $entry['end_time']
                                        ? htmlspecialchars(date('M d, Y g:i A', strtotime($entry['end_time'])))
                                        : '<em>Still Checked In</em>' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>This volunteer has not checked into any events in the past 7 days.</p>
            <?php endif; ?>
        <?php else: ?>
            <p style="color:#666;">Use the search box above to see a volunteer's check-in and check-out history.</p>
        <?php endif; ?>

    <?php else: ?>
        <form method="post" id="checkForm">
            <label for="eventID">Event:</label>
            <select name="eventID" id="eventID" required onchange="this.form.submit()">
                <option value="" disabled <?= !isset($_POST['eventID']) ? 'selected' : '' ?>>Select Event</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['id'] ?>"
                            <?= (isset($_POST['eventID']) && $_POST['eventID'] == $event['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($event['name']) ?> - <?= date('M d, Y', strtotime($event['date'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <?php if ($selected_event_info): ?>
                <?php if (can_check_in($id, $selected_event_info)) : ?>
                    <input type="hidden" name="action" value="checkin">
                    <input type="hidden" name="start_time" value="<?= date('Y-m-d\TH:i') ?>">
                    <button type="submit" class="button success">Check-In</button>
                <?php elseif (can_check_out($id, $selected_event_info)) : ?>
                    <input type="hidden" name="action" value="checkout">
                    <input type="hidden" name="end_time" value="<?= date('Y-m-d\TH:i') ?>">
                    <button type="submit" class="button danger">Check-Out</button>
                <?php else: ?>
                    <p>No available action for this event at this time.</p>
                <?php endif; ?>
            <?php endif; ?>
        </form>

        <?php if (!empty($message)): ?>
            <div style="margin: 10px 0; color: green; font-weight: bold;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <h4>Your Check-In History (Past 7 Days)</h4>
        <?php if (!empty($check_in_history)): ?>
            <div class="scrollable-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($check_in_history as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars(get_event_from_id($entry['eventID'])) ?></td>
                            <td><?= htmlspecialchars(date('M d, Y g:i A', strtotime($entry['start_time']))) ?></td>
                            <td>
                                <?= $entry['end_time']
                                    ? htmlspecialchars(date('M d, Y g:i A', strtotime($entry['end_time'])))
                                    : '<em>Still Checked In</em>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You have not checked into any events in the past 7 days.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($viewingSelf): ?>
        <a class="button cancel no-print" href="volunteerPortal.php">Return to Portal</a>
    <?php else: ?>
        <a class="button cancel no-print" href="volunteerPortal.php?id=<?= htmlspecialchars($id) ?>">Return to Portal</a>
    <?php endif; ?>

</main>
</body>
</html>
