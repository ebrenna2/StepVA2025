<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Participant Registration</title>
</head>
<body>
    <h1>New Participant Registration</h1>
    <main class="signup-form">

        <div id="test-submit-container">
            <button type="button" id="test-submit-btn">Test Submit</button>
            <p style="font-size: 12px;">Click to auto-fill and submit with dummy data (for testing only)</p>
        </div>

<script>
    //View this one
  // Isolated test script - remove this block when done testing
  document.getElementById('test-submit-btn').addEventListener('click', async function() {
    const form = document.querySelector('form.signup-form');

    // Generate unique username with date/time
    const now = new Date();
    const timestamp = now.toISOString().replace(/[-:T.]/g, '').slice(0, 14); // e.g., 20250307123456
    const uniqueUsername = `testuser${timestamp}`;

    // Fill participant fields (before family section)
    form.querySelector('#first_name').value = 'John';
    form.querySelector('#last_name').value = 'Doe';
    form.querySelector('#birthdate').value = '1990-01-01';
    form.querySelector('#street_address').value = '123 Test St';
    form.querySelector('#city').value = 'Testville';
    form.querySelector('#state').value = 'VA';
    form.querySelector('#zip').value = '12345';
    form.querySelector('#email').value = 'john.doe@test.com';
    form.querySelector('#phone').value = '(555) 555-5555';
    form.querySelector('input[name="phone_type"][value="cellphone"]').checked = true;
    form.querySelector('#emergency_contact_first_name').value = 'Jane';
    form.querySelector('#emergency_contact_last_name').value = 'Doe';
    form.querySelector('#emergency_contact_relation').value = 'Spouse';
    form.querySelector('#emergency_contact_phone').value = '(555) 555-6666';
    form.querySelector('input[name="emergency_contact_phone_type"][value="cellphone"]').checked = true;
    form.querySelector('input[name="tshirt_size"][value="m"]').checked = true;
    form.querySelector('#school_affiliation').value = 'N/A';
    form.querySelector('input[name="photo_release"][value="Not Restricted"]').checked = true;
    form.querySelector('#photo_release_notes').value = 'N/A';

    // Fill participant optional fields
    form.querySelector('#how_you_heard_of_stepva').value = 'Friend';
    form.querySelector('input[name="preferred_feedback_method"][value="email"]').checked = true;
    form.querySelector('#hobbies').value = 'Reading, hiking';
    form.querySelector('#professional_experience').value = 'Volunteered at local shelter';
    form.querySelector('#disability_accomodation_needs').value = 'None';

    // Trigger family section
    const familyRadio = form.querySelector('input[name="family_or_individual"][value="y"]');
    familyRadio.checked = true;
    familyRadio.dispatchEvent(new Event('change', { bubbles: true })); // Trigger toggleNumFamilyMembersSection

    const numFamilyInput = form.querySelector('#numFamilyMembers');
    numFamilyInput.value = 1; // One family member
    numFamilyInput.dispatchEvent(new Event('input', { bubbles: true })); // Trigger updateFamilyMemberSections

    // Wait for family section to render (async delay)
    await new Promise(resolve => setTimeout(resolve, 250)); // 250ms delay

    // Fill family member fields
    const familySection = form.querySelector('#family_member_info_section');
    if (familySection.querySelector('#family_first_name_1')) {
      familySection.querySelector('#family_first_name_1').value = 'Junior';
      familySection.querySelector('#family_last_name_1').value = 'Doe';
      familySection.querySelector('#family_birthdate_1').value = '2015-01-01';
      familySection.querySelector('#family_street_address_1').value = '123 Test St';
      familySection.querySelector('#family_city_1').value = 'Testville';
      familySection.querySelector('#family_state_1').value = 'VA';
      familySection.querySelector('#family_zip_1').value = '12345';
      familySection.querySelector('#family_email_1').value = 'junior.doe@test.com';
      familySection.querySelector('#family_phone_1').value = '(555) 555-7777';
      familySection.querySelector('input[name="family[1][phone_type]"][value="cellphone"]').checked = true;
      familySection.querySelector('#family_emergency_contact_first_name_1').value = 'Jane';
      familySection.querySelector('#family_emergency_contact_last_name_1').value = 'Doe';
      familySection.querySelector('#family_emergency_contact_relation_1').value = 'Mother';
      familySection.querySelector('#family_emergency_contact_phone_1').value = '(555) 555-8888';
      familySection.querySelector('input[name="family[1][emergency_contact_phone_type]"][value="cellphone"]').checked = true;
      familySection.querySelector('input[name="family[1][tshirt_size]"][value="s"]').checked = true;
      familySection.querySelector('#family_school_affiliation_1').value = 'N/A';
      familySection.querySelector('input[name="family[1][photo_release]"][value="Not Restricted"]').checked = true;
      familySection.querySelector('#family_photo_release_notes_1').value = 'N/A';
      // Fill family optional fields
      familySection.querySelector('#family_how_you_heard_of_stepva_1').value = 'Parent';
      familySection.querySelector('input[name="family[1][preferred_feedback_method]"][value="text"]').checked = true;
      familySection.querySelector('#family_hobbies_1').value = 'Drawing, soccer';
      familySection.querySelector('#family_professional_experience_1').value = 'None';
      familySection.querySelector('#family_disability_accomodation_needs_1').value = 'None';
      familySection.querySelector('#family_username_1').value = `${uniqueUsername}_jr`;
      familySection.querySelector('#family_password_1').value = 'Password2!';
      familySection.querySelector('#family_password_reenter_1').value = 'Password2!';
    } else {
      console.error('Family member fields not rendered!');
    }

    // Fill sections below family (Login Credentials)
    form.querySelector('#username').value = uniqueUsername;
    form.querySelector('#password').value = 'Password2!';
    form.querySelector('#password-reenter').value = 'Password2!';

    // Submit the form
    console.log('Base phone length:', form.querySelector('#phone').value.length);
    console.log('Base phone exact:', JSON.stringify(form.querySelector('#phone').value));
    console.log('Family phone length:', familySection.querySelector('#family_phone_1')?.value.length);
    console.log('Family phone exact:', JSON.stringify(familySection.querySelector('#family_phone_1')?.value));
    //form.submit();
  });
</script>

        <?php //<form class="signup-form" method="post"> ?>
        <form class="signup-form" method="post" onsubmit="console.log('Submitting'); if (!this.checkValidity()) { console.log('Validation failed'); this.reportValidity(); return false; }">
            <h2>Participant Registration Form</h2>
            <p>Please fill out each section of the following form if you would like to participate in an event.</p>
            <p>An asterisk (<em>*</em>) indicates a required field.</p>
            
            <fieldset class="section-box">
                <legend>Family Information</legend>
                <p>Here is where details on participating family members are given.</p>

                <label><em>* </em>Will you be signing up as a Family?</label>
                <div class="radio-group">
                    <input type="radio" id="yes" name="family_or_individual" value="y" required>
                    <label for="yes">Yes</label>
                    <input type="radio" id="no" name="family_or_individual" value="n" >
                    <label for="no">No</label>
                </div>

                <div id="num_family_members-section" style="display: none;">
                    <label for="numFamilyMembers">Number of Family Members:</label>
                    <input type="number" id="numFamilyMembers" name="num_family_members" min="1" max="100" value="0">
                </div>

                <div id="family_member_info_section" style="display: none;"></div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        toggleNumFamilyMembersSection();
                        updateFamilyMemberSections();
                    });

                    const numFamilyInput = document.getElementById('numFamilyMembers');
                    const familyInfoContainer = document.getElementById('family_member_info_section');

                    function updateFamilyMemberSections() {
                        const count = parseInt(numFamilyInput.value) || 0;
                        familyInfoContainer.innerHTML = '';
                        familyInfoContainer.style.display = count > 0 ? 'block' : 'none';

                        if (count >= 12) {
                            familyInfoContainer.innerHTML = `
                                <div class="section">
                                    <label style="color: red;">Please enter a number less than 12.</label>
                                </div>
                            `;
                        } else if (count > 0) {        
                            for (let i = 1; i <= count; i++) {
                                const section = document.createElement('fieldset');
                                section.className = 'section-box family-member-section';
                                section.innerHTML = `
                                    <legend>Family Member ${i} Information</legend>
                                    <input type="hidden" name="family[${i}][volunteer_or_participant]" value="p">

                                    <label for="family_first_name_${i}"><em>* </em>First Name</label>
                                    <input type="text" id="family_first_name_${i}" name="family[${i}][first_name]" required placeholder="Enter family member ${i}'s first name">

                                    <label for="family_last_name_${i}"><em>* </em>Last Name</label>
                                    <input type="text" id="family_last_name_${i}" name="family[${i}][last_name]" required placeholder="Enter family member ${i}'s last name">

                                    <label for="family_birthdate_${i}"><em>* </em>Date of Birth</label>
                                    <input type="date" id="family_birthdate_${i}" name="family[${i}][birthdate]" required placeholder="Choose family member ${i}'s birthday" max="<?php echo date('Y-m-d'); ?>">

                                    <label for="family_street_address_${i}"><em>* </em>Street Address</label>
                                    <input type="text" id="family_street_address_${i}" name="family[${i}][street_address]" required placeholder="Enter family member ${i}'s street address">

                                    <label for="family_city_${i}"><em>* </em>City</label>
                                    <input type="text" id="family_city_${i}" name="family[${i}][city]" required placeholder="Enter family member ${i}'s city">

                                    <label for="family_state_${i}"><em>* </em>State</label>
                                    <select id="family_state_${i}" name="family[${i}][state]" required>
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

                                    <label for="family_zip_${i}"><em>* </em>Zip Code</label>
                                    <input type="text" id="family_zip_${i}" name="family[${i}][zip]" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter family member ${i}'s 5-digit zip code">

                                    <legend>Contact Information</legend>
                                    <p>The following information will help us determine the best way to contact family member ${i} regarding event coordination.</p>

                                    <label for="family_email_${i}"><em>* </em>E-mail</label>
                                    <input type="email" id="family_email_${i}" name="family[${i}][email]" required placeholder="Enter family member ${i}'s e-mail address">

                                    <label for="family_phone_${i}"><em>* </em>Phone Number</label>
                                    <input type="text" id="family_phone_${i}" name="family[${i}][phone]" required placeholder="Ex. (555) 555-5555">

                                    <label><em>* </em>Phone Type</label>
                                    <div class="radio-group">
                                        <input type="radio" id="family_phone_type_cellphone_${i}" name="family[${i}][phone_type]" value="cellphone" required><label for="family_phone_type_cellphone_${i}">Cell</label>
                                        <input type="radio" id="family_phone_type_home_${i}" name="family[${i}][phone_type]" value="home"><label for="family_phone_type_home_${i}">Home</label>
                                        <input type="radio" id="family_phone_type_work_${i}" name="family[${i}][phone_type]" value="work"><label for="family_phone_type_work_${i}">Work</label>
                                    </div>

                                    <legend>Emergency Contact</legend>
                                    <p>Please provide us with someone to contact on behalf of family member ${i} in case of an emergency.</p>

                                    <label for="family_emergency_contact_first_name_${i}"><em>* </em>Contact First Name</label>
                                    <input type="text" id="family_emergency_contact_first_name_${i}" name="family[${i}][emergency_contact_first_name]" required placeholder="Enter emergency contact first name">

                                    <label for="family_emergency_contact_last_name_${i}"><em>* </em>Contact Last Name</label>
                                    <input type="text" id="family_emergency_contact_last_name_${i}" name="family[${i}][emergency_contact_last_name]" required placeholder="Enter emergency contact last name">

                                    <label for="family_emergency_contact_relation_${i}"><em>* </em>Contact Relation to Family Member ${i}</label>
                                    <input type="text" id="family_emergency_contact_relation_${i}" name="family[${i}][emergency_contact_relation]" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">

                                    <label for="family_emergency_contact_phone_${i}"><em>* </em>Contact Phone Number</label>
                                    <input type="text" id="family_emergency_contact_phone_${i}" name="family[${i}][emergency_contact_phone]" required placeholder="Enter emergency contact phone number. Ex. (555) 555-5555">

                                    <label><em>* </em>Contact Phone Type</label>
                                    <div class="radio-group">
                                        <input type="radio" id="family_emergency_phone_type_cellphone_${i}" name="family[${i}][emergency_contact_phone_type]" value="cellphone" required><label for="family_emergency_phone_type_cellphone_${i}">Cell</label>
                                        <input type="radio" id="family_emergency_phone_type_home_${i}" name="family[${i}][emergency_contact_phone_type]" value="home"><label for="family_emergency_phone_type_home_${i}">Home</label>
                                        <input type="radio" id="family_emergency_phone_type_work_${i}" name="family[${i}][emergency_contact_phone_type]" value="work"><label for="family_emergency_phone_type_work_${i}">Work</label>
                                    </div>

                                    <legend>Other Required Information</legend>
                                    <p>Here are a few other pieces of information we need from family member ${i}.</p>

                                    <label><em>* </em>T-Shirt Size</label>
                                    <div class="radio-group">
                                        <input type="radio" id="family_xxs_${i}" name="family[${i}][tshirt_size]" value="xxs" required><label for="family_xxs_${i}">XXS</label>
                                        <input type="radio" id="family_xs_${i}" name="family[${i}][tshirt_size]" value="xs"><label for="family_xs_${i}">XS</label>
                                        <input type="radio" id="family_s_${i}" name="family[${i}][tshirt_size]" value="s"><label for="family_s_${i}">S</label>
                                        <input type="radio" id="family_m_${i}" name="family[${i}][tshirt_size]" value="m"><label for="family_m_${i}">M</label>
                                        <input type="radio" id="family_l_${i}" name="family[${i}][tshirt_size]" value="l"><label for="family_l_${i}">L</label>
                                        <input type="radio" id="family_xl_${i}" name="family[${i}][tshirt_size]" value="xl"><label for="family_xl_${i}">XL</label>
                                        <input type="radio" id="family_xxl_${i}" name="family[${i}][tshirt_size]" value="xxl"><label for="family_xxl_${i}">XXL</label>
                                    </div>

                                    <label for="family_school_affiliation_${i}"><em>* </em>School Affiliation (or N/A)</label>
                                    <input type="text" id="family_school_affiliation_${i}" name="family[${i}][school_affiliation]" required placeholder="Are you affiliated with any school?">

                                    <label for="family_photo_release_${i}"><em>* </em>Photo Release Restrictions: Can family member ${i}'s photo be taken and used on our website and social media?</label>
                                    <div class="radio-group">
                                        <input type="radio" id="family_restricted_${i}" name="family[${i}][photo_release]" value="Restricted" required><label for="family_restricted_${i}">Restricted</label>
                                        <input type="radio" id="family_not_restricted_${i}" name="family[${i}][photo_release]" value="Not Restricted"><label for="family_not_restricted_${i}">Not Restricted</label>
                                    </div>

                                    <label for="family_photo_release_notes_${i}"><em>* </em>Photo Release Restriction Notes (or N/A)</label>
                                    <input type="text" id="family_photo_release_notes_${i}" name="family[${i}][photo_release_notes]" required placeholder="Do you have any specific notes about your photo release status?">

                                    <legend>Optional Information</legend>
                                    <p>Here are some optional pieces of information family member ${i} can give us.</p>

                                    <label>How did family member ${i} hear about StepVA?</label>
                                    <input type="text" id="family_how_you_heard_of_stepva_${i}" name="family[${i}][how_you_heard_of_stepva]" placeholder="">

                                    <label>What is family member ${i}'s preferred contact method?</label>
                                    <div class="radio-group">
                                        <input type="radio" id="family_text_${i}" name="family[${i}][preferred_feedback_method]" value="text"><label for="family_text_${i}">Text</label>
                                        <input type="radio" id="family_email_${i}" name="family[${i}][preferred_feedback_method]" value="email"><label for="family_email_${i}">Email</label>
                                        <input type="radio" id="family_no_preference_${i}" name="family[${i}][preferred_feedback_method]" value="No preference" checked><label for="family_no_preference_${i}">No preference</label>
                                    </div>

                                    <label>What are family member ${i}'s hobbies? Are there any specific skills/interests they have that could be useful for volunteering at StepVA?</label>
                                    <input type="text" id="family_hobbies_${i}" name="family[${i}][hobbies]" placeholder="">

                                    <label>Does family member ${i} have any other experience with volunteering?</label>
                                    <input type="text" id="family_professional_experience_${i}" name="family[${i}][professional_experience]" placeholder="">

                                    <label>Are there any accommodations family member ${i} may need? Anything we should keep in mind?</label>
                                    <input type="text" id="family_disability_accomodation_needs_${i}" name="family[${i}][disability_accomodation_needs]" placeholder="">

                                    <legend>Login Credentials</legend>
                                    <p>Family member ${i} will use the following information to log in to the system if you choose to create a login for them.</p>

                                    <label><em>* </em>Should this family member have their own login?</label>
                                    <div class="radio-group">
                                        <input type="radio" id="has_login_yes_${i}" name="family[${i}][has_login]" value="yes" onclick="toggleCredentials(${i}, true)" required><label for="has_login_yes_${i}">Yes</label>
                                        <input type="radio" id="has_login_no_${i}" name="family[${i}][has_login]" value="no" onclick="toggleCredentials(${i}, false)" required><label for="has_login_no_${i}">No</label>
                                    </div>

                                    <div id="credentials_section_${i}" style="display:none;">
                                        <label for="family_username_${i}"><em>* </em>Username</label>
                                        <input type="text" id="family_username_${i}" name="family[${i}][username]" placeholder="Enter a username">

                                        <label for="family_password_${i}"><em>* </em>Password</label>
                                        <input type="password" id="family_password_${i}" name="family[${i}][password]" placeholder="Enter a strong password">
                                        <p id="family_password_error_${i}" class="error hidden">Password needs to be at least 8 characters long, contain at least one number, one uppercase letter, and one lowercase letter!</p>

                                        <label for="family_password_reenter_${i}"><em>* </em>Re-enter Password</label>
                                        <input type="password" id="family_password_reenter_${i}" name="family[${i}][password_reenter]" placeholder="Re-enter password">
                                        <p id="family_password_match_error_${i}" class="error hidden">Passwords do not match!</p>
                                    </div>
                                `;
                                familyInfoContainer.appendChild(section);
                            }
                        } else {
                            familyInfoContainer.innerHTML = ``;
                        }
                    }

                    function toggleCredentials(i, show) {
                        const credentialsSection = document.getElementById('credentials_section_' + i);
                        const usernameField = document.getElementById('family_username_' + i);
                        const passwordField = document.getElementById('family_password_' + i);
                        const passwordReenterField = document.getElementById('family_password_reenter_' + i);
                        if (show) {
                            credentialsSection.style.display = 'block';
                            usernameField.disabled = false;
                            passwordField.disabled = false;
                            passwordReenterField.disabled = false;
                            usernameField.required = true;
                            passwordField.required = true;
                            passwordReenterField.required = true;
                        } else {
                            credentialsSection.style.display = 'none';
                            usernameField.disabled = true;
                            passwordField.disabled = true;
                            passwordReenterField.disabled = true;
                            usernameField.required = false;
                            passwordField.required = false;
                            passwordReenterField.required = false;
                            usernameField.value = '';
                            passwordField.value = '';
                            passwordReenterField.value = '';
                        }
                    }

                    function toggleNumFamilyMembersSection() {
                        const familyRadios = document.querySelectorAll('input[name="family_or_individual"]');
                        const numFamilySection = document.getElementById('num_family_members-section');
                        let isFamily = false;

                        familyRadios.forEach(radio => {
                            if (radio.checked && radio.value === 'y') isFamily = true;
                        });

                        if (isFamily) {
                            numFamilySection.style.display = 'block';
                            numFamilyInput.setAttribute('min', '1'); 
                        } else {
                            numFamilyInput.value = 0;
                            numFamilySection.style.display = 'none';
                            numFamilyInput.setAttribute('min', '0'); // Allow 0 for individual
                            updateFamilyMemberSections();
                        }
                    }

                    numFamilyInput.addEventListener('input', () => {
                        if (numFamilyInput.value < 0) numFamilyInput.value = 0;
                        if (numFamilyInput.value > 12) numFamilyInput.value = 12;
                        updateFamilyMemberSections();
                    });

                    document.querySelectorAll('input[name="family_or_individual"]').forEach(radio => {
                        radio.addEventListener('change', toggleNumFamilyMembersSection);
                    });
                </script>
            </fieldset>

            <fieldset class="section-box">
                <legend>Personal Information</legend>
                <p>The following information will help us identify you within our system.</p>
            

                <input type="hidden" name="volunteer_or_participant" value="p">
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
            <input type="radio" id="profile-yes" name="profile_feature" value="Yes" >
            <label for="profile-yes">Yes</label>
            <input type="radio" id="profile-no" name="profile_feature" value="No" >
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
            <input type="radio" id="headshot-yes" name="headshot_publish" value="Yes" >
            <label for="headshot-yes">Yes</label>
            <input type="radio" id="headshot-no" name="headshot_publish" value="No" >
            <label for="headshot-no">No</label>
        </div>

        <label><em>* </em>Can we use your cast/crew member’s likeness (photos or video clips) on show marketing materials? This includes social media posts, video shorts, flyers, etc.</label>
        <div class="radio-group">
            <input type="radio" id="likeness-yes" name="likeness_usage" value="Yes" >
            <label for="likeness-yes">Yes</label>
            <input type="radio" id="likeness-no" name="likeness_usage" value="No" >
            <label for="likeness-no">No</label>
            <input type="radio" id="likeness-filter" name="likeness_usage" value="Only with a filter" >
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
            <input type="submit" name="registration-form" value="Submit">

        </form>
    </main>
</body>
</html>
<script>
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

    document.querySelectorAll('input[name="photo_release"]').forEach(radio => {
        radio.addEventListener('change', togglePhotoReleaseDetails);
    });
            
    document.addEventListener('DOMContentLoaded', () => {
        togglePhotoReleaseDetails(); // Add this to ensure the photo release section is correctly displayed on page load
    });
</script>