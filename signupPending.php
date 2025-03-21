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
            <style>
            .centered {
            text-align: center;
            }
            </style>

            <?php require_once('header.php') ?>
            <h1>Sign-Up Request Sent!</h1>
            <p class="centered">The administrator will review your request shortly</p>
        </body>
    </html>