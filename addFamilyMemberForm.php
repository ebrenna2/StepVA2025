<form method="POST">
    <input type="hidden" name="add_family_member" value="1">

    <label>First Name: <input type="text" name="first_name" required></label><br>
    <label>Last Name: <input type="text" name="last_name" required></label><br>
    <label>Birthday: <input type="date" name="birthday" required></label><br>
    <label>Street Address: <input type="text" name="street_address" required></label><br>
    <label>City: <input type="text" name="city" required></label><br>
    <label>State: <input type="text" name="state" required></label><br>
    <label>Zip Code: <input type="text" name="zip_code" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Phone 1: <input type="text" name="phone1" required></label><br>
    <label>Phone 1 Type: 
        <select name="phone1type" required>
            <option value="mobile">Mobile</option>
            <option value="home">Home</option>
            <option value="work">Work</option>
        </select>
    </label><br>
    <label>Emergency Contact First Name: <input type="text" name="emergency_contact_first_name" required></label><br>
    <label>Emergency Contact Last Name: <input type="text" name="emergency_contact_last_name" required></label><br>
    <label>Emergency Contact Phone: <input type="text" name="emergency_contact_phone" required></label><br>
    <label>Emergency Contact Phone Type: 
        <select name="emergency_contact_phone_type" required>
            <option value="mobile">Mobile</option>
            <option value="home">Home</option>
            <option value="work">Work</option>
        </select>
    </label><br>
    <label>Emergency Contact Relation: <input type="text" name="emergency_contact_relation" required></label><br>
    <label>School Affiliation: <input type="text" name="school_affiliation" required></label><br>
    <label>T-Shirt Size: 
        <select name="tshirt_size" required>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
        </select>
    </label><br><br>

    <input type="hidden" name="photo_release" value="yes">
    <input type="hidden" name="photo_release_notes" value="">
    <input type="hidden" name="type" value="volunteer">
    <input type="hidden" name="how_you_heard_of_stepva" value="N/A">
    <input type="hidden" name="preferred_feedback_method" value="email">
    <input type="hidden" name="hobbies" value="">
    <input type="hidden" name="professional_experience" value="">
    <input type="hidden" name="disability_accomodation_needs" value="">
    <input type="hidden" name="skills" value="">
    <input type="hidden" name="networks" value="">
    <input type="hidden" name="contributions" value="">

    <input type="hidden" name="training_complete" value="0">
    <input type="hidden" name="training_date" value="<?= date('Y-m-d') ?>">
    <input type="hidden" name="orientation_complete" value="0">
    <input type="hidden" name="orientation_date" value="<?= date('Y-m-d') ?>">
    <input type="hidden" name="background_complete" value="0">
    <input type="hidden" name="background_date" value="<?= date('Y-m-d') ?>">

    <input type="submit" value="Add Family Member">
</form>
