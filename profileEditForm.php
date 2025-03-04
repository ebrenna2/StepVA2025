<?php
    require_once('domain/Person.php');
    require_once('database/dbPersons.php');
    require_once('include/output.php');

    $args = sanitize($_GET);
    if ($_SESSION['access_level'] >= 2 && isset($args['id'])) {
        $id = $args['id'];
        $editingSelf = $id == $_SESSION['_id'];
        // Check to see if user is a lower-level manager here
    } else {
        $editingSelf = true;
        $id = $_SESSION['_id'];
    }

    $person = retrieve_person($id);
    if (!$person) {
        echo '<main class="signup-form"><p class="error-toast">That user does not exist.</p></main></body></html>';
        die();
    }

    $times = [
        '12:00 AM', '1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM',
        '6:00 AM', '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM',
        '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM',
        '6:00 PM', '7:00 PM', '8:00 PM', '9:00 PM', '10:00 PM', '11:00 PM',
        '11:59 PM'
    ];
    $values = [
        "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", 
        "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", 
        "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", 
        "18:00", "19:00", "20:00", "21:00", "22:00", "23:00",
        "23:59"
    ];
    
    function buildSelect($name, $disabled=false, $selected=null) {
        global $times;
        global $values;
        if ($disabled) {
            $select = '
                <select id="' . $name . '" name="' . $name . '" disabled>';
        } else {
            $select = '
                <select id="' . $name . '" name="' . $name . '">';
        }
        if (!$selected) {
            $select .= '<option disabled selected value>Select a time</option>';
        }
        $n = count($times);
        for ($i = 0; $i < $n; $i++) {
            $value = $values[$i];
            if ($selected == $value) {
                $select .= '
                    <option value="' . $values[$i] . '" selected>' . $times[$i] . '</option>';
            } else {
                $select .= '
                    <option value="' . $values[$i] . '">' . $times[$i] . '</option>';
            }
        }
        $select .= '</select>';
        return $select;
    }
?>
<h1>Edit Profile</h1>
<main class="signup-form">
    <h2>Modify Volunteer Profile</h2>
    <?php if (isset($updateSuccess)): ?>
        <?php if ($updateSuccess): ?>
            <div class="happy-toast">Profile updated successfully!</div>
        <?php else: ?>
            <div class="error-toast">An error occurred.</div>
        <?php endif ?>
    <?php endif ?>
    <?php if ($isAdmin): ?>
        <?php if (strtolower($id) == 'vmsroot') : ?>
            <div class="error-toast">The root user profile cannot be modified</div></main></body>
            <?php die() ?>
        <?php elseif (isset($_GET['id']) && $_GET['id'] != $_SESSION['_id']): ?>
            <!-- <a class="button" href="modifyUserRole.php?id=<?php echo htmlspecialchars($_GET['id']) ?>">Modify User Access</a> -->
        <?php endif ?>
    <?php endif ?>
    <form class="signup-form" method="post">
        <br>
	<p>An asterisk (<em>*</em>) indicates a required field.</p>
    
        <fieldset class="section-box">
            <legend>Login Credentials</legend>
            <label>Username</label>
            <p><?php echo $person->get_id() ?></p>

            <!--<label>Password</label>-->
                <p><a href='changePassword.php'>Change Password</a></p>
        </fieldset>

        <fieldset class="section-box">
            <legend>Personal Information</legend>

            <p>The following information helps us identify you within our system.</p>
            <label for="first_name"><em>* </em>First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo hsc($person->get_first_name()); ?>" required placeholder="Enter your first name">

            <label for="last_name"><em>* </em>Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo hsc($person->get_last_name()); ?>" required placeholder="Enter your last name">

            <label for="birthday"><em>* </em>Date of Birth</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo hsc($person->get_birthday()); ?>" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">


            <label for="street_address"><em>* </em>Street Address</label>
            <input type="text" id="street_address" name="street_address" value="<?php echo hsc($person->get_street_address()); ?>" required placeholder="Enter your street address">

            <label for="city"><em>* </em>City</label>
            <input type="text" id="city" name="city" value="<?php echo hsc($person->get_city()); ?>" required placeholder="Enter your city">

            <label for="state"><em>* </em>State</label>
            <select id="state" name="state" required>
                <?php
                    $state = $person->get_state();
                    $states = array(
                        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District Of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                    );
                    $abbrevs = array(
                        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
                    );
                    $length = count($states);
                    for ($i = 0; $i < $length; $i++) {
                        if ($abbrevs[$i] == $state) {
                            echo '<option value="' . $abbrevs[$i] . '" selected>' . $states[$i] . '</option>';
                        } else {
                            echo '<option value="' . $abbrevs[$i] . '">' . $states[$i] . '</option>';
                        }
                    }
                ?>
            </select>

            <label for="zip_code"><em>* </em>Zip Code</label>
            <input type="text" id="zip_code" name="zip_code" value="<?php echo hsc($person->get_zip_code()); ?>" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">
        </fieldset>

        <fieldset class="section-box">
            <legend>Contact Information</legend>

            <p>The following information helps us determine the best way to contact you regarding event coordination.</p>
            <label for="email"><em>* </em>E-mail</label>
            <input type="email" id="email" name="email" value="<?php echo hsc($person->get_email()); ?>" required placeholder="Enter your e-mail address">

            <label for="phone1"><em>* </em>Phone Number</label>
            <input type="tel" id="phone1" name="phone1" value="<?php echo formatPhoneNumber($person->get_phone1()); ?>" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">

            <label><em>* </em>Phone Type</label>
            <div class="radio-group">
                <?php $type = $person->get_phone1type(); ?>
                <input type="radio" id="phone-type-cellphone" name="phone1type" value="cellphone" <?php if ($type == 'cellphone') echo 'checked'; ?> required><label for="phone-type-cellphone">Cell</label>
                <input type="radio" id="phone-type-home" name="phone1type" value="home" <?php if ($type == 'home') echo 'checked'; ?> required><label for="phone-type-home">Home</label>
                <input type="radio" id="phone-type-work" name="phone1type" value="work" <?php if ($type == 'work') echo 'checked'; ?> required><label for="phone-type-work">Work</label>
            </div>

        </fieldset>

        <fieldset class="section-box">
            <legend>Emergency Contact</legend>

            <p>Please provide us with someone to contact on your behalf in case of an emergency.</p>
            <label for="emergency_contact_first_name" required><em>* </em>First Name</label>
            <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" value="<?php echo hsc($person->get_emergency_contact_first_name()); ?>" required placeholder="Enter emergency contact name">

            <label for="emergency_contact_last_name" required><em>* </em>Last Name</label>
            <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" value="<?php echo hsc($person->get_emergency_contact_last_name()); ?>" required placeholder="Enter emergency contact name">

            <label for="emergency_contact_relation"><em>* </em>Contact Relation to You</label>
            <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?php echo hsc($person->get_emergency_contact_relation()); ?>" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">

            <label for="emergency_contact_phone"><em>* </em>Phone Number</label>
            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo formatPhoneNumber($person->get_emergency_contact_phone()); ?>" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">

            <label><em>* </em>Phone Type</label>
            <div class="radio-group">
                <?php $type = $person->get_emergency_contact_phone_type(); ?>
                <input type="radio" id="phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone" <?php if ($type == 'cellphone') echo 'checked'; ?> required><label for="phone-type-cellphone">Cell</label>
                <input type="radio" id="phone-type-home" name="emergency_contact_phone_type" value="home" <?php if ($type == 'home') echo 'checked'; ?> required><label for="phone-type-home">Home</label>
                <input type="radio" id="phone-type-work" name="emergency_contact_phone_type" value="work" <?php if ($type == 'work') echo 'checked'; ?> required><label for="phone-type-work">Work</label>
            </div>
        
        </fieldset>

        <fieldset class="section-box">
            <legend>Volunteer Information</legend>

            <label>Account Type</label>
            
            <!--
            <?php $account_type = $person->get_type()?>
            <div class="radio-group">
                <input type="radio" id="volunteer" name="type" value="volunteer" <?php if ($account_type == 'volunteer') echo 'checked'; ?> required><label for="type">Volunteer</label>
                <input type="radio" id="participant" name="type" value="participant" <?php if ($account_type == 'participant') echo 'checked'; ?> required><label for="type">Participant</label>
            </div>
            -->
            <input type="hidden" name="type" value="v">


            <label for="school_affiliation"><em>* </em>School Affiliation</label>
            <input type="text" id="school_affiliation" name="school_affiliation" value="<?php echo hsc($person->get_school_affiliation()); ?>" required placeholder="Enter your affiliated school.">

            <label><em>* </em>Tshirt Size</label>
            <div class="radio-group">
                <?php $size = $person->get_tshirt_size(); ?>
                <input type="radio" id="tshirt-size-xs" name="tshirt_size" value="xs" <?php if ($size == 'xs') echo 'checked'; ?> required><label for="tshirt-size-xs">XS</label>
                <input type="radio" id="tshirt-size-s" name="tshirt_size" value="s" <?php if ($size == 's') echo 'checked'; ?> required><label for="tshirt-size-s">S</label>
                <input type="radio" id="tshirt-size-m" name="tshirt_size" value="m" <?php if ($size == 'm') echo 'checked'; ?> required><label for="tshirt-size-m">M</label>
                <input type="radio" id="tshirt-size-l" name="tshirt_size" value="l" <?php if ($size == 'l') echo 'checked'; ?> required><label for="tshirt-size-l">L</label>
                <input type="radio" id="tshirt-size-xl" name="tshirt_size" value="xl" <?php if ($size == 'xl') echo 'checked'; ?> required><label for="tshirt-size-xl">XL</label>
            </div>

            <label for="photo_release"><em>* </em>Photo Release Restrictions: Can your photo be taken and used on our website and social media?</label>
            <div class="radio-group">
                <?php $photo_release = $person->get_photo_release()?>
                <input type="radio" id="Restricted" name="photo_release" value="Restricted" <?php if ($photo_release == 'Restricted') echo 'checked'; ?> required><label for="photo_release">Restricted</label>
                <input type="radio" id="Not Restricted" name="photo_release" value="Not Restricted" <?php if ($photo_release == 'Not Restricted') echo 'checked'; ?> required><label for="photo_release">Not Restricted</label>
            </div>

            <label for="photo_release_notes"><em>* </em>Photo Release Restriction Notes (or N/A)</label>
            <input type="text" id="photo_release_notes" name="photo_release_notes" value="<?php echo hsc($person->get_photo_release_notes()); ?>" required placeholder="Do you have any specific notes about your photo release status?">
        </fieldset>

        <fieldset class="section-box">
            <legend>Volunteer Training</legend>

            <p>Please provide details about your training status.</p>

            <label for="training_complete"><em>* </em>Training Completed</label>
            <div class="radio-group">
                <?php $trainingComplete = $person->get_training_complete(); ?>
                <input type="radio" id="training-complete-yes" name="training_complete" value="1" 
                    <?php if ($trainingComplete == '1') echo 'checked'; ?> required>
                <label for="training-complete-yes">Yes</label>
                <input type="radio" id="training-complete-no" name="training_complete" value="0" 
                    <?php if ($trainingComplete == '0') echo 'checked'; ?> required>
                <label for="training-complete-no">No</label>
            </div>

            <div id="training-date-container" style="display: none;">
                <label for="training_date">Training Date</label>
                <input type="date" id="training_date" name="training_date" 
                    value="<?php echo hsc($person->get_training_date()); ?>" 
                    max="<?php echo date('Y-m-d'); ?>" 
                    placeholder="Enter training date">
            </div>
        </fieldset>

        <fieldset class="section-box">
            <legend>Volunteer Orientation</legend>

            <p>Please provide details about your orientation status.</p>

            <label for="orientation_complete"><em>* </em>Orientation Completed</label>
            <div class="radio-group">
                <?php $orientationComplete = $person->get_orientation_complete();?>
                <input type="radio" id="orientation-complete-yes" name="orientation_complete" value="1" 
                    <?php if ($orientationComplete == '1') echo 'checked'; ?> required>
                <label for="orientation-complete-yes">Yes</label>
                <input type="radio" id="orientation-complete-no" name="orientation_complete" value="0" 
                    <?php if ($orientationComplete == '0') echo 'checked'; ?> required>
                <label for="orientation-complete-no">No</label>
            </div>

            <div id="orientation-date-container" style="display: none;">
                <label for="orientation_date">Orientation Date</label>
                <input type="date" id="orientation_date" name="orientation_date" 
                    value="<?php echo hsc($person->get_orientation_date()); ?>" 
                    max="<?php echo date('Y-m-d'); ?>" 
                    placeholder="Enter orientation date">
            </div>
        </fieldset>

        <fieldset class="section-box">
            <legend>Background Check</legend>

            <p>Please provide details about your background check status.</p>

            <label for="background_complete"><em>* </em>Background Check Completed</label>
            <div class="radio-group">
                <?php $backgroundComplete = $person->get_background_complete(); ?>
                <input type="radio" id="background-complete-yes" name="background_complete" value="1" 
                    <?php if ($backgroundComplete == '1') echo 'checked'; ?> required>
                <label for="background-complete-yes">Yes</label>
                <input type="radio" id="background-complete-no" name="background_complete" value="0" 
                    <?php if ($backgroundComplete == '0') echo 'checked'; ?> required>
                <label for="background-complete-no">No</label>
            </div>

            <div id="background-date-container" style="display: none;">
                <label for="background_date">Background Check Date</label>
                <input type="date" id="background_date" name="background_date" 
                    value="<?php echo hsc($person->get_background_date()); ?>" 
                    max="<?php echo date('Y-m-d'); ?>" 
                    placeholder="Enter background check date">
            </div>
        </fieldset>

        <script>
            // Function to toggle the visibility and required attribute of the date inputs based on the radio buttons
            function toggleStatusDateVisibility(statusType) {
                const statusCompleteYes = document.getElementById(statusType + '-complete-yes');
                const statusDateContainer = document.getElementById(statusType + '-date-container');
                const statusDateInput = document.getElementById(statusType + '_date');

                if (statusCompleteYes.checked) {
                    // Show the date field and make it required
                    statusDateContainer.style.display = 'block';
                    statusDateInput.required = true;
                } else {
                    // Hide the date field and remove its required status
                    statusDateContainer.style.display = 'none';
                    statusDateInput.required = false;
                }
            }

            // Add event listeners for each section
            document.getElementById('training-complete-yes').addEventListener('change', function() {
                toggleStatusDateVisibility('training');
            });
            document.getElementById('training-complete-no').addEventListener('change', function() {
                toggleStatusDateVisibility('training');
            });

            document.getElementById('orientation-complete-yes').addEventListener('change', function() {
                toggleStatusDateVisibility('orientation');
            });
            document.getElementById('orientation-complete-no').addEventListener('change', function() {
                toggleStatusDateVisibility('orientation');
            });

            document.getElementById('background-complete-yes').addEventListener('change', function() {
                toggleStatusDateVisibility('background');
            });
            document.getElementById('background-complete-no').addEventListener('change', function() {
                toggleStatusDateVisibility('background');
            });

            // Initial check on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleStatusDateVisibility('training');
                toggleStatusDateVisibility('orientation');
                toggleStatusDateVisibility('background');
            });
        </script>


        <fieldset class="section-box">
            <legend>Optional Information</legend>

            <label>How did you hear about StepVA?</label>
            <input type="text" id="how_you_heard_of_stepva" name="how_you_heard_of_stepva" value="<?php echo hsc($person->get_how_you_heard_of_stepva()); ?>" placeholder="">

            <label>What is your preferred contact method?</label>
            <div class="radio-group">
                <?php $preferred_feedback_method = $person->get_preferred_feedback_method(); ?>

                <input type="radio" id="text" name="preferred_feedback_method" value="text" <?php if ($type == 'text') echo 'checked'; ?> required>
                <label for="text">Text</label>
                
                <input type="radio" id="email" name="preferred_feedback_method" value="email" <?php if ($type == 'email') echo 'checked'; ?> required>
                <label for="email">Email</label>
                
                <input type="radio" id="no-preference" name="preferred_feedback_method" value="no-preference" 
                <?php if ($preferred_feedback_method == 'no-preference') echo 'checked'; ?> required>
                <label for="no-preference">No preference</label>
            </div>

            <label>What are your hobbies? </label>
            <input type="text" id="hobbies" name="hobbies" value="<?php echo hsc($person->get_hobbies()); ?>" placeholder="">

            <label>Do you have any other experience with volunteering?</label>
            <input type="text" id="professional_experience" name="professional_experience" value="<?php echo hsc($person->get_professional_experience()); ?>" placeholder="">

            <label>Are there any accomodations you may need? Anything we should keep in mind?</label>
            <input type="text" id="disability_accomodation_needs" name="disability_accomodation_needs" value="<?php echo hsc($person->get_disability_accomodation_needs()); ?>" placeholder="">

            <label>Are there any specific skills/interests you have that you believe could be useful for volunteering at StepVA? </label>
            <input type="text" id="skills" name="skills" value="<?php echo hsc($person->get_skills()); ?>" placeholder="">

            <label>Do you have connections to any local businesses or organizations that might be interested in sponsoring or supporting our programs? </label>
            <input type="text" id="networks" name="networks" value="<?php echo hsc($person->get_networks()); ?>" placeholder="">

            <label>Do you have any additional ways you can contribute to STEPVA? </label>
            <input type="text" id="contributions" name="contributions" value="<?php echo hsc($person->get_contributions()); ?>" placeholder="">
        </fieldset>

        <p></p>
        <p></p>

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="profile-edit-form" value="Update Profile">
        <?php if ($editingSelf): ?>
            <a class="button cancel" href="viewProfile.php" style="margin-top: -.5rem">Cancel</a>
        <?php else: ?>
            <a class="button cancel" href="viewProfile.php?id=<?php echo htmlspecialchars($_GET['id']) ?>" style="margin-top: -.5rem">Cancel</a>
        <?php endif ?>
    </form>
</main>
