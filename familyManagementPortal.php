<?php
// Author: Lauren Knight
// Description: Profile edit page

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
include_once('database/dbPersons.php');
include_once('domain/Person.php');

if (isset($_SESSION['_id'])) {
    $person = retrieve_person($_SESSION['_id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Manage Profile</title>
    <style>
        .main-content {
            max-width: 1200px; /* Increased from 1000px to provide more space */
            margin: 20px auto;
            padding: 20px;
        }
        .section-box {
            border: 2px solid #999; /* Keeping the thicker border */
            border-radius: 5px;
            padding: 30px; /* Keeping increased padding */
            overflow: hidden; /* Ensure content doesn't overflow and get clipped */
        }
        .family-table {
            width: 100%; /* Adjusted to fit within the fieldset */
            max-width: 700px; /* Increased max-width to give more room */
            margin: 20px auto;
            border-collapse: collapse;
        }
        .family-table td {
            padding: 10px;
            vertical-align: middle;
        }
        .family-table .name-column {
            width: 40%;
            white-space: nowrap;
        }
        .family-table .button-column {
            width: 60%;
            min-width: 200px;
            white-space: nowrap;
            padding-right: 40px; /* Increased padding to push buttons away from border */
        }
        .edit-button, .register-button {
            padding: 6px 6px; /* Keeping smaller padding */
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.9em; /* Keeping smaller font */
            width: 200px; /* Added specific width to reduce button size */
            text-align: center; /* Center the text within the button */
        }
        .edit-button:hover, .register-button:hover {
            background-color: #0056b3;
        }
        legend {
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <?php require_once('header.php'); ?>
    
    <div class="main-content">
        <?php
        $isAdmin = $_SESSION['access_level'] >= 2;
        
        $familyMemNames = get_family_member_names($person->get_id());
        
        echo '<fieldset class="section-box">';
        echo '<legend>Family Members</legend>';
        
        if (count($familyMemNames) > 0) {
            echo '<table class="family-table">';
            for ($i = 0; $i < count($familyMemNames); $i++) {
                echo '<tr>';
                
                // Family member name with wider column
                echo '<td class="name-column">';
                echo htmlspecialchars($familyMemNames[$i]);
                echo '</td>';
                
                // Buttons cell
                echo '<td class="button-column">';
                // Edit Child Profile button
                echo '<a href="editchildprofile.php">';
                echo '<button type="button" class="edit-button">Edit Child Profile</button>';
                echo '</a>';
                // Register Child for Event button
                echo '<a href="viewAllEvents.php">';
                echo '<button type="button" class="register-button">Register Child for Event</button>';
                echo '</a>';
                echo '</td>';
                
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p style="text-align: center;">No family members found.</p>';
        }
        
        echo '</fieldset>';
        ?>
    </div>
</body>
</html>