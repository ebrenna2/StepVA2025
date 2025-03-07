<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
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
    if ($isAdmin && isset($_GET['id'])) {
        require_once('include/input-validation.php');
        $args = sanitize($_GET);
        $id = $args['id'];
        $viewingSelf = $id == $userID;
    } else {
        $id = $_SESSION['_id'];
        $viewingSelf = true;
    }

    $dateFrom = (isset($_GET['date_from']) && $_GET['date_from'] != '') ? $_GET['date_from'] : null;
    $dateTo = (isset($_GET['date_to']) && $_GET['date_to'] != '') ? $_GET['date_to'] : null;

    $events = get_events_attended_by($id);
    $volunteer = retrieve_person($id);

    // Filter events by date range
    if ($dateFrom || $dateTo) {
        $events = array_filter($events, function($evt) use ($dateFrom, $dateTo) {
            $evtDate = strtotime($evt['date']); 
            if ($dateFrom && $dateTo) {
                return ($evtDate >= strtotime($dateFrom)) && ($evtDate <= strtotime($dateTo));
            }

            elseif ($dateFrom) {
                return $evtDate >= strtotime($dateFrom);
            }

            elseif ($dateTo) {
                return $evtDate <= strtotime($dateTo);
            }

            return true;
        });
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Volunteer History</title>
    <link rel="stylesheet" href="css/hours-report.css">
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Volunteer History Report</h1>
    <main class="hours-report">
        <?php if (!$volunteer): ?>
            <p class="error-toast">That volunteer does not exist!</p>
        <?php else: ?>
            <form method="GET" class="no-print" style="margin-bottom: 1rem;">
                <?php if ($isAdmin && isset($id)): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>
                <label for="date_from">Start Date:</label>
                <input type="date" id="date_from" name="date_from" value="<?php echo $dateFrom ? htmlspecialchars($dateFrom) : ''; ?>">
                <label for="date_to">End Date:</label>
                <input type="date" id="date_to" name="date_to" value="<?php echo $dateTo ? htmlspecialchars($dateTo) : ''; ?>">
                <button type="submit">Filter</button>
            </form>
            <?php if ($viewingSelf): ?>
                <h2 class="no-print">Your Volunteer Hours</h2>
            <?php else: ?>
                <h2 class="no-print">Hours Volunteered by <?php echo $volunteer->get_first_name() . ' ' . $volunteer->get_last_name(); ?></h2>
            <?php endif; ?>
            <h2 class="print-only">Hours Volunteered by <?php echo $volunteer->get_first_name() . ' ' . $volunteer->get_last_name(); ?></h2>
            <?php if (count($events) > 0): ?>
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Event</th>
                                <th></th>
                                <th class="align-right">Hours</th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php
                                require_once('include/output.php');
                                $total_hours = 0;
                                foreach ($events as $event) {
                                    $time = fetch_volunteering_hours($id, $event['id']);
                                    if ($time == -1) {
                                        continue;
                                    }
                                    $hours = $time / 3600;
                                    $total_hours += $hours;
                                    $dateFmt = date('m/d/Y', strtotime($event['date']));
                                    echo '<tr>
                                            <td>' . $dateFmt . '</td>
                                            <td>' . $event["name"] . '</td>
                                            <td></td>
                                            <td class="align-right">' . floatPrecision($hours, 2) . '</td>
                                          </tr>';
                                }
                                echo "<tr class='total-hours'>
                                        <td></td>
                                        <td></td>
                                        <td class='total-hours'>Total Hours</td>
                                        <td class='align-right'>" . floatPrecision($total_hours, 2) . "</td>
                                      </tr>";
                            ?>
                        </tbody>
                    </table>
                    <p class="print-only">I hereby certify that this volunteer has contributed the above volunteer hours to the Step VA organization.</p>
                    <table id="signature-table" class="print-only">
                        <tbody>
                            <tr>
                                <td>Admin Signature: ______________________________________ Date: <?php echo date('m/d/Y'); ?></td>
                            </tr>
                            <tr>
                                <td>Print Admin Name: _____________________________________</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="no-print" onclick="window.print()" style="margin-bottom: -.5rem">Print</button>
            <?php else: ?>
                <p>There are no volunteer hours to report <?php if ($dateFrom || $dateTo) echo "in this date range"; ?>.</p>
            <?php endif; ?>
            <?php if ($viewingSelf): ?>
                <a class="button cancel no-print" href="viewProfile.php">Return to Profile</a>
            <?php else: ?>
                <a class="button cancel no-print" href="viewProfile.php?id=<?php echo htmlspecialchars($id); ?>">Return to Profile</a>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
