<?php
// Volunteer = 0
// Participant = 1
// All = 2
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
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
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

?>
<!DOCTYPE html>
<html>
    <style>

        /* Formatting logic */
        .left{
            float: left;
        }
        .right {
            float: right;
        }
        .middle{
            text-align: center;
        }

        /* Video display logic */
        iframe{
            display: block;
            margin: 0 auto;
            width: 40%;
            height: 300px;
            overflow: auto;
        }

        /* Additional video links logic */
        .dropdown-content {
            display: block;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            padding: 12px 16px;
            color: black;
            text-decoration: none;
            display: block;
            text-align: center;
        }

    </style>
    <head>
        <?php require('universal.inc'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title>Step VA System | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Available Videos</h1>
        
        <div>
            <label for="videoSelect">Select a Video:</label>
            <select id="videoSelect" onchange="loadVideo(this.value)">
                <option value="">-- Choose a Video --</option>
                <?php foreach ($videos as $video) {
                  echo "<option value='{$video['id']}'>{$video['title']}</option>";
              } ?>
            </select>
        </div>

            <!-- Here is where we display any additional videos by pulling from the database. -->
            <div>
                <button disabled>Additional Videos:</button>
                <div class='dropdown-content'>
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Link 1</a>
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Link 2</a>
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Link 3</a>
                </div>
            </div>
        </main>
    </body>
</html>

