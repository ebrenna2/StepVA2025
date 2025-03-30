<?php
    // Author: Maxwell Van Vort
    // Description: Deletin of Admins page.

    require_once('include/input-validation.php');
    require_once('database/dbPersons.php');
    require_once('domain/Person.php');
?>

<?php session_cache_expire(30);
    session_start();
    // Make session information accessible, allowing us to associate
    // data with the logged-in user. If needed, this will allow us to
    // backtrack which admin was deleted.

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 

    // Deleting any admin requires admin privileges. 
    if ($accessLevel < 2) {
        header('Location: login.php');
        //echo 'bad access level';
        die();
    }
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
            require_once('database/dbPersons.php');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $result = remove_person($_POST['deletedAdminId']);

                if ($result) {
                    error_log("DEBUG: Admin successfully deleted the database.");
                    echo ('<script>alert("Admin successfully deleted from the database!");</script>');
                } else {
                    error_log("ERROR: Admin deletion failed.");
                    echo ('<script>alert("Error: Failed to delete admin from database. Please try again.");</script>');
                }

            }
        ?>

        <h1>Admin Deletion</h1>

        <style>
            
            select {
                display: block;
                text-align: center;
                margin: 0 auto;
                width: 50%;
            }

            label {
                margin: 0 auto;
                text-align: center;
            }

            form {
                width: 50%;
                margin: 0 auto;
                text-align: center;
            }

        </style>

        <div>
            <br>
            <label for="adminSelect">Select an Admin:</label>
            <br><br>
            <select id="adminSelect" onchange="setHiddenAdminVariable();">
                <option value="">-- Choose an Admin --</option>
                <?php
                    $admins = getall_admins();

                    if($admins != false) {
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

        <!-- Aesthetic Formatting -->
        <p>
        <br>
        </p>
        
        <div id="calendar-footer" style="width: 50%; margin: 0 auto;">
            <a class="button cancel" href="adminPortal.php">Return to Dashboard</a>
        </div>

        <script>
            document.getElementById('deleteAdminForm').addEventListener('submit', function (event) {
                document.getElementById('deletedAdminId').value = getHiddenAdminVariable();
            });
        </script>

    </body>
</html>