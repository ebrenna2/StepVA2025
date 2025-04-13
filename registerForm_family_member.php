<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Family Member Registration</title>
</head>
<body>
    <h1>New Family Member Registration</h1>

    <main class="signup-form">
        <form class="signup-form" method="post" onsubmit="console.log('Submitting'); if (!this.checkValidity()) { console.log('Validation failed'); this.reportValidity(); return false; }">
            <h2>Family Member Information</h2>
            <p>Please fill out the following information for the family member you want to add.</p>
            <p>An asterisk (<em>*</em>) indicates a required field.</p>
            
            <fieldset class="section-box">
                <legend>Family Member Information</legend>
                <p>Provide the details of the family member you wish to register.</p>

                <label><em>* </em>First Name</label>
                <input type="text" name="family[first_name]" required placeholder="Enter family member's first name"><br>

                <label><em>* </em>Last Name</label>
                <input type="text" name="family[last_name]" required placeholder="Enter family member's last name"><br>

                <label><em>* </em>Date of Birth</label>
                <input type="date" name="family[birthdate]" required placeholder="Choose family member's birthday" max="<?php echo date('Y-m-d'); ?>"><br>

                <label><em>* </em>Street Address</label>
                <input type="text" name="family[street_address]" required placeholder="Enter family member's street address"><br>

                <label><em>* </em>City</label>
                <input type="text" name="family[city]" required placeholder="Enter family member's city"><br>

                <label><em>* </em>State</label>
                <select name="family[state]" required>
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
                </select><br>

                <label><em>* </em>Zip Code</label>
                <input type="text" name="family[zip]" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter family member's 5-digit zip code"><br>

                <label><em>* </em>Email</label>
                <input type="email" name="family[email]" required placeholder="Enter family member's email"><br>

                <label><em>* </em>Phone Number</label>
                <input type="text" name="family[phone]" required placeholder="Enter family member's phone number (e.g., (555) 555-5555)"><br>

                <label><em>* </em>Phone Type</label>
                <div class="radio-group">
                    <input type="radio" name="family[phone_type]" value="cellphone" required> Cell<br>
                    <input type="radio" name="family[phone_type]" value="home"> Home<br>
                    <input type="radio" name="family[phone_type]" value="work"> Work<br>
                </div>
            </fieldset>

            <!-- Emergency Contact Information -->
            <fieldset class="section-box">
                <legend>Emergency Contact Information</legend>
                <label><em>* </em>Emergency Contact First Name</label>
                <input type="text" name="family[emergency_contact_first_name]" required placeholder="Enter emergency contact first name"><br>

                <label><em>* </em>Emergency Contact Last Name</label>
                <input type="text" name="family[emergency_contact_last_name]" required placeholder="Enter emergency contact last name"><br>

                <label><em>* </em>Relation to Family Member</label>
                <input type="text" name="family[emergency_contact_relation]" required placeholder="Relation to family member (e.g., Mother, Father)"><br>

                <label><em>* </em>Emergency Contact Phone Number</label>
                <input type="text" name="family[emergency_contact_phone]" required placeholder="Enter emergency contact phone number"><br>

                <label><em>* </em>Emergency Contact Phone Type</label>
                <div class="radio-group">
                    <input type="radio" name="family[emergency_contact_phone_type]" value="cellphone" required> Cell<br>
                    <input type="radio" name="family[emergency_contact_phone_type]" value="home"> Home<br>
                    <input type="radio" name="family[emergency_contact_phone_type]" value="work"> Work<br>
                </div>
            </fieldset>

            <!-- Submit Button -->
            <input type="submit" value="Register Family Member">
        </form>
    </main>
</body>
</html>