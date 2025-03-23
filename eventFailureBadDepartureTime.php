<?php
    session_cache_expire(30);
    session_start();
    // Set the redirect header based on childID presence
    $redirectUrl = "viewAllEvents.php";
    if (isset($_GET['childID'])) {
        $redirectUrl .= "?childID=" . urlencode($_GET['childID']);
    }
    header("Refresh: 2; url=" . $redirectUrl);
?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Step VA | Sign-Up for Event</title>
        </head>
        <body>
            <?php require_once('header.php') ?>
            <h1>Oops! You tried to leave earlier than you arrive.</h1>
        </body>
    </html>