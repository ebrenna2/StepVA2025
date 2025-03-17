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
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';


    # TO-DO: Integrate this section of code with dbVideos

    # Note: For whatever reason, php wanted to be special and made their dictionaries be called arrays & simply didn't make actual arrays a thing.

    $arrayOfVideoLinks = [ # Array of video links
        "Rick Roll" => "https://www.youtube.com/embed/dQw4w9WgXcQ?si=QRY1mPge2aqHZSyH"
    ];
    $arrayOfVideoThumbnails = [ # Not used, kept in in case this is needed with integration
        "Rick Roll" => "I am a dummy thumbnail."
    ];
    $arrayOfVideoTitles = [ # Video titles go here.
        "Rick Roll" => "Never Gonna Give You Up"
    ];
    $arrayOfVideoDescriptions = [ # Video descriptions go here, recommend the key match the title.
        "Rick Roll" => "The music video for 'Never Gonna Give You Up' by Rick Astley is one of the most iconic videos of the 1980s, known for its 
        catchy melody and somewhat simple yet memorable aesthetic. The tone of the video is uplifting, celebratory, and full of energy. Even though 
        the visuals are somewhat simple and repetitive, the essence of the song — sincerity, commitment, and love — is fully captured through 
        Rick’s performance and the overall presentation of the video. The vibrant colors, dynamic dance moves, and Rick's earnest delivery create 
        an emotionally charged atmosphere that supports the powerful message of the song. It’s an iconic music video that doesn't rely on complex 
        storytelling or visual effects but instead focuses on Rick Astley’s charm, the catchy beat, and the fun, high-energy vibes of the 1980s. 
        The understated, yet effective, choreography, background lighting, and Rick’s powerful yet warm performance make it a memorable experience 
        for viewers.In summary, the 'Never Gonna Give You Up' video is a classic representation of 1980s pop videos, with its dynamic dancing, 
        energetic vibe, minimalist set design, and Rick Astley’s charismatic and engaging performance. Its simple, straightforward visuals align
         perfectly with the upbeat and heartfelt message of the song, making it a timeless and memorable video. <br>"
    ]; 

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

        <!-- TO-DO: Integrate database of videos here. -->
        <main class='dashboard'> 

            <!-- Here is our logic for grabbing and displaying an embedded video and its related info. -->
            <div> 
                <?php
                    echo '<iframe src="', $arrayOfVideoLinks["Rick Roll"],'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
                    echo '<div class="middle"><br>', $arrayOfVideoTitles["Rick Roll"], '<br></div>';
                    echo '<div class="middle"><br>', $arrayOfVideoDescriptions["Rick Roll"], '<br></div>';
                ?>
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