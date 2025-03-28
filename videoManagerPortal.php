<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // Debug: Log session state
    error_log("adminPortal.php: Session['_id'] = " . (isset($_SESSION['_id']) ? $_SESSION['_id'] : 'not set'));
    error_log("adminPortal.php: Session['access_level'] = " . (isset($_SESSION['access_level']) ? $_SESSION['access_level'] : 'not set'));

    // Restrict access to admins only (access level 3 or higher)
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 3) {
        error_log("adminPortal.php: Redirecting to login.php because access_level < 3");
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
        <title>Step VA System | Admin Portal</title>
    </head>
    <body>
    <style>

    form {
        width: 50%;
        margin: 0 auto;
        text-align: center;
    }

    label {
        margin: 0 auto;
        text-align: center;
    }

    </style>

        <?php require('header.php'); ?>
        <h1>Video Management Portal</h1>
        <main class='dashboard'>
            <div id="dashboard">
                <!-- For all admins (access level 3 and above) -->
                <div class="dashboard-item" data-link="viewVideos.php">
                    <img src="images/camera.svg">
                    <span>View Training Videos</span>
                </div>
                <div class="dashboard-item" data-link="videoUploadManager.php">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload Videos</span>
                </div>
                <div class="dashboard-item" data-link="videoDeletionManager.php">
                    <i class="fa-solid fa-video-slash"></i>
                    <span>Delete Videos</span>
                </div>
                <div class="dashboard-item" data-link="editVideos.php">
                    <img src="images/editvideo.svg">
                    <span>Edit Videos</span>
                </div>

                <!-- back to vmsDash -->
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