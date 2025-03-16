<?php
    // In this section, I've removed code that ensures the user is already logged in.
    // This is because we want users without accounts to be able to create new accounts.

    // Author: Lauren Knight
    // Description: Registration page for new volunteers

    require_once('include/input-validation.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Registration</title>
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

            // echo "<p>The form was submitted:</p>";
            // foreach ($args as $key => $value) {
            //     echo "<p>$key: $value</p>";
            // }

            // required fields
            $required = array(
                'family_or_individual', 'first_name', 'last_name', 'birthdate',
                'street_address', 'city', 'state', 'zip', 
                'email', 'phone', 'phone_type', 'emergency_contact_first_name',
                'emergency_contact_last_name',
                'emergency_contact_relation', 'emergency_contact_phone', 'tshirt_size',
                'school_affiliation', 'username', 'password',
                'photo_release', 'photo_release_notes'
            );

            
            $family_or_individual = isset($_POST['family_or_individual']) ? $_POST['family_or_individual'] : null;  
            

            $optional = array(
                'how_you_heard_of_stepva', 'preferred_feedback_method', 'hobbies',
                'skills', 'professional_experience', 'disability_accomodation_needs'
            );

            // Set optional fields if they exist
            $how_you_heard_of_stepva = isset($args['how_you_heard_of_stepva']) ? $args['how_you_heard_of_stepva'] : '';
            $preferred_feedback_method = isset($args['preferred_feedback_method']) ? $args['preferred_feedback_method'] : '';
            $hobbies = isset($args['hobbies']) ? $args['hobbies'] : '';
            $professional_experience = isset($args['professional_experience']) ? $args['professional_experience'] : '';
            $disability_accomodation_needs = isset($args['disability_accomodation_needs']) ? $args['disability_accomodation_needs'] : '';

            $errors = false;
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                $errors = true;
            }
            $first_name = $args['first_name'];
            $last_name = $args['last_name'];
            $birthday = validateDate($args['birthdate']);
            if (!$birthday) {
                $errors = true;
                echo 'bad dob';
            }

            $street_address = $args['street_address'];
            $city = $args['city'];
            $state = $args['state'];
            if (!valueConstrainedTo($state, array('AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA',
                    'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
                    'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM',
                    'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX',
                    'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'))) {
                $errors = true;
            }
            $zip_code = $args['zip'];
            if (!validateZipcode($zip_code)) {
                $errors = true;
                echo 'bad zip';
            }
            $email = strtolower($args['email']);
            $email = validateEmail($email);
            if (!$email) {
                $errors = true;
                echo 'bad email';
            }
            $phone1 = validateAndFilterPhoneNumber($args['phone']);
            if (!$phone1) {
                $errors = true;
                echo 'bad phone';
            }
            $phone1type = $args['phone_type'];
            if (!valueConstrainedTo($phone1type, array('cellphone', 'home', 'work'))) {
                $errors = true;
                echo 'bad phone type';
            }

            $emergency_contact_first_name = $args['emergency_contact_first_name'];
            $emergency_contact_last_name = $args['emergency_contact_last_name'];
            $emergency_contact_relation = $args['emergency_contact_relation'];
            $emergency_contact_phone = validateAndFilterPhoneNumber($args['emergency_contact_phone']);
            if (!$emergency_contact_phone) {
                $errors = true;
                echo 'bad e-contact phone';
            }
            $emergency_contact_phone_type = $args['emergency_contact_phone_type'];
            if (!valueConstrainedTo($emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
                $errors = true;
                echo 'bad phone type';
            }

            $tshirt_size = $args['tshirt_size'];
            $school_affiliation = $args['school_affiliation'];
            $photo_release = $args['photo_release'];
            if (!valueConstrainedTo($photo_release, array('Restricted', 'Not Restricted'))) {
                $errors = true;
                echo 'bad photo release type';
            }
            $photo_release_notes = $args['photo_release_notes'];

            $archived = 0;

            $id = $args['username'];
            // May want to enforce password requirements at this step
            //$username = $args['username'];
            $password = isSecurePassword($args['password']);
            if (!$password) {
                $errors = true;
            } else {
                $password = password_hash($args['password'], PASSWORD_BCRYPT);
            } 

            $how_you_heard_of_stepva = $args['how_you_heard_of_stepva'];
            // Safely access preferred_feedback_method
            $preferred_feedback_method = $args['preferred_feedback_method'];

            $hobbies = $args['hobbies'];
            $professional_experience = $args['professional_experience'];
            $disability_accomodation_needs = $args['disability_accomodation_needs'];

            $training_complete = isset($args['training_complete']) ? (int)$args['training_complete'] : 0;
            $training_date = isset($args['training_date']) ? $args['training_date'] : null;

            $orientation_complete = isset($args['orientation_complete']) ? (int)$args['orientation_complete'] : 0;
            $orientation_date = isset($args['orientation_date']) ? $args['orientation_date'] : null;

            $background_complete = isset($args['background_complete']) ? (int)$args['background_complete'] : 0;
            $background_date = isset($args['background_date']) ? $args['background_date'] : null;

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }

            $status = "Active";
            
            $skills = '';
            $networks = '';
            $contributions = '';

            $type = 'participant';
            
            $newperson = new Person(
                $id, // (id = username)
                $password,
                date("Y-m-d"),
                $first_name,
                $last_name,
                $birthday,
                $street_address,
                $city,
                $state,
                $zip_code,
                $phone1,
                $phone1type,
                $email,
                $emergency_contact_first_name,
                $emergency_contact_last_name,
                $emergency_contact_phone,
                $emergency_contact_phone_type,
                $emergency_contact_relation,
                $tshirt_size,
                $school_affiliation,
                $photo_release,
                $photo_release_notes,
                $type, // admin or volunteer or participant...
                $status,
                $archived,
                $how_you_heard_of_stepva,
                $preferred_feedback_method,
                $hobbies,
                $professional_experience,
                $disability_accomodation_needs,
                $training_complete,
                $training_date,
                $orientation_complete,
                $orientation_date,
                $background_complete,
                $background_date,
                $skills,
                $networks,
                $contributions,
                $familyId=-1 //Default to negative one indicating not a family leader, but will update in a second if so
            );

            //Down here is where we're probably going to need to figure out potentially adding them to a 'family' table

            $result = add_person($newperson);
            if (!$result) {
                echo '<p>That username is already in use.</p>';
            }

            // Store the family leader in the family leader table and store

            //Family member stuff
            if ($family_or_individual === "y"){
                //This means they are signing up as a family
                $type = '';

                //Need to get other family member details from args list
                $num_family_members = isset($_POST['num_family_members']) ? $_POST['num_family_members'] : null;
                if (!is_null($num_family_members) && $num_family_members > 0){
                    echo 'There are: ' . $num_family_members . ' family members.';

                    //At this point, they are a family leader and can be inserted into the table

                    $familyId = add_family_leader($id);

                    if (update_person_familyid($id, $familyId) != 1){
                        echo 'There was an issue updating the family member id';
                    }

                    $family_members = isset($_POST['family']) ? $_POST['family'] : null;

                    $member_required = array(
                        'first_name', 'last_name', 'birthdate',
                        'street_address', 'city', 'state', 'zip', 
                        'email', 'phone', 'phone_type', 'emergency_contact_first_name',
                        'emergency_contact_last_name',
                        'emergency_contact_relation', 'emergency_contact_phone', 'tshirt_size',
                        'school_affiliation', 'username', 'password',
                        'photo_release', 'photo_release_notes'
                    );

                    for ($i = 1; $i <= $num_family_members; $i++){

                        $member = $family_members[$i];

                        if (!wereRequiredFieldsSubmitted($member, $member_required)) {
                            echo 'not all required';
                            $errors = true;
                        }
                        
                        $member_first_name = isset($member['first_name']) ? $member['first_name'] : null;
                        $member_last_name = isset($member['last_name']) ? $member['last_name'] : null;

                        $member_birthdate = isset($member['birthdate']) ? $member['birthdate'] : null;
                        $member_birthdate = validateDate($member_birthdate);
                        if (!$member_birthdate){
                            $errors = true;
                            echo 'bad member dob';
                        }

                        $member_street_address = isset($member['street_address']) ? $member['street_address'] : null;
                        $member_city = isset($member['city']) ? $member['city'] : null;

                        $member_state = isset($member['state']) ? $member['state'] : null;
                        if (!valueConstrainedTo($member_state, array('AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA',
                                                              'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
                                                              'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM',
                                                              'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX',
                                                              'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'))) {
                            $errors = true;
                        }

                        $member_zip = isset($member['zip']) ? $member['zip'] : null;
                        if (!validateZipcode($member_zip)) {
                            $errors = true;
                            echo 'bad zip';
                        }

                        $member_email = isset($member['email']) ? $member['email'] : null;
                        $member_email = strtolower($member_email);
                        $member_email = validateEmail($member_email);
                        if (!$member_email) {
                            $errors = true;
                            echo 'bad member email';
                        }

                        //Need to validate
                        $member_phone = isset($member['phone']) ? $member['phone'] : null;
                        $member_phone = validateAndFilterPhoneNumber($member_phone);
                        if (!$member_phone) {
                            $errors = true;
                            echo 'bad member phone';
                        }
                        $member_phone_type = isset($member['phone_type']) ? $member['phone_type'] : null;
                        if (!valueConstrainedTo($member_phone_type, array('cellphone', 'home', 'work'))) {
                            $errors = true;
                            echo 'bad member phone type';
                        }

                        $member_emergency_contact_first_name = isset($member['emergency_contact_first_name']) ? $member['emergency_contact_first_name'] : null;
                        $member_emergency_contact_last_name = isset($member['emergency_contact_last_name']) ? $member['emergency_contact_last_name'] : null;
                        $member_emergency_contact_relation = isset($member['emergency_contact_relation']) ? $member['emergency_contact_relation'] : null;

                        $member_emergency_contact_phone = isset($member['emergency_contact_phone']) ? $member['emergency_contact_phone'] : null;
                        $member_emergency_contact_phone = validateAndFilterPhoneNumber($member_emergency_contact_phone);
                        if (!$member_emergency_contact_phone) {
                            $errors = true;
                            echo 'bad member e-contact phone';
                        }
                        
                        $member_emergency_contact_phone_type = isset($member['emergency_contact_phone_type']) ? $member['emergency_contact_phone_type'] : null;
                        if (!valueConstrainedTo($member_emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
                            $errors = true;
                            echo 'bad member phone type';
                        }

                        $member_tshirt_size = isset($member['tshirt_size']) ? $member['tshirt_size'] : null;
                        $member_school_affiliation = isset($member['school_affiliation']) ? $member['school_affiliation'] : null;

                        $member_photo_release = isset($member['photo_release']) ? $member['photo_release'] : null;
                        if (!valueConstrainedTo($member_photo_release, array('Restricted', 'Not Restricted'))) {
                            $errors = true;
                            echo 'bad member photo release type';
                        }
                        $member_photo_release_notes = isset($member['photo_release_notes']) ? $member['photo_release_notes'] : null;
                        
                        $member_how_you_heard_of_stepva = isset($member['how_you_heard_of_stepva']) ? $member['how_you_heard_of_stepva'] : '';
                        $member_preferred_feedback_method = isset($member['preferred_feedback_method']) ? $member['preferred_feedback_method'] : '';
                        $member_hobbies = isset($member['hobbies']) ? $member['hobbies'] : '';
                        $member_professional_experience = isset($member['professional_experience']) ? $member['professional_experience'] : '';
                        $member_disability_accomodation_needs = isset($member['disability_accomodation_needs']) ? $member['disability_accomodation_needs'] : '';

                        $member_training_complete = isset($member['training_complete']) ? (int)$member['training_complete'] : 0;
                        $member_training_date = isset($member['training_date']) ? $member['training_date'] : null;
            
                        $member_orientation_complete = isset($member['orientation_complete']) ? (int)$member['orientation_complete'] : 0;
                        $member_orientation_date = isset($member['orientation_date']) ? $member['orientation_date'] : null;
            
                        $member_background_complete = isset($member['background_complete']) ? (int)$member['background_complete'] : 0;
                        $member_background_date = isset($member['background_date']) ? $member['background_date'] : null;

                        $member_username = isset($member['username']) ? $member['username'] : null;
                        // May want to enforce password requirements at this step
                        //$username = $args['username'];
                        $member_password = isset($member['password']) ? $member['password'] : null;
                        $member_password = isSecurePassword($member_password);
                        if (!$member_password) {
                            $errors = true;
                        } else {
                            $member_password = password_hash($member_password, PASSWORD_BCRYPT);
                        } 
                        
                        
                        $member_password_reenter = isset($member['password_reenter']) ? $member['password_reenter'] : null;

                        if ($errors) {
                            echo '<p>Your member form submission contained unexpected input.</p>';
                            die();
                        }

                        $member_status = "Active";
            
                        $member_type = 'participant';

                        $member_archived = 0;

                        $member_skills = '';
                        $member_networks = '';
                        $member_contributions = '';
            
                        $newperson = new Person(
                            $member_username, // (id = username)
                            $member_password,
                            date("Y-m-d"),
                            $member_first_name,
                            $member_last_name,
                            $member_birthdate,
                            $member_street_address,
                            $member_city,
                            $member_state,
                            $member_zip,
                            $member_phone,
                            $member_phone_type,
                            $member_email,
                            $member_emergency_contact_first_name,
                            $member_emergency_contact_last_name,
                            $member_emergency_contact_phone,
                            $member_emergency_contact_phone_type,
                            $member_emergency_contact_relation,
                            $member_tshirt_size,
                            $member_school_affiliation,
                            $member_photo_release,
                            $member_photo_release_notes,
                            $member_type, // admin or volunteer or participant...
                            $member_status,
                            $member_archived,
                            $member_how_you_heard_of_stepva,
                            $member_preferred_feedback_method,
                            $member_hobbies,
                            $member_professional_experience,
                            $member_disability_accomodation_needs,
                            $member_training_complete,
                            $member_training_date,
                            $member_orientation_complete,
                            $member_orientation_date,
                            $member_background_complete,
                            $member_background_date,
                            $member_skills,
                            $member_networks,
                            $member_contributions,
                            $familyId
                        );

                        //Down here is where we're probably going to need to figure out potentially adding them to a 'family' table
                        $result = add_person($newperson);

                        if (!$result) {
                            echo '<p>That username is already in use.</p>';
                        }

                        if (add_family_member($member_username, $familyId) != 1){
                            echo 'There was an issue adding ' . $username . ' to the family member table';
                        }
                    }
                }

                //Here need to add each of the family members individually using person constructor

            } else {
                //Just signing up as a participant
                $type = 'participant';
            }

                /*if ($loggedIn) {
                    echo '<script>document.location = "index.php?registerSuccess";</script>';
                } else {*/
                    echo '<script>document.location = "login.php?registerSuccess";</script>';
                /*}*/
        } else {
            //Redirect to the register form
            require_once('registerForm_participantAndFamilyLeader.php'); 
        }
        

    ?>
</body>
</html>
