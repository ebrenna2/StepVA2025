<?php

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");

    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }

    include_once('database/dbVideos.php');
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    include_once('domain/Video.php');

    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';

    $videos = retrieve_all_videos();

    if (isset($_GET['id'])) {
        $videoId = intval($_GET['id']);
        $selectedVideo = null;

        foreach ($videos as $video) {
            if ($video['id'] == $videoId) {
                $selectedVideo = $video;
                break;
            }
        }

        if ($selectedVideo) {
            echo json_encode($selectedVideo);
        } else {
            echo json_encode(["error" => "Video not found"]);
        }
        exit();
    }
    $allowed_type = null; // Default to an invalid type
    $allowed_type = $person->get_type();
    

    // Based on account type lets you view different videos

    if ($allowed_type == 'admin') {
        $allowed_type = 2; // Admin can see all
    } elseif ($allowed_type == 'participant') {
        $allowed_type = 1; // Participants see type=1 videos
    } elseif ($allowed_type == 'v' || $allowed_type == 'volunteer') {
        $allowed_type = 0; // Volunteers see type=0 videos
    }
    else{
        $allowed_type = 2;
    }
?>

<!DOCTYPE html>
<html>
    <head>
    <?php require('universal.inc'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title>Step VA System | Available Videos</title>
        <style>
            iframe {
                display: block;
                margin: 0 auto;
                width: 40%;
                height: 500px;
                overflow: auto;
            }

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
    </head>
    </head>
    <body>
        <?php require('header.php'); 

            // If a video id has been sent, the video with the correlating id gets deleted.
            // If its not set, user gets sent back to the deletion manager.
            if (isset($_POST['deletedVideoId'])) {
                $deletedVideoId = $_POST['deletedVideoId'];
                remove_video($deletedVideoId);
                header("Location: videoDeletionManager.php");
            } else {
                header("Location: videoDeletionManager.php");
            }
        
        ?>
    </body>
</html>

