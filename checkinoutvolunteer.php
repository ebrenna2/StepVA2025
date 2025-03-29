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

require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once('include/input-validation.php');
    $args = sanitize($_GET);
    $id = $args['id'];
    $viewingSelf = ($id == $userID);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $eventID = $_POST['eventID'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $success = insert_person_hours($userID, $eventID, $start, $end);
    if ($success) {
        echo "<p>Check-in/out recorded successfully!</p>";
    } else {
        echo "<p>Error: Could not record check-in/out.</p>";
    }
}

$events = get_signed_up_events_by($userID);
?>

<!DOCTYPE html>
<html>
<head>
<?php require_once('universal.inc'); ?>
    <title>Step VA | Volunteer Check In / Out </title>
    <link rel="stylesheet" href="css/hours-report.css">
</head>
<body>
<h2>Volunteer Check-In / Check-Out</h2>
<form method="post">
    <label for="eventID">Event:</label>
    <select name="eventID" id="eventID" required>
        <?php foreach ($events as $event): ?>
            <option value="<?= $event['id'] ?>">
                <?= htmlspecialchars($event['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <label for="start_time">Start Time:</label>
    <input type="datetime-local" name="start_time" required><br><br>
    <label for="end_time">End Time:</label>
    <input type="datetime-local" name="end_time" required><br><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>
