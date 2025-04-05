<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // Debug: Log session state
    error_log("index.php: Session['_id'] = " . (isset($_SESSION['_id']) ? $_SESSION['_id'] : 'not set'));
    error_log("index.php: Session['access_level'] = " . (isset($_SESSION['access_level']) ? $_SESSION['access_level'] : 'not set'));

// Redirect logic based on access level and user ID
include_once('database/dbPersons.php');
include_once('domain/Person.php');

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    if (isset($_SESSION['change-password'])) {
        error_log("index.php: Redirecting to changePassword.php due to change-password flag");
        header('Location: changePassword.php');
    } else {
        error_log("index.php: Redirecting to login.php because access_level is invalid or not set");
        header('Location: login.php');
    }
    die();
}

// Retrieve person object for family leader check
if (isset($_SESSION['_id'])) {
    $person = retrieve_person($_SESSION['_id']);
} else {
    error_log("index.php: No user ID in session, redirecting to login.php");
    header('Location: login.php');
    die();
}

// Redirect based on user type
if ($_SESSION['access_level'] >= 4 && $_SESSION['_id'] === 'vmsroot') {
    // Redirect vmsroot to vmsDash.php
    error_log("index.php: Redirecting vmsroot to vmsDash.php");
    header('Location: vmsDash.php');
    die();
} elseif ($_SESSION['access_level'] == 3 && $_SESSION['_id'] !== 'vmsroot') {
    // Redirect regular admins to adminDash.php
    error_log("index.php: Redirecting regular admin to adminDash.php");
    header('Location: adminDash.php');
    die();
} elseif ($_SESSION['access_level'] == 1) {
    // Check the user's role
    $role = $person->get_type();
    if ($role === 'participant') {
        // Redirect family leaders and participants to participantPortal.php
        error_log("index.php: Redirecting family leader/participant to participantPortal.php");
        header('Location: participantPortal.php');
        die();
    } elseif ($role === 'volunteer') {
        // Redirect volunteers to volunteerPortal.php
        error_log("index.php: Redirecting volunteer to volunteerPortal.php");
        header('Location: volunteerPortal.php');
        die();
    } else {
        // Handle unexpected roles (e.g., log an error and redirect to a default page)
        error_log("index.php: Unknown role '$role' for user with access_level 1, redirecting to login.php");
        header('Location: login.php');
        die();
    }
}
?>