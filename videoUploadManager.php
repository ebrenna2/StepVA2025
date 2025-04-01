<?php
session_cache_expire(30);
session_start();
if (!isset($_SESSION['_id']) || !isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    if (isset($_SESSION['change-password'])) {
        header('Location: changePassword.php');
    } else {
        header('Location: login.php');
    }
    exit();
}


$userID = $_SESSION['_id'];
$accessLevel = $_SESSION['access_level'];

    include "domain/Video.php";
    include "database/dbVideos.php";

    $uploadSuccess = false;
    $alertToggle = false;

    // Post request method only used when sending new video data to the server.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // If, at any point, a video in the database has type equal to -1 then something went horribly wrong.
        $newVideoTypeUnformatted = $_POST["videoType"];
        $newVideoTypeFormatted = -1;

        // Translates who should view the video into the 'type' column in dbVideos.php.
        // 'type' is meant to represent the required access level to view the video.
        if ($newVideoTypeUnformatted === "volunteer") {
            $newVideoTypeFormatted = 0;
        } else if ($newVideoTypeUnformatted === "participant") {
            $newVideoTypeFormatted = 1;
        } else if ($newVideoTypeUnformatted === "admin") {
            $newVideoTypeFormatted = 2;
        } else { // Fail-safe. 
            echo('Unexpected error in form submission. Please try again.');
        }

        // ID is automatically incremented in the db and does not need to be given a value at construction
        $newVideo = new Video(
            '',
            $_POST["videoURL"], 
            $_POST["videoTitle"],
            $_POST["videoSynopsis"],
            $newVideoTypeFormatted
        );

        // Adds video to database
        $uploadSuccess = add_video($newVideo);
        $alertToggle = true;

    }

 $loggedIn = false; 
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

if (!$loggedIn) {
    header('Location: login.php');
    die();
}
?>
<!DOCTYPE html>
<html>

    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Upload Video</title>
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

        <?php require_once('header.php') ?>
        <?php require_once('database/dbEvents.php');?>
        <h1>Upload Video Resources</h1>

        <form action="videoUploadManager.php" id="uploadVideoForm" method="POST">
            <br>

            <!-- Here is where the new video information comes from. See top of file to see additional details. -->
            <label for="videoURL">Embed Video URL:</label>
            <input type="url" id="videoURL" name="videoURL" required="true" size="80"><br><br>

            <label for="videoTitle">Video Title:</label>
            <input type="text" id="videoTitle" name="videoTitle" required="true"><br><br>

            <label for="videoSynopsis">Video Synopsis:</label>
            <input type="text" id="videoSynopsis" name="videoSynopsis" required="true"><br><br>

            <label for="videoType">Who should see the videos?</label>
            <select name="videoType" id="videoType">
                <option value="volunteer">Volunteer</option>
                <option value="participant">Participant</option>
                <option value="admin">All</option>
            </select><br><br>

            
            <button type="submit">Submit</button>
        </form>
        
        <!-- Aesthetic Formatting -->
        <p>
        <br>
        <br>
        </p>

        <div id="calendar-footer" style="width: 50%; margin: 0 auto;">
            <a class="button cancel" href="adminPortal.php">Return to Dashboard</a>
        </div>

        <br>

       <script>
       var uploadSuccess = <?php echo json_encode($uploadSuccess); ?>;
        var alertToggle = <?php echo json_encode($alertToggle); ?>;

        if (alertToggle) {
            if (uploadSuccess) {
                alert("Your video has been successfully uploaded!");
            } else {
                alert("There was an error in video uploading. Please try again.");
                }
                }
        </script>
        </body>     
</html>

