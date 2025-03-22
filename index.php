<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // Debug: Log session state
    error_log("index.php: Session['_id'] = " . (isset($_SESSION['_id']) ? $_SESSION['_id'] : 'not set'));
    error_log("index.php: Session['access_level'] = " . (isset($_SESSION['access_level']) ? $_SESSION['access_level'] : 'not set'));

    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            error_log("index.php: Redirecting to changePassword.php due to change-password flag");
            header('Location: changePassword.php');
        } else {
            error_log("index.php: Redirecting to login.php because access_level is invalid or not set");
            header('Location: login.php');
        }
        die();
    }
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title>Step VA System | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Dashboard</h1>
        <main class='dashboard'>
            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <?php
                    require_once('database/dbMessages.php');
                    $unreadMessageCount = get_user_unread_count($person->get_id());
                    $inboxIcon = 'inbox.svg';
                    if ($unreadMessageCount) {
                        $inboxIcon = 'inbox-unread.svg';
                    }
                    
                ?>
                
                <!-- VMSROOT ONLY -->
                 <?php if ($_SESSION['access_level'] >= 4): ?>
                <div class="dashboard-item" data-link="registerAdmin.php">
                        <img src="images/add-person.svg">
                        <span>Add Admin</span>
                    </div>
                <?php endif ?>
                <!-- Everyone's Dashboard -->
                <div class="dashboard-item" data-link="inbox.php">
                    <img src="images/<?php echo $inboxIcon ?>">
                    <span>Notifications<?php 
                        if ($unreadMessageCount > 0) {
                            echo ' (' . $unreadMessageCount . ')';
                        }
                    ?></span>
                </div>
                <div class="dashboard-item" data-link="calendar.php">
                    <img src="images/view-calendar.svg">
                    <span>View Calendar</span>
                </div>
                <div class="dashboard-item" data-link="viewAllEvents.php">
                    <img src="images/new-event.svg">
                    <span>Sign-Up for Event</span>
                </div>
                
                <!-- ADMIN ONLY -->
                <?php if ($_SESSION['access_level'] >= 2): ?>
                    <div class="dashboard-item" data-link="addEvent.php">
                        <i class="fa-solid fa-plus" font-size: 70px;></i>
                        <span>Create Event</span>
                    </div>

                    <div class="dashboard-item" data-link="viewAllEventSignUps.php">
                        <i class="fa-solid fa-users"></i>
                        <span><center>View Pending Sign-Ups <?php 
                        require_once('database/dbEvents.php');
                        require_once('database/dbPersons.php');
                        $pendingsignups = all_pending_names();
                        if (sizeof($pendingsignups) > 0) {
                            echo ' (' . sizeof($pendingsignups) . ')';
                        }
                    ?></center></span>
                    </div>
                    
                    <div class="dashboard-item" data-link="personSearch.php">
                        <img src="images/person-search.svg">
                        <span>Find Volunteer</span>
                    </div>

                    <div class="dashboard-item" data-link="adminViewingEvents.php">
                        <i class="fa-solid fa-list"></i>
                        <span>View Events</span>
                    </div>
              
                    <div class="dashboard-item" data-link="adminRegisterVolunteer.php">
                        <img src="images/add-person.svg">
                        <span>Register Volunteer</span>
                    </div>

                    <div class="dashboard-item" data-link="editHours.php">
                        <i class="fa-regular fa-clock"></i>
                        <span><center>View & Change Event Hours</center></span>
                    </div>
                    <div class="dashboard-item" data-link="resources.php">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        <span><center>Upload Resources</center></span>
                    </div>
                    
                    <div class="dashboard-item" data-link="reportsDash.php">
                        <img src="images/create-report.svg">
                        <span><center>Reports Page</center></span>
                    </div>
                <?php endif ?>
                <!-- FOR VOLUNTEERS ONLY -->
                <?php if ($_SESSION['access_level'] >= 1): ?>
                    <div class="dashboard-item" data-link="viewProfile.php">
                        <img src="images/view-profile.svg">
                        <span>View Profile</span>
                    </div>
                    <div class="dashboard-item" data-link="editProfile.php">
                        <img src="images/manage-account.svg">
                        <span>Edit Profile</span>
                    </div>
                    <div class="dashboard-item" data-link="viewVideos.php">
                        <img src="images/camera.svg">
                        <span>Training Videos</span>
                    </div>
                    <div class="dashboard-item" data-link="viewMyUpcomingEvents.php">
                        <i class="fa-solid fa-list"></i>
                        <span>My Upcoming Events</span>
                    </div>
                <?php endif ?>
                <?php if ($_SESSION['access_level'] >= 1): ?>
                    <div class="dashboard-item" data-link="volunteerReport.php">
                        <img src="images/volunteer-history.svg">
                        <span><center>View Volunteering Report</center></span>
                    </div>
                <div class="dashboard-item" data-link="editHours.php">
                        <img src="images/add-person.svg">
                        <span><center>View & Change My Event Hours</center></span>
                    </div>
                <?php endif ?>
                <!-- For Participants Only -->

                <!-- FOR FAMILY LEADERS ONLY -->
                <?php if (is_family_leader($person->get_id())) : ?>
                        <div class="dashboard-item" data-link="familyManagementPortal.php">
                            <img src="images/people.svg">
                            <span>Manage Family</span>
                        </div>
                <?php endif ?>

                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>
                
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
                <!-- autoredirects home as volunteer currently -->
                <!-- <div class="dashboard-item" data-link="editHours.php">
                        <img src="images/add-person.svg">
                        <span>View & Change Event Hours</span>
                </div> -->
            </div>
        </main>
    </body>
</html>