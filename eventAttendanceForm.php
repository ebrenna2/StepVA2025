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

// Include FPDF
require('fpdf.php');

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
            font-family: Arial, sans-serif;
        }

        label {
            font-weight: bold;
            display: inline-block;
            width: 150px; /* Adjust width for alignment */
            margin-bottom: 10px;
        }

        select {
            padding: 8px;
            width: 100%; /* Make the dropdown fill the available space */
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
            margin: 0 auto; /* Center the form */
        }

        .form-group {
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px; /* Limit the width of the form */
        }

        .attendee-list {
            margin-left: 0; /* Remove additional left margin */
            padding-left: 0;
            list-style-type: none; /* Remove default list styling */
        }

        .attendee-list li {
            padding-left: 1rem; /* Indent list items */
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
            </div>
            
            <input type="submit" name="show_attendees" value="Show Attendees">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id']) && !empty($_POST['event_id'])) {
            $eventId = $_POST['event_id'];

            // Fetch event name for the selected event
            $sqlEventName = "SELECT name FROM dbEvents WHERE id = ?";
            $stmtEventName = $conn->prepare($sqlEventName);
            $stmtEventName->bind_param("i", $eventId);
            $stmtEventName->execute();
            $resultEventName = $stmtEventName->get_result();

            $eventName = '';
            if ($eventRow = $resultEventName->fetch_assoc()) {
                $eventName = $eventRow['name'];
            }
            $stmtEventName->close();

            echo "<h3>Attendees for \"$eventName\" Event</h3>";

            // Correct query to include the 'position' column from the dbeventpersons table
            $sqlAttendees = "
                SELECT p.id, p.first_name, p.last_name, ep.position
                FROM dbPersons p
                INNER JOIN dbeventpersons ep ON p.id = ep.userID
                WHERE ep.eventID = ?
            ";

            $stmt = $conn->prepare($sqlAttendees);
            if (!$stmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("i", $eventId);
            $stmt->execute();
            $attendeesResult = $stmt->get_result();

            $volunteers = [];
            $participants = [];

            // Process the results and separate by 'position'
            while ($attendeeRow = $attendeesResult->fetch_assoc()) {
                if (isset($attendeeRow['position'])) { // Ensure 'position' exists
                    if ($attendeeRow['position'] == 'v') {
                        $volunteers[] = $attendeeRow;  // Add to volunteers array
                    } elseif ($attendeeRow['position'] == 'p') {
                        $participants[] = $attendeeRow;  // Add to participants array
                    }
                } else {
                    echo "<p>Warning: 'position' column is missing in the result.</p>";
                }
            }

            // Always display "Volunteers" header, even if there are no volunteers
            echo "<h4>Volunteers</h4>";

            if (count($volunteers) > 0) {
                echo "<ul class='attendee-list'>";
                foreach ($volunteers as $volunteer) {
                    echo "<li>" . $volunteer['first_name'] . " " . $volunteer['last_name'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-attendees'>No volunteers found for this event.</p>";
            }

            // Always display "Participants" header, even if there are no participants
            echo "<h4>Participants</h4>";
            if (count($participants) > 0) {
                echo "<ul class='attendee-list'>";
                foreach ($participants as $participant) {
                    echo "<li>" . $participant['first_name'] . " " . $participant['last_name'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-attendees'>No participants found for this event.</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <form method="get" action="">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventId); ?>">
            <input type="hidden" name="generate_pdf" value="true">
            <input type="submit" value="Download PDF">
        </form>

        <?php
            if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'true') {
                if (!class_exists('FPDF')) {
                require('fpdf.php');
            }

            $pdf = new FPDF();
            $pdf->AddPage();

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Attendance Report for Event: ' . htmlspecialchars($eventId), 0, 1, 'C');
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
                JOIN dbEventPersons ep ON p.id = ep.user_id
                WHERE ep.event_id = ?
            ";

            $stmt = $conn->prepare($sqlAttendees);
            $stmt->bind_param("i", $eventId);
            $stmt->execute();
            $attendeesResult = $stmt->get_result();

            while ($attendeeRow = $attendeesResult->fetch_assoc()) {
                $pdf->Cell(60, 10, htmlspecialchars($attendeeRow['first_name']), 1);
                $pdf->Cell(60, 10, htmlspecialchars($attendeeRow['last_name']), 1);
                $pdf->Cell(40, 10, htmlspecialchars($attendeeRow['position']) === 'v' ? 'Volunteer' : 'Participant', 1);
                $pdf->Ln();
            }

            $stmt->close();
            $conn->close();

            $pdf->Output('I', 'Attendance_Report.pdf');
            exit;
            }
        ?>
    </div>
</body>
</html>