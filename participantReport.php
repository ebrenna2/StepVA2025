<?php
require_once('database/dbPersons.php');
require_once __DIR__ . '/include/fpdf.php';

session_start();
if (!isset($_SESSION['_id']) || $_SESSION['access_level'] < 1) {
    header('Location: login.php');
    exit;
}

$participants = get_participants_with_accommodations();

$name_sort = $_GET['name_sort'] ?? '';
$age_sort  = $_GET['age_sort'] ?? '';

if ($name_sort && $age_sort) {
    usort($participants, function($a, $b) use ($name_sort, $age_sort) {

        $ageA = date_diff(date_create($a['birthday']), date_create('today'))->y;
        $ageB = date_diff(date_create($b['birthday']), date_create('today'))->y;
        if ($age_sort === 'age_asc') {
            $result = $ageA <=> $ageB;
        } else { 
            $result = $ageB <=> $ageA;
        }
        if ($result === 0) {
            switch ($name_sort) {
                case 'first_asc':
                    $result = strcmp($a['first_name'], $b['first_name']);
                    break;
                case 'first_desc':
                    $result = strcmp($b['first_name'], $a['first_name']);
                    break;
                case 'last_asc':
                    $result = strcmp($a['last_name'], $b['last_name']);
                    break;
                case 'last_desc':
                    $result = strcmp($b['last_name'], $a['last_name']);
                    break;
                default:
                    $result = 0;
            }
        }
        return $result;
    });
} elseif ($age_sort) {
    usort($participants, function($a, $b) use ($age_sort) {
        $ageA = date_diff(date_create($a['birthday']), date_create('today'))->y;
        $ageB = date_diff(date_create($b['birthday']), date_create('today'))->y;
        return ($age_sort === 'age_asc') ? ($ageA <=> $ageB) : ($ageB <=> $ageA);
    });
} elseif ($name_sort) {
    switch ($name_sort) {
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
if (isset($_GET['group']) && $_GET['group'] !== '') {
    if ($_GET['group'] === 'accommodation') {
        foreach ($participants as $participant) {
            $accommodation = $participant['disability_accomodation_needs'] ?: 'None';
            $grouped[$accommodation][] = $participant;
        }
    } elseif ($_GET['group'] === 'birthday') {
        foreach ($participants as $participant) {
            $month = date('F', strtotime($participant['birthday']));
            $grouped[$month][] = $participant;
        }
    } elseif ($_GET['group'] === 'age') {
        foreach ($participants as $participant) {
            $age = date_diff(date_create($participant['birthday']), date_create('today'))->y;
            if ($age < 18) {
                $groupKey = 'Under 18';
            } elseif ($age < 30) {
                $groupKey = '18-29';
            } elseif ($age < 40) {
                $groupKey = '30-39';
            } else {
                $groupKey = '40+';
            }
            $grouped[$groupKey][] = $participant;
        }
    }
}

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

    if (isset($_GET['group']) && $_GET['group'] !== '' && !empty($grouped)) {
        foreach ($grouped as $groupKey => $group) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, htmlspecialchars($groupKey), 1, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            foreach ($group as $participant) {
                $pdf->Cell(60, 10, htmlspecialchars($participant['first_name']), 1);
                $pdf->Cell(60, 10, htmlspecialchars($participant['last_name']), 1);
                $pdf->Cell(70, 10, htmlspecialchars($participant['disability_accomodation_needs']), 1);
                $pdf->Ln();
            }
        }
    } else {
        // No grouping; simply list all participants
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
        <label for="name_sort">Sort By Name:</label>
        <select name="name_sort" id="name_sort">
            <option value="">-- Select --</option>
            <option value="first_asc" <?= (($_GET['name_sort'] ?? '') == 'first_asc') ? 'selected' : '' ?>>First Name (A-Z)</option>
            <option value="first_desc" <?= (($_GET['name_sort'] ?? '') == 'first_desc') ? 'selected' : '' ?>>First Name (Z-A)</option>
            <option value="last_asc" <?= (($_GET['name_sort'] ?? '') == 'last_asc') ? 'selected' : '' ?>>Last Name (A-Z)</option>
            <option value="last_desc" <?= (($_GET['name_sort'] ?? '') == 'last_desc') ? 'selected' : '' ?>>Last Name (Z-A)</option>
        </select>

        <label for="age_sort">Sort By Age:</label>
        <select name="age_sort" id="age_sort">
            <option value="">-- Select --</option>
            <option value="age_asc" <?= (($_GET['age_sort'] ?? '') == 'age_asc') ? 'selected' : '' ?>>Age (Youngest to Oldest)</option>
            <option value="age_desc" <?= (($_GET['age_sort'] ?? '') == 'age_desc') ? 'selected' : '' ?>>Age (Oldest to Youngest)</option>
        </select>

        <label for="group">Group By:</label>
        <select name="group" id="group">
            <option value="">-- Select --</option>
            <option value="accommodation" <?= (($_GET['group'] ?? '') === 'accommodation') ? 'selected' : '' ?>>Accommodation Needs</option>
            <option value="birthday" <?= (($_GET['group'] ?? '') === 'birthday') ? 'selected' : '' ?>>Birthday Month</option>
            <option value="age" <?= (($_GET['group'] ?? '') === 'age') ? 'selected' : '' ?>>Age Group</option>
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
                    <?php if (isset($_GET['group']) && $_GET['group'] !== '' && !empty($grouped)): ?>
                        <?php foreach ($grouped as $groupKey => $group): ?>
                            <tr>
                                <td colspan="3" style="font-weight: bold; background-color: #f0f0f0;"><?= htmlspecialchars($groupKey) ?></td>
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
        <input type="hidden" name="name_sort" value="<?= htmlspecialchars($_GET['name_sort'] ?? '') ?>">
        <input type="hidden" name="age_sort" value="<?= htmlspecialchars($_GET['age_sort'] ?? '') ?>">
        <input type="hidden" name="group" value="<?= htmlspecialchars($_GET['group'] ?? '') ?>">
        <button type="submit">Download PDF</button>
    </form>

    <a class="button cancel no-print" href="index.php">Return to Dashboard</a>
</body>
</html>
