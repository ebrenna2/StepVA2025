<?php
session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    if (isset($_SESSION['change-password'])) {
        header('Location: changePassword.php');
    } else {
        header('Location: logout.php');
    }
    die();
}

include_once('database/dbPersons.php');
include_once('domain/Person.php');
require_once('database/dbMessages.php');


if (isset($_SESSION['_id'])) {
    $person = retrieve_person($_SESSION['_id']);
}
$notRoot = $person->get_id() != 'vmsroot';
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>STEP VA</title>
    <style>
        form {
            margin-left: 1in; 
            margin-right: 1in;
        }
    </style>
</head>
<body>
    <?php require_once('header.php'); ?>

    <form method="post" action="">
        <h3>Select Event to View Attendees</h3>
        

        <label for="event_id">Event:</label>
        <select name="event_id" id="event_id">
            <option value="">--Select an event--</option>
            <?php
                $conn = connect();
                $sqlEvents = "SELECT id, name, date FROM dbEvents ORDER BY date DESC";
                $eventsResult = $conn->query($sqlEvents);

                while ($eventRow = $eventsResult->fetch_assoc()) {
                    $selected = (isset($_POST['event_id']) && $_POST['event_id'] == $eventRow['id']) ? 'selected' : '';
                    echo "<option value='" . $eventRow['id'] . "' $selected>" . $eventRow['name'] . " (" . $eventRow['date'] . ")</option>";
                }
                
                $eventsResult->close();
            ?>
        </select>
        
        <br><br>
        <input type="submit" name="show_attendees" value="Show Attendees">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        $eventId = $_POST['event_id'];

        $sqlAttendees = "
            SELECT p.id, p.first_name, p.last_name
            FROM dbPersons p
            INNER JOIN dbAttendance a ON p.id = a.person_id
            WHERE a.event_id = ?
        ";

        $stmt = $conn->prepare($sqlAttendees);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $attendeesResult = $stmt->get_result();

        echo "<h3>Attendees for Event ID: $eventId</h3>";

        if ($attendeesResult->num_rows > 0) {
            echo "<ul>";
            while ($attendeeRow = $attendeesResult->fetch_assoc()) {
                echo "<li>" . $attendeeRow['first_name'] . " " . $attendeeRow['last_name'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No attendees found for this event.</p>";
        }
        $stmt->close();
    }

    $conn->close();
    ?>
 

    <form method="post" action="takeAttendance.php">
        <input type="submit" value="Back">
    </form>
</body>
</html>