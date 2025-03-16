<?php
    // Author: Lauren Knight
    // Description: Profile edit page
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
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
</head>
<body>
    
    <?php
        if (isset($_GET['message']) && $_GET['message'] === 'deleted') {
            echo '<p style="color: green;">Family member deleted successfully</p>';
        }

        require_once('header.php');
        $isAdmin = $_SESSION['access_level'] >= 2;

        $familyMemIDs = get_family_member_ids($person->get_id());      

        echo '<fieldset class="section-box">';
        echo '<legend>Family Members</legend>';
        //Now make a table with all of the family members tied to this account
        echo '<table>';
        for ($i = 0; $i<count($familyMemIDs); $i++){
            echo '<tr>';

            //Display the family member name
            echo '<td>';
            echo get_name_from_id($familyMemIDs[$i]);
            echo '</td>';

            //Display the family member edit button
            echo '<td>';
            echo '<a href="editchildprofile.php?childID=' . $familyMemIDs[$i] .  '"><button type="button" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Edit Child Profile</button></a>';
            echo '</td>';

            //Display the delete family member button
            echo '<td>';
            echo '<a href="deletefamilymember.php?childID=' . $familyMemIDs[$i] . '" onclick="return confirm(\'Are you sure you want to delete this family member?\');"><button type="button" style="padding: 10px 20px; background-color: #ff4444; color: white; border: none; cursor: pointer;">Delete Family Member</button></a>';
            echo '</td>';

        }
        echo '</table>';
        echo '</fieldset>';
    ?>
</body>
</html>
