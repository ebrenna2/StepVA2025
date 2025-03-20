<?php
require_once __DIR__ . '/include/fpdf.php';

// Make session information accessible, allowing us to associate data with the logged-in user.
session_cache_expire(30);
session_start();
$id = null;          
$volunteer = null;  
$events = [];
ini_set("display_errors", 1);
error_reporting(E_ALL);
$loggedIn = false;
$accessLevel = 0;
$userID = null;

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

if (!$loggedIn) {
    header('Location: login.php');
    die();
}

$isAdmin = $accessLevel >= 2;
require_once('database/dbPersons.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once('include/input-validation.php');
    $args = sanitize($_GET);
    $id = $args['id'];
    $viewingSelf = ($id == $userID);
}


$dateFrom = (isset($_GET['date_from']) && $_GET['date_from'] != '') ? $_GET['date_from'] : null;
$dateTo = (isset($_GET['date_to']) && $_GET['date_to'] != '') ? $_GET['date_to'] : null;

$events = get_events_attended_by($id);
$volunteer = retrieve_person($id);

// Filter events by date range
if ($dateFrom || $dateTo) {
    $events = array_filter($events, function ($evt) use ($dateFrom, $dateTo) {
        $evtDate = strtotime($evt['date']);
        if ($dateFrom && $dateTo) {
            return ($evtDate >= strtotime($dateFrom)) && ($evtDate <= strtotime($dateTo));
        } elseif ($dateFrom) {
            return $evtDate >= strtotime($dateFrom);
        } elseif ($dateTo) {
            return $evtDate <= strtotime($dateTo);
        }
        return true;
    });
}

// Check if the user clicked the "Generate PDF" button
if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'true') {
    // Initialize FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Volunteer History Report', 0, 1, 'C');

    // Volunteer details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(10); // Line break
    $pdf->Cell(0, 10, 'Volunteer: ' . $volunteer->get_first_name() . ' ' . $volunteer->get_last_name(), 0, 1);

    // Date range
    if ($dateFrom || $dateTo) {
        $pdf->Cell(0, 10, 'Date Range: ' . ($dateFrom ? $dateFrom : 'N/A') . ' - ' . ($dateTo ? $dateTo : 'N/A'), 0, 1);
    }

    // Table header
    $pdf->Ln(10);
    $pdf->Cell(40, 10, 'Date', 1);
    $pdf->Cell(100, 10, 'Event', 1);
    $pdf->Cell(40, 10, 'Hours', 1);
    $pdf->Ln();

    // Loop through events and add them to the table
    $total_hours = 0;
    foreach ($events as $event) {
        $time = fetch_volunteering_hours($id, $event['id']);
        if ($time == -1) continue;

        $hours = $time / 3600;
        $total_hours += $hours;
        $dateFmt = date('m/d/Y', strtotime($event['date']));
        $pdf->Cell(40, 10, $dateFmt, 1);
        $pdf->Cell(100, 10, $event['name'], 1);
        $pdf->Cell(40, 10, number_format($hours, 2), 1);
        $pdf->Ln();
    }

    // Total Hours
    $pdf->Ln(10);
    $pdf->Cell(140, 10, 'Total Hours', 1);
    $pdf->Cell(40, 10, number_format($total_hours, 2), 1);

    // Signature table
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'I hereby certify that this volunteer has contributed the above volunteer hours to the Step VA organization.', 0, 1);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Admin Signature: ______________________________________ Date: ' . date('m/d/Y'), 0, 1);
    $pdf->Cell(0, 10, 'Print Admin Name: _____________________________________', 0, 1);

    // Output the PDF
    $pdf->Output('I', 'Volunteer_History_Report.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Participant Accommodations</title>
    <link rel="stylesheet" href="css/hours-report.css">
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Participant Accommodations Report</h1>

    </main>
</body>
</html>
