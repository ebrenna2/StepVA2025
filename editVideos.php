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
include_once('domain/Video.php');

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_video"])) {
    $video_id = $_POST["video_id"];
    $video_url = $_POST["video_url"];
    $video_title = $_POST["video_title"];
    $video_synopsis = $_POST["video_synopsis"];
    $video_type = $_POST["video_type"];

    // Update the video using the update_video function
    $result = update_video($video_id, $video_url, $video_title, $video_synopsis, $video_type);

    if ($result) {
        $message = "Video updated successfully!";
    } else {
        $message = "Failed to update video.";
    }
}

// Retrieve all videos for the dropdown
$videos = retrieve_all_videos();
?>

<!DOCTYPE html>
<html>

<head>
    <?php require('universal.inc'); ?>
    <title>Step VA System | Edit Videos</title>
    <script>
        function loadVideoData(videoId) {
            if (videoId) {
                fetch(`viewVideos.php?id=${videoId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error:', data.error);
                        } else {
                            document.getElementById("video_id").value = data.id;
                            document.getElementById("video_url").value = data.url;
                            document.getElementById("video_title").value = data.title;
                            document.getElementById("video_synopsis").value = data.synopsis;
                            document.getElementById("video_type").value = data.type;
                        }
                    })
                    .catch(error => console.error('Error fetching video:', error));
            }
        }
    </script>
</head>

<body>
    <?php require('header.php'); ?>
    <h1>Edit Videos</h1>

    <!-- Display success or error message if exists -->
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <div>
        <label for="videoSelect">Select a Video:</label>
        <select id="videoSelect" onchange="loadVideoData(this.value)">
            <option value="">-- Choose a Video --</option>
            <?php foreach ($videos as $video) {
                echo "<option value='{$video['id']}'>{$video['title']}</option>";
            } ?>
        </select>
    </div>

    <form action="editVideos.php" method="POST">
        <input type="hidden" id="video_id" name="video_id">

        <label for="video_url">Video URL:</label>
        <input type="text" id="video_url" name="video_url" required><br>

        <label for="video_title">Title:</label>
        <input type="text" id="video_title" name="video_title" required><br>

        <label for="video_synopsis">Synopsis:</label>
        <textarea id="video_synopsis" name="video_synopsis" required></textarea><br>

        <label for="video_type">Type:</label>
        <select id="video_type" name="video_type">
            <option value="0">Volunteer</option>
            <option value="1">Participant</option>
            <option value="2">All</option>
        </select><br>

        <button type="submit" name="update_video">Update Video</button>
    </form>
</body>

</html>