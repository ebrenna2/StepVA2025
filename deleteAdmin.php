<?php
// Author: Maxwell Van Vort
// Description: Delete admin, page only accessible by vmsroot
require_once('include/input-validation.php');
require_once('database/dbPersons.php');
require_once('domain/Person.php');
session_cache_expire(30);
session_start();



$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

// Check access level and redirect if needed
if ($accessLevel < 2) {
    header('Location: login.php');
    exit(); 
}

require_once('include/input-validation.php');
require_once('database/dbPersons.php');
require_once('domain/Person.php');

// Process POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = remove_person($_POST['deletedAdminId']);
    $message = $result 
        ? '<script>alert("Admin successfully deleted from the database!");</script>'
        : '<script>alert("Error: Failed to delete admin from database. Please try again.");</script>';
    
    $result 
        ? error_log("DEBUG: Admin successfully deleted from the database.")
        : error_log("ERROR: Admin deletion failed.");
}

// Flush buffer before HTML output
ob_end_flush();
?>
<!DOCTYPE html>

    <html>
    <head>
        <?php require_once('universal.inc'); ?> 
        <title>Step VA | Register</title>
    </head>
    <body>
        <?php  
            require_once('header.php');
            require_once('domain/Person.php');
            require_once('database/dbPersons.php'); //Default requirements for anything accessing the database 45-47

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $result = remove_person($_POST['deletedAdminId']); //Get selected admin ID call remove person dbPersons 114

                if ($result) {
                    error_log("DEBUG: Admin successfully deleted the database.");
                    echo ('<script>alert("Admin successfully deleted from the database!");</script>');
                } else {
                    error_log("ERROR: Admin deletion failed.");
                    echo ('<script>alert("Error: Failed to delete admin from database. Please try again.");</script>');
<html>
<head>
    <?php require_once 'universal.inc'; ?>
    <title>Step VA | Register</title>
    <style>
    #adminSelect {
        display: block;
        margin: 0 auto;
        width: 50%;
        text-align: center;
    }
    label {
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }
    form {
        width: 50%;
        margin: 0 auto;
        text-align: center;
    }
</style>
</head>
<body>
    <?php
    require_once 'header.php';
    if (isset($message)) {
        echo $message;
    }
    ?>
    <h1>Admin Deletion</h1>
    <div>
        <br>
        <label for="adminSelect">Select an Admin:</label>
        <br><br>
        <select id="adminSelect" onchange="setHiddenAdminVariable();">
            <option value="">-- Choose an Admin --</option>
            <?php
            $admins = getall_admins();
            if ($admins !== false) {
                foreach ($admins as $admin) {
                    $adminIDs[] = $admin->get_id();
                    $adminNames[] = $admin->get_first_name();
                }
                $adminCount = count($adminIDs);
                for ($i = 0; $i < $adminCount; $i++) {
                    echo "<option value='{$adminIDs[$i]}'>{$adminNames[$i]}</option>";
                }
            }
            ?>
        </select>
        <br>
    </div>

    <script>
        var selectedAdmin = '';
        function getHiddenAdminVariable() {
            return selectedAdmin;
        }
        function setHiddenAdminVariable() {
            selectedAdmin = document.getElementById("adminSelect").value;
            return true;
        }
    </script>

    <form action="deleteAdmin.php" id="deleteAdminForm" method="POST">
        <input type="hidden" id="deletedAdminId" name="deletedAdminId" value="">
        <button type="submit">Delete</button>
    </form>

    <p><br></p>
    <div id="calendar-footer" style="width: 50%; margin: 0 auto;">
        <a class="button cancel" href="adminPortal.php">Return to Dashboard</a>
    </div>

    <script>
        document.getElementById('deleteAdminForm').addEventListener('submit', function(event) {
            document.getElementById('deletedAdminId').value = getHiddenAdminVariable();
        });
    </script>
</body>
</html>