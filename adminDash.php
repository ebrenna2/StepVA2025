<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // Debug: Log session state
    error_log("adminDash.php: Session['_id'] = " . (isset($_SESSION['_id']) ? $_SESSION['_id'] : 'not set'));
    error_log("adminDash.php: Session['access_level'] = " . (isset($_SESSION['access_level']) ? $_SESSION['access_level'] : 'not set'));

    // Restrict access to regular admins (access level 3, but not vmsroot)
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] != 3 || (isset($_SESSION['_id']) && $_SESSION['_id'] === 'vmsroot')) {
        error_log("adminDash.php: Redirecting to login.php because user is not a regular admin (access_level != 3 or is vmsroot)");
        header('Location: login.php');
        die();
    }

    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title>Step VA System | Admin Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Admin Dashboard</h1>
        <main class='dashboard'>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <div class="dashboard-item" data-link="viewall.php">
                    <img src="images/view-all.svg">
                    <span>View All Items</span>
                </div>
                <div class="dashboard-item" data-link="volunteerPortal.php">
                    <img src="images/people.svg">
                    <span>View Volunteer Portal</span>
                </div>
                <div class="dashboard-item" data-link="participantPortal.php">
                    <img src="images/person.svg">
                    <span>View Participant Portal</span>
                </div>
                <div class="dashboard-item" data-link="adminPortal.php">
                    <img src="images/admin.svg">
                    <span>View Admin Portal</span>
                </div>
                <div class="dashboard-item" data-link="eventSettings.php">
                    <img src="images/settings.svg">
                    <span>Event Settings</span>
                </div>
                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>