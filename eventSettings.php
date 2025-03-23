<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // Debug: Log session state
    error_log("eventSettings.php: Session['_id'] = " . (isset($_SESSION['_id']) ? $_SESSION['_id'] : 'not set'));
    error_log("eventSettings.php: Session['access_level'] = " . (isset($_SESSION['access_level']) ? $_SESSION['access_level'] : 'not set'));

    // Restrict access to admins only (access level 2 or higher)
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
        error_log("eventSettings.php: Redirecting to login.php because access_level < 2");
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
        <title>Step VA System | Event Settings</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Event Settings</h1>
        <main class='dashboard'>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <!-- General Event-Related Items (Access Level 2 and above) -->
                <div class="dashboard-item" data-link="adminViewingEvents.php">
                    <i class="fa-solid fa-list"></i>
                    <span>View Events</span>
                </div>
                <div class="dashboard-item" data-link="viewAllEvents.php">
                    <img src="images/new-event.svg">
                    <span>Sign Up Event</span>
                </div>
                <div class="dashboard-item" data-link="viewMyUpcomingEvents.php">
                    <i class="fa-solid fa-list"></i>
                    <span>Upcoming Events</span>
                </div>
                <div class="dashboard-item" data-link="calendar.php">
                    <img src="images/view-calendar.svg">
                    <span>Calendar</span>
                </div>
                <!-- Admin-Only Event Items (Access Level 2 and above) -->
                <div class="dashboard-item" data-link="addEvent.php">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Event</span>
                </div>
                <div class="dashboard-item" data-link="editHours.php">
                    <i class="fa-regular fa-clock"></i>
                    <span>Change Event Hours</span>
                </div>
                <!-- Go Back to vmsDash.php -->
                <!-- Go Back for vmsroot (links to vmsDash.php) -->
                <?php if ($_SESSION['access_level'] >= 4 && $_SESSION['_id'] === 'vmsroot'): ?>
                    <div class="dashboard-item" data-link="vmsDash.php">
                        <img src="images/go-back.svg">
                        <span>Go Back</span>
                    </div>
                <?php endif ?>

                <!-- Go Back for regular admins (links to adminDash.php) -->
                <?php if ($_SESSION['access_level'] == 3 && $_SESSION['_id'] !== 'vmsroot'): ?>
                    <div class="dashboard-item" data-link="adminDash.php">
                        <img src="images/go-back.svg">
                        <span>Go Back</span>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </main>
    </body>
</html>