<?php
require_once('database/dbPersons.php');
require_once __DIR__ . '/include/fpdf.php';

session_start();
if (!isset($_SESSION['_id']) || $_SESSION['access_level'] < 1) {
    header('Location: login.php');
    exit;
}

$participants = get_participants_with_accommodations();

if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'true') {
    $pdf = new FPDF();
    $pdf->AddPage();
    
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Participant Accommodations Report', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'First Name', 1);
    $pdf->Cell(60, 10, 'Last Name', 1);
    $pdf->Cell(70, 10, 'Accommodation Needs', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($participants as $participant) {
        $pdf->Cell(60, 10, htmlspecialchars($participant['first_name']), 1);
        $pdf->Cell(60, 10, htmlspecialchars($participant['last_name']), 1);
        $pdf->Cell(70, 10, htmlspecialchars($participant['disability_accomodation_needs']), 1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'Participant_Accommodations.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once('universal.inc'); ?>
    <title>Step VA | Participant Accommodations</title>
    <link rel="stylesheet" href="css/base.css">
</head>
<body>
    <?php include('header.php'); ?>
    <h1>Participant Accommodations Report</h1>

    <div class="table-wrapper">
        <table class="general">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Accommodation Needs</th>
                </tr>
            </thead>
            <tbody class="standout">
                <?php if (count($participants) > 0): ?>
                    <?php foreach ($participants as $participant): ?>
                        <tr>
                            <td><?= htmlspecialchars($participant['first_name']) ?></td>
                            <td><?= htmlspecialchars($participant['last_name']) ?></td>
                            <td><?= htmlspecialchars($participant['disability_accomodation_needs']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-data">No participants with accommodations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form method="GET" class="no-print" style="margin-bottom: 1rem;">
        <button type="submit" name="generate_pdf" value="true">Download PDF</button>
    </form>

    </table>

    <a class="button cancel no-print" href="dashboard.php">Return to Dashboard</a>

</body>
</html>
