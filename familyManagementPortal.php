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
        require_once('header.php');
        $isAdmin = $_SESSION['access_level'] >= 2;

        $familyMemNames = get_family_member_names($person->get_id());      

        echo '<fieldset class="section-box">';
        echo '<legend>Family Members</legend>';
        //Now make a table with all of the family members tied to this account
        echo '<table>';
        for ($i = 0; $i<count($familyMemNames); $i++){
            echo '<tr>';

            //Display the family member name
            echo '<td>';
            echo $familyMemNames[$i];
            echo '</td>';

            //Display the family member edit button
            echo '<td>';
            
            echo '</td>';

            //Display the family member delete button
            echo '<td>';

            echo '</td>';

            echo '</tr>';

        }
        echo '</table>';
        echo '</fieldset>';
    ?>
</body>
</html>
