<?php
    // Author: Maxwell Van Vort
    // Description: New admins registration page.

    require_once('include/input-validation.php');

    session_cache_expire(30);
    session_start();
    // Make session information accessible, allowing us to associate
    // data with the logged-in user. If needed, this will allow us to
    // backtrack which admin created which admin account.

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 3 = manager (Admin), 4 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 
     
    if ($accessLevel < 3) {
        header('Location: login.php');
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
                // make every submitted field SQL-safe except for password
                $ignoreList = array('password');
                $args = sanitize($_POST, $ignoreList);
                
                // required fields
                $required = array(
                    'first_name', 'email',
                    'username', 'password'
                );

                $errors = false;
                if (!wereRequiredFieldsSubmitted($args, $required)) {
                    $errors = true;
                }

                $first_name = $args['first_name'];

                $email = strtolower($args['email']);
                $email = validateEmail($email);
                if (!$email) {
                    $errors = true;
                    //echo 'bad email';
                }

                $id = $args['username'];
                $password = isSecurePassword($args['password']);
                if (!$password) {
                    $errors = true;
                } else {
                    $password = password_hash($args['password'], PASSWORD_BCRYPT);
                } 

                if ($errors) {
                    echo '<p>Your form submission contained unexpected input.</p>';
                    die();
                }

                $newperson = new Person(
                    $id,
                    $password,
                    '', // Date
                    $first_name,
                    '', // Last name
                    '', // DoB
                    'N/A', // Street Address
                    'N/A', // City
                    'VA', // State
                    'N/A', // Zip Code
                    '', // Phone 1
                    'N/A', // Phone 1 Type
                    $email,
                    'N/A', // Emergency Contact First Name
                    'N/A', // Emergency Contact Last Name
                    'N/A', // Emergency Contact Phone
                    'N/A', // Emergency Contact Phone Type
                    'N/A', // Emergency Contact Relation
                    '', // T Shirt Size
                    '', // School Affiliation 
                    '', // Photo Release
                    '', // Photo Release Notes
                    'Admin', // Type of Account
                    '', // Status
                    0, // Archived
                    '', // How you heard of stepva
                    '', // Preferred feedback method
                    '', // Hobbies
                    '', // Professional Experience
                    '', // Disability Accommodation needs
                    0, // Training Complete
                    '', // Training Date
                    0, // Orientation Complete
                    '', // Orientation Date
                    0, // Background Complete 
                    '', // Background Date
                    '', // Skills
                    '', // Networks
                    '', // Contributions
                    '0', // Family ID 
                    '', // Profile Feature
                    '', // Identification Preference
                    0,  // Headshot Publish
                    0   // Likeness Usage
                );

    

                //$person = Array();
                //$person['status'] = 'N/A';
                //$person['hours'] = 'N/A';
                //$person['availability'] = 'N/A';
                //$person['schedule'] = 'N/A';
                //$person['birthday'] = 'N/A';
                //$person['start_date'] = 'N/A';
                //$person['notes'] = 'N/A';
                //$person['profile_pic'] = '';
                //$person['gender'] = '';
                //$person['force_password_change'] = 1;
                //$days = array('sun', 'mon', 'tues', 'wednes', 'thurs', 'fri', 'satur');
                //foreach ($days as $day) {
                    //$person[$day . 'days_start'] = '';
                    //$person[$day . 'days_end'] = '';
                //}

                    $result = add_person($newperson);

                    if ($result) {
                        echo ('<script>alert("The admin was successfully added to the database!");</script>');
                    } else {
                        error_log("ERROR: Admin insertion failed. Username might already exist.");
                        echo ('<script>alert("Error: Failed to add admin from database because the user already exists. Please try again.");</script>');
                    }

                
                if (!$result) {
                    echo '<p>That username is already in use.</p>';
                } else {
                    if ($loggedIn) {
                        echo '<script>document.location = "index.php?registerSuccess";</script>';
                    } else {
                        echo '<script>document.location = "login.php?registerSuccess";</script>';
                    }
                }               
            } else {
                require_once('registrationFormAdmin.php'); 
            }
        ?>
    </body>
</html>