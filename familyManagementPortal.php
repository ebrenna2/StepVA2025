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
            max-width: 1200px; /* Keeping increased width */
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
            min-width: 300px;
            white-space: nowrap;
            padding-right: 40px; /* Increased padding to push buttons away from border */
        }
        .edit-button, .register-button, .delete-button {
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
        .edit-button {
            background-color: #4CAF50; /* Green for Edit */
        }
        .delete-button {
            background-color: #ff4444; /* Red for Delete */
        }
        .edit-button:hover, .register-button:hover, .delete-button:hover {
            background-color: #0056b3; /* Consistent hover color */
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
    <div class="main-content">
        <?php
        if (isset($_GET['message']) && $_GET['message'] === 'deleted') {
            echo '<p style="color: green;">Family member deleted successfully</p>';
        }

        require_once('header.php');
        $isAdmin = $_SESSION['access_level'] >= 2;

        $familyMemIDs = get_family_member_ids($person->get_id());      

        echo '<fieldset class="section-box">';
        echo '<legend>Family Members</legend>';
        // Now make a table with all of the family members tied to this account
        echo '<table class="family-table">';
        for ($i = 0; $i < count($familyMemIDs); $i++) {
            echo '<tr>';

            // Display the family member name
            echo '<td class="name-column">';
            echo htmlspecialchars(get_name_from_id($familyMemIDs[$i]));
            echo '</td>';

            // Display the family member edit button
            echo '<td class="button-column">';
            echo '<a href="editChildProfile.php?childID=' . htmlspecialchars($familyMemIDs[$i]) . '">';
            echo '<button type="button" class="edit-button">Edit Child Profile</button>';
            echo '</a>';

            // Display the register button
            echo '<a href="viewAllEvents.php?childID=' . htmlspecialchars($familyMemIDs[$i]) . '">';
            echo '<button type="button" class="register-button">Register Child for Event</button>';
            echo '</a>';

            // Display the delete family member button
            echo '<a href="deleteFamilyMember.php?childID=' . htmlspecialchars($familyMemIDs[$i]) . '" onclick="return confirm(\'Are you sure you want to delete this family member?\');">';
            echo '<button type="button" class="delete-button">Delete Family Member</button>';
            echo '</a>';
            echo '</td>';

            echo '</tr>';
        }
        echo '</table>';
        echo '</fieldset>';
        ?>
    </div>
</body>
</html>