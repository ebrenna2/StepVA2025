<?php
require_once('database/dbPersons.php');
require_once __DIR__ . '/include/fpdf.php';

session_start();
if (!isset($_SESSION['_id']) || $_SESSION['access_level'] < 1) {
    header('Location: login.php');
    exit;
}

$participants = get_participants_with_accommodations();
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'first_asc':
            usort($participants, fn($a, $b) => strcmp($a['first_name'], $b['first_name']));
            break;
        case 'first_desc':
            usort($participants, fn($a, $b) => strcmp($b['first_name'], $a['first_name']));
            break;
        case 'last_asc':
            usort($participants, fn($a, $b) => strcmp($a['last_name'], $b['last_name']));
            break;
        case 'last_desc':
            usort($participants, fn($a, $b) => strcmp($b['last_name'], $a['last_name']));
            break;
    }
}

$grouped = [];
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

    if (isset($_GET['group']) && $_GET['group'] === 'accommodation') {
        foreach ($grouped as $accommodation => $group) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, htmlspecialchars($accommodation), 1, 1, 'L');
            $pdf->SetFont('Arial', '', 12);

            foreach ($group as $participant) {
                $pdf->Cell(60, 10, htmlspecialchars($participant['first_name']), 1);
                $pdf->Cell(60, 10, htmlspecialchars($participant['last_name']), 1);
                $pdf->Cell(70, 10, htmlspecialchars($participant['disability_accomodation_needs']), 1);
                $pdf->Ln();
            }
        }
    } else {
        foreach ($participants as $participant) {
            $pdf->Cell(60, 10, htmlspecialchars($participant['first_name']), 1);
            $pdf->Cell(60, 10, htmlspecialchars($participant['last_name']), 1);
            $pdf->Cell(70, 10, htmlspecialchars($participant['disability_accomodation_needs']), 1);
            $pdf->Ln();
        }
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
    <form method="GET" style="margin-bottom: 1rem;">
    <label for="sort">Sort By:</label>
    <select name="sort" id="sort">
        <option value="">-- Select --</option>
        <option value="first_asc" <?= ($_GET['sort'] ?? '') == 'first_asc' ? 'selected' : '' ?>>First Name (A-Z)</option>
        <option value="first_desc" <?= ($_GET['sort'] ?? '') == 'first_desc' ? 'selected' : '' ?>>First Name (Z-A)</option>
        <option value="last_asc" <?= ($_GET['sort'] ?? '') == 'last_asc' ? 'selected' : '' ?>>Last Name (A-Z)</option>
        <option value="last_desc" <?= ($_GET['sort'] ?? '') == 'last_desc' ? 'selected' : '' ?>>Last Name (Z-A)</option>
    </select>


    <button type="submit">Apply</button>
</form>

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
                    <?php if (isset($_GET['group']) && $_GET['group'] === 'accommodation'): ?>
                            <?php foreach ($grouped as $accommodation => $group): ?>
                                <tr>
                                    <td colspan="3" style="font-weight: bold; background-color: #f0f0f0;"><?= htmlspecialchars($accommodation) ?></td>
                                </tr>
                                <?php foreach ($group as $participant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($participant['first_name']) ?></td>
                                        <td><?= htmlspecialchars($participant['last_name']) ?></td>
                                        <td><?= htmlspecialchars($participant['disability_accomodation_needs']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($participants as $participant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($participant['first_name']) ?></td>
                                    <td><?= htmlspecialchars($participant['last_name']) ?></td>
                                    <td><?= htmlspecialchars($participant['disability_accomodation_needs']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-data">No participants with accommodations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form method="GET" class="no-print" style="margin-bottom: 1rem;">
    <input type="hidden" name="generate_pdf" value="true">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort'] ?? '') ?>">
    <input type="hidden" name="group" value="<?= htmlspecialchars($_GET['group'] ?? '') ?>">
    <button type="submit">Download PDF</button>
</form>


    </table>

    <a class="button cancel no-print" href="index.php">Return to Dashboard</a>

</body>
</html>
