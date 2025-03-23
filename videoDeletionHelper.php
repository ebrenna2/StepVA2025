<?php
    include_once('database\dbVideos.php');
    include_once('database\dbPersons.php');
    include_once('domain\Person.php');
    include_once('domain\Video.php');
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
    <body onload="deleteLoadedVideo()">
        <?php require('header.php'); ?>
        <script>
            
            function getPassedParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            // Get the 'id' query parameter
            var id = getPassedParam('id');

            // Use the 'id' value (e.g., display it on the page)
            if (id) {
                remove_video(id);
                alert('IT WORKED!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
            } else {
                document.getElementById("displayId").textContent = "No ID received in the URL.";
            }
        </script>
    </body>
</html>

