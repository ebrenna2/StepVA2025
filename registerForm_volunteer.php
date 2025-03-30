<h1>New Volunteer Registration</h1>
<main class="signup-form">
    <form class="signup-form" method="post">
        <h2>Registration Form</h2>
        <p>Please fill out each section of the following form if you would like to volunteer for the organization.</p>
        <p>An asterisk (<em>*</em>) indicates a required field.</p>
        
        <fieldset class="section-box">
            <legend>Personal Information</legend>

            <p>The following information will help us identify you within our system.</p>
            <label for="first_name"><em>* </em>First Name</label>
            <input type="text" id="first_name" name="first_name" required placeholder="Enter your first name">

            <label for="last_name"><em>* </em>Last Name</label>
            <input type="text" id="last_name" name="last_name" required placeholder="Enter your last name">

            <label for="birthdate"><em>* </em>Date of Birth</label>
            <input type="date" id="birthdate" name="birthdate" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">
            
            <label for="street_address"><em>* </em>Street Address</label>
            <input type="text" id="street_address" name="street_address" required placeholder="Enter your street address">

            <label for="city"><em>* </em>City</label>
            <input type="text" id="city" name="city" required placeholder="Enter your city">

            <label for="state"><em>* </em>State</label>
            
            <select id="state" name="state" required>
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District Of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA" selected>Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
            </select>

            <label for="zip"><em>* </em>Zip Code</label>
            <input type="text" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">
        </fieldset>

        <fieldset class="section-box">
            <legend>Contact Information</legend>

            <p>The following information will help us determine the best way to contact you regarding event coordination.</p>
            <label for="email"><em>* </em>E-mail</label>
            <input type="email" id="email" name="email" required placeholder="Enter your e-mail address">

            <label for="phone"><em>* </em>Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">

            <label><em>* </em>Phone Type</label>
            <div class="radio-group">
                <input type="radio" id="phone-type-cellphone" name="phone_type" value="cellphone" required><label for="phone-type-cellphone">Cell</label>
                <input type="radio" id="phone-type-home" name="phone_type" value="home" required><label for="phone-type-home">Home</label>
                <input type="radio" id="phone-type-work" name="phone_type" value="work" required><label for="phone-type-work">Work</label>
            </div>

        </fieldset>

        <fieldset class="section-box">
            <legend>Emergency Contact</legend>

            <p>Please provide us with someone to contact on your behalf in case of an emergency.</p>
            <label for="emergency_contact_first_name" required><em>* </em>Contact First Name</label>
            <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" required placeholder="Enter emergency contact first name">

            <label for="emergency_contact_last_name" required><em>* </em>Contact Last Name</label>
            <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" required placeholder="Enter emergency contact last name">

            <label for="emergency_contact_relation"><em>* </em>Contact Relation to You</label>
            <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">

            <label for="emergency_contact_phone"><em>* </em>Contact Phone Number</label>
            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Enter emergency contact phone number. Ex. (555) 555-5555">

            <label><em>* </em>Contact Phone Type</label>
            <div class="radio-group">
                <input type="radio" id="phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone" required><label for="phone-type-cellphone">Cell</label>
                <input type="radio" id="phone-type-home" name="emergency_contact_phone_type" value="home" required><label for="phone-type-home">Home</label>
                <input type="radio" id="phone-type-work" name="emergency_contact_phone_type" value="work" required><label for="phone-type-work">Work</label>
            </div>
        </fieldset>

        <fieldset class="section-box">
    <legend>Other Required Information</legend>

    <p>Here are a few other pieces on information we need from you.</p>

    <input type="hidden" name="volunteer_or_participant" value="v">

    <label><em>* </em>T-Shirt Size</label>
    <div class="radio-group">
        <input type="radio" id="xxs" name="tshirt_size" value="xxs" required><label for="tshirt_size">XXS</label>
        <input type="radio" id="xs" name="tshirt_size" value="xs" required><label for="tshirt_size">XS</label>
        <input type="radio" id="s" name="tshirt_size" value="s" required><label for="tshirt_size">S</label>
        <input type="radio" id="m" name="tshirt_size" value="m" required><label for="tshirt_size">M</label>
        <input type="radio" id="l" name="tshirt_size" value="l" required><label for="tshirt_size">L</label>
        <input type="radio" id="xl" name="tshirt_size" value="xl" required><label for="tshirt_size">XL</label>
        <input type="radio" id="xxl" name="tshirt_size" value="xxl" required><label for="tshirt_size">XXL</label>
    </div>

    <label for="school_affiliation"><em>* </em>School Affiliation (or N/A)</label>
    <input type="text" id="school_affiliation" name="school_affiliation" required placeholder="Are you affiliated with any school?">

    <label for="photo_release"><em>* </em>Photo Release Restrictions: Can your photo be taken and used on our website and social media?</label>
    <div class="radio-group">
        <input type="radio" id="Restricted" name="photo_release" value="Restricted" required><label for="photo_release">Restricted</label>
        <input type="radio" id="Not Restricted" name="photo_release" value="Not Restricted" required><label for="photo_release">Not Restricted</label>
    </div>

    <label for="photo_release_notes"><em>* </em>Photo Release Restriction Notes (or N/A)</label>
    <input type="text" id="photo_release_notes" name="photo_release_notes" required placeholder="Do you have any specific notes about your photo release status?">

    <!-- New Photo Release Details Section -->
    <div id="photo-release-details" style="display: none;">
        <label><em>* </em>Can your cast or crew member be featured in a profile?</label>
        <div class="radio-group">
            <input type="radio" id="profile-yes" name="profile_feature" value="Yes" required>
            <label for="profile-yes">Yes</label>
            <input type="radio" id="profile-no" name="profile_feature" value="No" required>
            <label for="profile-no">No</label>
        </div>

        <label><em>* </em>How would you like your cast/crew member identified?</label>
        <div class="radio-group">
            <input type="radio" id="id-full-name" name="identification_preference" value="First and last name">
            <label for="id-full-name">First and last name</label>
            <input type="radio" id="id-first-name" name="identification_preference" value="First name and last initial">
            <label for="id-first-name">First name and Last initial</label>
            <input type="radio" id="id-initials" name="identification_preference" value="Initials only">
            <label for="id-initials">Initials only</label>
        </div>

        <label><em>* </em>Can we publish your cast/crew member’s head shot with their profile (on STEP VA’s website, Facebook, and Instagram)?</label>
        <div class="radio-group">
            <input type="radio" id="headshot-yes" name="headshot_publish" value="Yes" required>
            <label for="headshot-yes">Yes</label>
            <input type="radio" id="headshot-no" name="headshot_publish" value="No" required>
            <label for="headshot-no">No</label>
        </div>

        <label><em>* </em>Can we use your cast/crew member’s likeness (photos or video clips) on show marketing materials? This includes social media posts, video shorts, flyers, etc.</label>
        <div class="radio-group">
            <input type="radio" id="likeness-yes" name="likeness_usage" value="Yes" required>
            <label for="likeness-yes">Yes</label>
            <input type="radio" id="likeness-no" name="likeness_usage" value="No" required>
            <label for="likeness-no">No</label>
            <input type="radio" id="likeness-filter" name="likeness_usage" value="Only with a filter" required>
            <label for="likeness-filter">Only with a filter</label>
        </div>
    </div>
</fieldset>

        <fieldset class="section-box">
            <legend>Optional Information</legend>

            <p>Here are some optional pieces of information you can give us.</p>

            <label>How did you hear about StepVA?</label>
            <input type="text" id="how_you_heard_of_stepva" name="how_you_heard_of_stepva" placeholder="">

            <label>What is your preferred contact method?</label>
            <div class="radio-group">
                <input type="radio" id="text" name="preferred_feedback_method" value="text"><label for="preferred_contact_method">Text</label>
                <input type="radio" id="email" name="preferred_feedback_method" value="email"><label for="preferred_contact_method">Email</label>
                <input type="radio" id="no-preference" name="preferred_feedback_method" value="No preference" checked><label for="preferred_feedback_method">No preference</label>
            </div>

            <label>What are your hobbies? Are there any specific skills/interests you have that you believe could be useful for volunteering at StepVA?</label>
            <input type="text" id="hobbies" name="hobbies" placeholder="">

            <label>Do you have any other experience with volunteering?</label>
            <input type="text" id="professional_experience" name="professional_experience" placeholder="">

            <label>Are there any accomodations you may need? Anything we should keep in mind?</label>
            <input type="text" id="disability_accomodation_needs" name="disability_accomodation_needs" placeholder="">

            <label>Are there any specific skills/interests you have that you believe could be useful for volunteering at StepVA? </label>
            <input type="text" id="skills" name="skills" placeholder="">

            <label>Do you have connections to any local businesses or organizations that might be interested in sponsoring or supporting our programs? </label>
            <input type="text" id="networks" name="networks" placeholder="">

            <label>Do you have any additional ways you can contribute to STEPVA? </label>
            <input type="text" id="contributions" name="contributions" placeholder="">

        </fieldset>

        <fieldset class="section-box" id="training-info-section" style="display: none;">
            <legend>Training Information</legend>
            <!--<p>If you are a volunteer, please indicate your training status.</p>-->

            <div id="training-info">
                <label><em>* </em>Training Complete?</label>
                <div class="radio-group">
                    <input type="radio" id="training-complete-yes" name="training_complete" value="1">
                    <label for="training-complete-yes">Yes</label>
                    <input type="radio" id="training-complete-no" name="training_complete" value="0">
                    <label for="training-complete-no">No</label>
                </div>

                <label for="training_date" id="training-date-label" style="display: none;">Training Date</label>
                <input type="date" id="training_date" name="training_date" placeholder="Enter training date" style="display: none;" max="<?php echo date('Y-m-d'); ?>">

                <!-- Orientation Information -->
                <label><em>* </em>Orientation Complete?</label>
                <div class="radio-group">
                    <input type="radio" id="orientation-complete-yes" name="orientation_complete" value="1">
                    <label for="orientation-complete-yes">Yes</label>
                    <input type="radio" id="orientation-complete-no" name="orientation_complete" value="0">
                    <label for="orientation-complete-no">No</label>
                </div>

                <label for="orientation_date" id="orientation-date-label" style="display: none;">Orientation Date</label>
                <input type="date" id="orientation_date" name="orientation_date" placeholder="Enter orientation date" style="display: none;" max="<?php echo date('Y-m-d'); ?>">

                <!-- Background Information -->
                <label><em>* </em>Background Check Complete?</label>
                <div class="radio-group">
                    <input type="radio" id="background-complete-yes" name="background_complete" value="1">
                    <label for="background-complete-yes">Yes</label>
                    <input type="radio" id="background-complete-no" name="background_complete" value="0">
                    <label for="background-complete-no">No</label>
                </div>

                <label for="background_date" id="background-date-label" style="display: none;">Background Date</label>
                <input type="date" id="background_date" name="background_date" placeholder="Enter background date" style="display: none;" max="<?php echo date('Y-m-d'); ?>">
            </div>
        </fieldset>


            <script>
            // Function to toggle the visibility of the training section based on volunteer or participant selection
            function toggleTrainingSection() {
                const volunteerOrParticipant = document.querySelector('input[name="volunteer_or_participant"]').value;
                const trainingInfoSection = document.getElementById('training-info-section');

                if (volunteerOrParticipant === 'v') {
                    trainingInfoSection.style.display = 'block';
                } else {
                    trainingInfoSection.style.display = 'none';
                }

                toggleTrainingDateField();
                toggleOrientationDateField();
                toggleBackgroundDateField();
            }

            // Function to toggle the visibility of the training date field
            function toggleTrainingDateField() {
                const trainingCompleteYes = document.getElementById('training-complete-yes');
                const trainingCompleteNo = document.getElementById('training-complete-no');
                const trainingDateField = document.getElementById('training_date');
                const trainingDateLabel = document.getElementById('training-date-label');
                if (trainingCompleteYes.checked) {
                    trainingDateField.style.display = 'inline';
                    trainingDateLabel.style.display = 'inline';
                } else {
                    trainingDateField.style.display = 'none';
                    trainingDateLabel.style.display = 'none';
                }
            }

            // Function to toggle the visibility of the orientation date field
            function toggleOrientationDateField() {
                const orientationCompleteYes = document.getElementById('orientation-complete-yes');
                const orientationCompleteNo = document.getElementById('orientation-complete-no');
                const orientationDateField = document.getElementById('orientation_date');
                const orientationDateLabel = document.getElementById('orientation-date-label');

                if (orientationCompleteYes.checked) {
                    orientationDateField.style.display = 'inline';
                    orientationDateLabel.style.display = 'inline';
                } else {
                    orientationDateField.style.display = 'none';
                    orientationDateLabel.style.display = 'none';
                }
            }

            // Function to toggle the visibility of the background date field
            function toggleBackgroundDateField() {
                const backgroundCompleteYes = document.getElementById('background-complete-yes');
                const backgroundCompleteNo = document.getElementById('background-complete-no');
                const backgroundDateField = document.getElementById('background_date');
                const backgroundDateLabel = document.getElementById('background-date-label');

                if (backgroundCompleteYes.checked) {
                    backgroundDateField.style.display = 'inline';
                    backgroundDateLabel.style.display = 'inline';
                } else {
                    backgroundDateField.style.display = 'none';
                    backgroundDateLabel.style.display = 'none';
                }
            }

            // Function to toggle the visibility of the photo release details section
            function togglePhotoReleaseDetails() {
                const notRestricted = document.getElementById('Not Restricted');
                const photoReleaseDetails = document.getElementById('photo-release-details');

                if (notRestricted.checked) {
                    photoReleaseDetails.style.display = 'block';
                    document.querySelectorAll('#photo-release-details input').forEach(input => {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    photoReleaseDetails.style.display = 'none';
                    document.querySelectorAll('#photo-release-details input').forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            }

            // Event listeners for changes in volunteer/participant selection and the complete statuses
            document.querySelectorAll('input[name="volunteer_or_participant"]').forEach(radio => {
                radio.addEventListener('change', toggleTrainingSection);
            });

            document.getElementById('training-complete-yes').addEventListener('change', toggleTrainingDateField);
            document.getElementById('training-complete-no').addEventListener('change', toggleTrainingDateField);

            document.getElementById('orientation-complete-yes').addEventListener('change', toggleOrientationDateField);
            document.getElementById('orientation-complete-no').addEventListener('change', toggleOrientationDateField);

            document.getElementById('background-complete-yes').addEventListener('change', toggleBackgroundDateField);
            document.getElementById('background-complete-no').addEventListener('change', toggleBackgroundDateField);

            // Event listener for photo release radio buttons
            document.querySelectorAll('input[name="photo_release"]').forEach(radio => {
                radio.addEventListener('change', togglePhotoReleaseDetails);
            });

            // Initial check on page load
            document.addEventListener('DOMContentLoaded', () => {
                toggleTrainingSection();
                toggleTrainingDateField();
                toggleOrientationDateField();
                toggleBackgroundDateField();
                togglePhotoReleaseDetails(); // Add this to ensure the photo release section is correctly displayed on page load
            });
            </script>


        <fieldset class="section-box">
            <legend>Login Credentials</legend>
            
            <p>You will use the following information to log in to the system.</p>

            <label for="username"><em>* </em>Username</label>
            <input type="text" id="username" name="username" required placeholder="Enter a username">

            <label for="password"><em>* </em>Password</label>
            <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
            <p id="password-error" class="error hidden">Password needs to be at least 8 characters long, contain at least one number, one uppercase letter, and one lowercase letter!</p>

            <label for="password-reenter"><em>* </em>Re-enter Password</label>
            <input type="password" id="password-reenter" name="password-reenter" placeholder="Re-enter password" required>
            <p id="password-match-error" class="error hidden">Passwords do not match!</p>
        </fieldset>
        <p>By pressing Submit below, you are agreeing to volunteer for the organization.</p>
        <input type="submit" name="registration-form" value="Submit">
    </form>
    
</main>