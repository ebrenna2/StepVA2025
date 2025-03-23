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
require_once __DIR__ . '/include/fpdf.php';
$conn = connect();
if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] === 'true') {
    $eventID = $_GET['event_id'] ?? '';
    if (empty($eventID)) {
        die('No event chosen for PDF generation.');
    }
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Attendance Report for Event: ' . htmlspecialchars($eventID), 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'First Name', 1);
    $pdf->Cell(60, 10, 'Last Name', 1);
    $pdf->Cell(40, 10, 'Position', 1);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $sqlAttendees = "
        SELECT p.first_name, p.last_name, ep.position
        FROM dbPersons p
        JOIN dbEventPersons ep ON p.id = ep.userID
        WHERE ep.eventID = ?
    ";
    $stmt = $conn->prepare($sqlAttendees);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $attendeesResult = $stmt->get_result();
    while ($row = $attendeesResult->fetch_assoc()) {
        $pdf->Cell(60, 10, htmlspecialchars($row['first_name']), 1);
        $pdf->Cell(60, 10, htmlspecialchars($row['last_name']), 1);
        $positionLabel = ($row['position'] === 'v') ? 'Volunteer' : 'Participant';
        $pdf->Cell(40, 10, $positionLabel, 1);
        $pdf->Ln();
    }
    $stmt->close();
    $pdf->Output('I', 'Attendance_Report.pdf');
    exit;
}
if (isset($_SESSION['_id'])) {
    $person = retrieve_person($_SESSION['_id']);
}
$notRoot = ($person->get_id() !== 'vmsroot');
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
            font-family: Arial, sans-serif;
        }
        label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        select {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="button"] {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
        }
        h3 {
            text-align: center;
        }
        h4 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px;
        }
        .attendee-list {
            margin-left: 0;
            padding-left: 0;
            list-style-type: none;
        }
        .attendee-list li {
            padding-left: 1rem;
        }
        .no-attendees {
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php require_once('header.php'); ?>
    <div class="form-container">
        <form method="post" action="">
            <h3>Select Event to View Attendees</h3>
            <div class="form-group">
                <label for="event_id">Event:</label>
                <select name="event_id" id="event_id">
                    <option value="">--Select an event--</option>
                    <?php
                        $sqlEvents = "SELECT id, name, date FROM dbEvents ORDER BY date DESC";
                        $eventsResult = $conn->query($sqlEvents);
                        while ($eventRow = $eventsResult->fetch_assoc()) {
                            $selected = (isset($_POST['event_id']) && $_POST['event_id'] == $eventRow['id']) ? 'selected' : '';
                            echo "<option value='" . $eventRow['id'] . "' $selected>"
                               . $eventRow['name'] . " (" . $eventRow['date'] . ")</option>";
                        }
                        $eventsResult->close();
                    ?>
                </select>
            </div>
            <input type="submit" name="show_attendees" value="Show Attendees">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['event_id'])) {
            $eventID = $_POST['event_id'];
            $sqlEventName = "SELECT name FROM dbEvents WHERE id = ?";
            $stmtEventName = $conn->prepare($sqlEventName);
            $stmtEventName->bind_param("i", $eventID);
            $stmtEventName->execute();
            $resultEventName = $stmtEventName->get_result();
            $eventName = '';
            if ($eventRow = $resultEventName->fetch_assoc()) {
                $eventName = $eventRow['name'];
            }
            $stmtEventName->close();
            echo "<h3>Attendees for \"{$eventName}\" Event</h3>";
            $sqlAttendees = "
                SELECT p.id, p.first_name, p.last_name, ep.position
                FROM dbPersons p
                INNER JOIN dbEventPersons ep ON p.id = ep.userID
                WHERE ep.eventID = ?
            ";
            $stmt = $conn->prepare($sqlAttendees);
            if (!$stmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $stmt->bind_param("i", $eventID);
            $stmt->execute();
            $attendeesResult = $stmt->get_result();
            $volunteers = [];
            $participants = [];
            while ($attendeeRow = $attendeesResult->fetch_assoc()) {
                if (isset($attendeeRow['position'])) {
                    if ($attendeeRow['position'] === 'v') {
                        $volunteers[] = $attendeeRow;
                    } elseif ($attendeeRow['position'] === 'p') {
                        $participants[] = $attendeeRow;
                    }
                }
            }
            $stmt->close();
            echo "<h4>Volunteers</h4>";
            if (count($volunteers) > 0) {
                echo "<ul class='attendee-list'>";
                foreach ($volunteers as $vol) {
                    echo "<li>{$vol['first_name']} {$vol['last_name']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-attendees'>No volunteers found for this event.</p>";
            }
            echo "<h4>Participants</h4>";
            if (count($participants) > 0) {
                echo "<ul class='attendee-list'>";
                foreach ($participants as $part) {
                    echo "<li>{$part['first_name']} {$part['last_name']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-attendees'>No participants found for this event.</p>";
            }
            ?>
            <form method="get" action="">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventID); ?>">
                <input type="hidden" name="generate_pdf" value="true">
                <input type="submit" value="Download PDF">
            </form>
            <?php
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
