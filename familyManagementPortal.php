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
                max-width: 1200px;
                margin: 20px auto;
                padding: 20px;
            }
            .section-box {
                border: 2px solid #999;
                border-radius: 5px;
                padding: 30px;
                overflow: hidden;
            }
            .family-table {
                width: 100%;
                max-width: 700px;
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
                padding-right: 40px;
            }
            .edit-button, .register-button, .delete-button {
                padding: 6px 6px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
                margin-right: 10px;
                display: inline-block;
                font-size: 0.9em;
                width: 200px;
                text-align: center;
            }
            .edit-button {
                background-color: #4CAF50; /* Green for Edit */
            }
            .delete-button {
                background-color: #ff4444; /* Red for Delete */
            }
            .edit-button:hover, .register-button:hover, .delete-button:hover {
                background-color: #0056b3;
            }
            legend {
                text-align: center;
                font-size: 1.2em;
                font-weight: bold;
                padding: 0 10px;
            }

            /* Dark mode overrides */
            body.darkmode .section-box {
                background-color: var(--standout-background); /* #0a1e3b */
                border: 2px solid var(--shadow-and-border-color); /* #2a3b5b */
            }

            body.darkmode .family-table {
                background-color: var(--standout-background);
            }

            body.darkmode .family-table td {
                background-color: var(--standout-background);
                color: var(--page-font-color); /* #e8f0fa */
            }

            body.darkmode legend {
                color: var(--secondary-accent-color); /* #a3e0f7 */
            }

            body.darkmode .edit-button {
                background-color: #4CAF50 !important; /* Green */
            }

            body.darkmode .edit-button:hover {
                background-color: #3d8b40 !important; /* Darker green */
            }

            body.darkmode .register-button {
                background-color: #007bff !important; /* Blue */
            }

            body.darkmode .register-button:hover {
                background-color: #0056b3 !important; /* Darker blue */
            }

            body.darkmode .delete-button {
                background-color: var(--error-color) !important; /* #ff6666 */
            }

            body.darkmode .delete-button:hover {
                background-color: #cc3333 !important; /* Darker red */
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
        echo '<a href="addFamilyMember.php">';
        echo '    <button type="button" class="add-family-button">Add Family Member</button>';
        echo '</a>';
        echo '</fieldset>';
        ?>
    </div>
    <a class="button cancel" href="participantPortal.php">Return to Participant Portal</a>
</body>
</html>