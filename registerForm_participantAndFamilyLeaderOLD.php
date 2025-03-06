<h1>New Participant Registration</h1>
<main class="signup-form">
    <form class="signup-form" method="post">
        <h2>Participant Registration Form</h2>
        <p>Please fill out each section of the following form if you would like to participate for in the organization.</p>
        <p>An asterisk (<em>*</em>) indicates a required field.</p>
        
        <fieldset class="section-box">
            <legend>Personal Information</legend>

            <p>The following information will help us identify you within our system.</p>
            <label><em>* </em>Will you be signing up as a Family?</label>
            <div class="radio-group">
                <input type="radio" id="yes" name="family_or_individual" value="y" required>
                <label for="yes">Yes</label>

                <input type="radio" id="no" name="family_or_individual" value="n" required>
                <label for="no">No</label>
            </div>

            <div id="num_family_members-section" style="display: none;" >
                <label for="numFamilyMembers">Number of Family Members:</label>
                <input type="number" id="numFamilyMembers" name="num_family_members" min=1 max=100 value=0>
            </div>

            <div id="family_member_info_section" style="display: none;" ></div>

            <script>
                const input = document.getElementById('numFamilyMembers');
                const container = document.getElementById('family_member_info_section');

                function updateFamilyMemberSections() {
                    const count = parseInt(input.value) || 0;
            
                    // Clear existing sections
                    container.innerHTML = '';

                    if (count >= 12){
                        const section = document.createElement('div');
                        //This is where can add the fields associated with the family members
                        section.className = 'section';
                        
                        section.innerHTML = `
                            <div>
                                <label style="color: red;">
                                    Please enter a number less than 12.
                                </label>

                            </div>
                        `;

                        //Add this section to the container
                        container.appendChild(section);
                    } else {
                        const section = document.createElement('div');
                        // Create new sections based on input
                        for (let i = 1; i <= count; i++) {

                            //Label for getting family member age
                            const labelAge = document.createElement('label');
                            labelAge.htmlFor = `age${i}`;
                            labelAge.textContent = `Member ${i} Age`;
                            //Adding the label to the section
                            section.appendChild(labelAge);
                            //Input for getting family member age
                            const inputAge = document.createElement('input');
                            inputAge.type = 'number';
                            inputAge.id = `age${i}`;
                            inputAge.name = `family_mem_age${i}`;
                            inputAge.min = '1';
                            inputAge.max = '120';
                            inputAge.value = '0';
                            //Adding input age to the section
                            section.appendChild(inputAge);
                            
                        }
                        //Add this section to the container
                        container.appendChild(section);
                    }   
                    
                    const family_member_info_section = document.getElementById('family_member_info_section'); // Entire training section
                    if (count === 0){
                        family_member_info_section.style.display = "none";
                    } else {
                        family_member_info_section.style.display = "block";
                    }

                }

                // Add event listener to the input (not using querySelectorAll)
                input.addEventListener('input', updateFamilyMemberSections)

                // Initial check on page load
                document.addEventListener('DOMContentLoaded', () => {
                    updateFamilyMemberSections(); // Toggle the input section for info of family members
                });

            </script>

            <script>
                // Function to toggle the visibility of the training section based on volunteer or participant selection
                function toggleNumFamilyMembersSection() {
                    // Get the value of the hidden input field
                    const familyRadios = document.querySelectorAll('input[name="family_or_individual"]');
                    const numFamilyMembersSection = document.getElementById('num_family_members-section'); // Entire training section
                    
                    let isFamily = false;
                    
                    familyRadios.forEach(radio => {
                        if (radio.checked && radio.value === 'y') {
                            isFamily = true;
                        }
                    });

                    // Show the entire training section only if the user is a volunteer
                    if (isFamily) {
                        numFamilyMembersSection.style.display = 'block';
                    } else {
                        const numFamilyMembersInput = document.getElementById('numFamilyMembers')
                        numFamilyMembersInput.value = 0;
                        updateFamilyMemberSections();
                        numFamilyMembersSection.style.display = 'none';
                    }

                }


                // Event listeners for changes in volunteer/participant selection and the complete statuses
                document.querySelectorAll('input[name="family_or_individual"]').forEach(radio => {
                    radio.addEventListener('change', toggleNumFamilyMembersSection);
                });

                // Initial check on page load
                document.addEventListener('DOMContentLoaded', () => {
                    toggleNumFamilyMembersSection(); // Toggle the input box for number of family members
                });

            </script>
            
            <!-- Default value for volunteer_or_participant -->
            <input type="hidden" name="volunteer_or_participant" value="v">
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
                <input type="radio" id="xs" name="tshirt_size" value="xs" required><label for="tshirt_size">XS</label>
                <input type="radio" id="s" name="tshirt_size" value="s" required><label for="tshirt_size">S</label>
                <input type="radio" id="m" name="tshirt_size" value="m" required><label for="tshirt_size">M</label>
                <input type="radio" id="l" name="tshirt_size" value="l" required><label for="tshirt_size">L</label>
                <input type="radio" id="xl" name="tshirt_size" value="xl" required><label for="tshirt_size">XL</label>
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
        <p>By pressing Submit below, you are agreeing to volunteer for the organization.</p>
        <input type="submit" name="registration-form" value="Submit">
    </form>
    
</main>