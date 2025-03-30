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

$isAdmin = $accessLevel >= 2;

if ($isAdmin && isset($_GET['id'])) {
    require_once('include/input-validation.php');
    $args = sanitize($_GET);
    $id = $args['id'];
    $viewingSelf = $id == $userID;
} else {
    $id = $_SESSION['_id'];
    $viewingSelf = true;
}

require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eventID'], $_POST['action'])) {
    $eventID = $_POST['eventID'];
    $action = $_POST['action'];
    $event_info = retrieve_event2($eventID);

    if ($action === "checkin") {
        $start = $_POST['start_time'];
        if (can_check_in($userID, $event_info)) {
            check_in($userID, $eventID, $start);
            $formatted_start = date("Y-m-d H:i:s", strtotime($start));
            $message = "Checked in successfully at " . $formatted_start;
        } else {
            $message = "You've already checked in for this event or it's not the correct time.";
        }
    }

    if ($action === "checkout") {
        $end = $_POST['end_time'];
        if (can_check_out($userID, $event_info)) {
            check_out($userID, $eventID, $end);
            $formatted_end = date("Y-m-d H:i:s", strtotime($end));
            $message = "Checked out successfully at " . $formatted_end;
        } else {
            $message = "You can't check out without checking in first.";
        }
    }
}

$events = get_signed_up_events_by($userID);

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
    <?php if (!empty($message)): ?>
        <div style="margin: 10px 0; color: green; font-weight: bold;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" id="checkForm">
    <label for="eventID">Event:</label>
    <select name="eventID" id="eventID" required onchange="this.form.submit()">
        <option value="" disabled <?= !isset($_POST['eventID']) ? 'selected' : '' ?>>Select Event</option>
        <?php foreach ($events as $event): ?>
            <option value="<?= $event['id'] ?>" <?= (isset($_POST['eventID']) && $_POST['eventID'] == $event['id']) ? 'selected' : '' ?>>
                 <?= htmlspecialchars($event['name']) ?> - <?= date('M d, Y', strtotime($event['date'])) ?>
            </option>

        <?php endforeach; ?>
    </select>
    <br><br>

    <?php if ($selected_event_info): ?>
        <?php if (can_check_in($userID, $selected_event_info)) : ?>
            <input type="hidden" name="action" value="checkin">
            <input type="hidden" name="start_time" value="<?= date('Y-m-d\TH:i') ?>">
            <button type="submit" class="button success">Check-In</button>
        <?php elseif (can_check_out($userID, $selected_event_info)) : ?>
            <input type="hidden" name="action" value="checkout">
            <input type="hidden" name="end_time" value="<?= date('Y-m-d\TH:i') ?>">
            <button type="submit" class="button danger">Check-Out</button>
        <?php else: ?>
            <p>No available action for this event at this time.</p>
        <?php endif; ?>
    <?php endif; ?>
</form>

<?php if ($viewingSelf): ?>
    <a class="button cancel no-print" href="volunteerPortal.php">Return to Portal</a>
<?php else: ?>
    <a class="button cancel no-print" href="volunteerPortal.php?id=<?= htmlspecialchars($id) ?>">Return to Portal</a>
<?php endif; ?>

<script>
    document.getElementById('debug').innerText = "Select an event to see available actions.";
</script>
