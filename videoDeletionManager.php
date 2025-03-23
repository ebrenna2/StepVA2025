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

    include_once('database\dbVideos.php');
    include_once('database\dbPersons.php');
    include_once('domain\Person.php');
    include_once('domain\Video.php');

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
    <body>
        <?php require('header.php'); ?>
        <h1>Video Deletion</h1>
        
        <div>
            <label for="videoSelect">Select a Video:</label>
            <select id="videoSelect" onchange="loadVideo(this.value); setHiddenVideoVariable();">
                <option value="">-- Choose a Video --</option>
                <?php foreach ($videos as $video) {
                    // Filters videos based on account type
                    if ($video['type'] == $allowed_type || $allowed_type == 2) {
                        echo "<option value='{$video['id']}'>{$video['title']}</option>";
                    }
                    
                } ?>
            </select>
        </div>

        <div>
            <br>
            <iframe id="videoFrame" src="" title="Selected Video" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            <br>
            <h2 id="videoTitle" style="text-align: center;"></h2>
            <br>
            <p id="videoDescription" style="text-align:center;"></p>
            <br>
        </div>

        <script>
            var selectedVideo = '';

            function deleteVideo(){
                var videoSelectDropdown = document.getElementById("videoSelect").value;
                alert('In Delete Video!')
                window.location.href = 'videoDeletionHelper.php?id=' + encodeURIComponent(videoSelectionDropdown);
            }

            function getSelectedVideoVariable() {
                return selectedVideo;
            }

            function setHiddenVideoVariable() {
                var selectedVideo = document.getElementById("videoSelect").value;
            }

            function getSelectedVideo() {
                var videoSelectDropdown = document.getElementById("videoSelect");
                var selectedValue = videoSelectDropdown.value;
                return selectedValue;
            }
        
            function loadVideo(videoId) {
                if (videoId) {
                    fetch(`viewVideos.php?id=${videoId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error('Error:', data.error);
                            } else {
                                document.getElementById("videoFrame").src = data.url;
                                document.getElementById("videoTitle").innerText = data.title;
                                document.getElementById("videoDescription").innerText = data.synopsis;
                            }
                        })
                        .catch(error => console.error('Error fetching video:', error));
                }
            }

        </script>

        <form action="videoDeletionManager.php" id="deleteVideoForm" method="POST" onsubmit="deleteVideo()">
            <input type="hidden" id="deletedVideoId" name="deletedVideoId" value=selectedVideo>
            <button type="submit">Delete</button>
        </form>
        <br>

    </body>

</html>