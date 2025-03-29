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
require_once('include/input-validation.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $eventID = $_POST['eventID'];

    if (isset($_POST['checkin'])) {
        $start = $_POST['start_time'];
        if (can_check_in($userID, ['id' => $eventID])) {
            $success = check_in($userID, $eventID, $start);
            $message = $success ? "✅ Checked in successfully!" : "❌ Error: Could not check in.";
        } else {
            $message = "❌ Not allowed to check in again.";
        }
    }

    if (isset($_POST['checkout'])) {
        $end = $_POST['end_time'];
        if (can_check_out($userID, ['id' => $eventID])) {
            $success = check_out($userID, $eventID, $end);
            $message = $success ? "✅ Checked out successfully!" : "❌ Error: Could not check out.";
        } else {
            $message = "❌ Not allowed to check out again.";
        }
    }
}

$events = get_signed_up_events_by($userID);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Volunteer Check In / Out</title>
    <link rel="stylesheet" href="css/hours-report.css">
    <script>
    const eventsData = <?php echo json_encode(
        array_map(function ($e) use ($userID) {
            return [
                'id'          => $e['id'],
                'name'        => $e['name'],
                'start_time'  => $e['start_time'],
                'end_time'    => $e['end_time'],
                'canCheckIn'  => can_check_in($userID, $e),
                'canCheckOut' => can_check_out($userID, $e)
            ];
        }, $events)
    ); ?>;
    
    function handleEventSelect() {
        const eventSelect = document.getElementById('eventDropdown');
        const selectedID   = eventSelect.value;

        const checkIn      = document.getElementById('checkinBtn');
        const checkOut     = document.getElementById('checkoutBtn');
        const start        = document.getElementById('start_time');
        const end          = document.getElementById('end_time');

        const selected = eventsData.find(e => e.id == selectedID);

        if (!selected) {

            checkIn.disabled  = true;
            checkOut.disabled = true;
            start.disabled    = true;
            end.disabled      = true;
            return;
        }

        document.getElementById('eventID').value = selectedID;

        if (selected.canCheckIn) {
            start.disabled    = false;
            checkIn.disabled  = false;

            end.disabled      = true;
            checkOut.disabled = true;
        } else if (selected.canCheckOut) {
            start.disabled    = true;
            checkIn.disabled  = true;

            end.disabled      = false;
            checkOut.disabled = false;
        } else {
            start.disabled    = true;
            end.disabled      = true;
            checkIn.disabled  = true;
            checkOut.disabled = true;
        }
    }
</script>

</head>
<body>
    <h2>Volunteer Check-In / Check-Out</h2>

    <?php if (!empty($message)): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label for="eventDropdown">Select Event:</label>
        <select id="eventDropdown" onchange="handleEventSelect()">
            <option value="">-- Select an event --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="eventID" id="eventID" value="">

        <br><br>
        <label for="start_time">Start Time:</label>
        <input type="datetime-local" name="start_time" id="start_time" disabled>

        <br><br>
        <label for="end_time">End Time:</label>
        <input type="datetime-local" name="end_time" id="end_time" disabled>

        <br><br>
        <input type="submit" name="checkin" id="checkinBtn" value="Check In" disabled>
        <input type="submit" name="checkout" id="checkoutBtn" value="Check Out" disabled>
    </form>
</body>
</html>
