<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    include_once('domain/Person.php');
    include_once('database/dbPersons.php');

    $person = Array();
    $person['first_name'] = 'vmsroot';
    $person['last_name'] = '';
    $person['venue'] = 'portland';
    $person['address'] = 'N/A';
    $person['city'] = 'N/A';
    $person['state'] = 'VA';
    $person['zip'] = 'N/A';
    $person['phone1'] = '';
    $person['phone1type'] = 'N/A';
    $person['emergency_contact_phone'] = 'N/A';
    $person['emergency_contact_phone_type'] = 'N/A';
    $person['birthday'] = 'N/A';
    $person['email'] = 'vmsroot';
    $person['emergency_contact_first_name'] = 'N/A';
    $person['contact_num'] = 'N/A';
    $person['emergency_contact_relation'] = 'N/A';
    $person['contact_method'] = 'N/A';
    $person['type'] = '';
    $person['status'] = 'N/A';
    $person['notes'] = 'N/A';
    $person['password'] = password_hash('vmsroot', PASSWORD_BCRYPT);
    $person['profile_pic'] = '';
    $person['gender'] = '';
    $person['tshirt_size'] = '';
    $person['how_you_heard_of_stepva'] = '';
    $person['sensory_sensitivities'] = '';
    $person['disability_accomodation_needs'] = '';
    $person['school_affiliation'] = '';
    $person['race'] = '';
    $person['preferred_feedback_method'] = '';
    $person['hobbies'] = '';
    $person['professional_experience'] = '';
    $person['archived'] = '';
    $person['emergency_contact_last_name'] = '';
    $person['photo_release'] = '';
    $person['photo_release_notes'] = '';
    $person['training_complete'] = '';
    $person['training_date'] = '';
    $person['orientation_complete'] = '';
    $person['orientation_date'] = '';
    $person['background_complete'] = '';
    $person['background_date'] = '';
    $person['skills'] = '';
    $person['networks'] = '';
    $person['contributions'] = '';
    $person['familyid'] = '';
    $person['profile_feature'] = '';
    $person['identification_preference'] = '';
    $person['headshot_publish'] = '';
    $person['likeness_usage'] = '';



    // $days = array('sun', 'mon', 'tues', 'wednes', 'thurs', 'fri', 'satur');
    // foreach ($days as $day) {
    //     $person[$day . 'days_start'] = '';
    //     $person[$day . 'days_end'] = '';
    // }
    $PERSON = make_a_person($person);
    $result = add_person($PERSON);
    if ($result) {
        echo 'ROOT USER CREATION SUCCESS';
    } else {
        echo 'USER ALREADY EXISTS';
    }
?>