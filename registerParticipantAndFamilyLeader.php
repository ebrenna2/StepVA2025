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

            echo '<table>';
            foreach ($_POST as $key => $value) {
                echo "<tr>";
                echo "<td>";
                echo $key;
                echo "</td>";
                echo "<td>";
                echo $value;
                echo "</td>";
                echo "</tr>";
            }
            echo '</table>';


            if ($family_or_individual === "y"){
                //This means they are signing up as a family
                $type = 'familyLeader';

                //Need to get other family member details from args list
                $num_family_members = isset($_POST['num_family_members']) ? $_POST['num_family_members'] : null;
                if (!is_null($num_family_members) && $num_family_members > 0){
                    echo 'There are: ' . $num_family_members . ' family members.';
                    for ($i = 1; $i <= $num_family_members; $i++){
                        $age = isset($_POST['family_mem_age' . $i]) ? $_POST['family_mem_age' . $i] : null;
                        echo 'Fam member ' . $i . ' age: ' . $age;
                    }
                }

                //Here need to add each of the family members individually using person constructor

            } else {
                //Just signing up as a participant
                $type = 'participant';
            }
            

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
                $background_date
            );

            //Down here is where we're probably going to need to figure out potentially adding them to a 'family' table

            $result = add_person($newperson);
            if (!$result) {
                echo '<p>That username is already in use.</p>';
            } else {
                /*if ($loggedIn) {
                    echo '<script>document.location = "index.php?registerSuccess";</script>';
                } else {*/
                    //echo '<script>document.location = "login.php?registerSuccess";</script>';
                /*}*/
            }
        } else {
            require_once('registerForm_participantAndFamilyLeader.php'); 
        }
    ?>
</body>
</html>
