<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('database/dbPersons.php');
require_once('domain/Person.php');
require_once('include/input-validation.php');

if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve the logged-in user (family leader)
$parent = retrieve_person($_SESSION['_id']);
if (!$parent) {
    echo "Parent not found.";
    exit;
}

// Get the family ID of the logged-in user
$familyId = $parent->get_familyId();

// Ensure the logged-in user has a valid family ID
if (!$familyId) {
    echo "The logged-in user does not have a valid family ID.";
    exit;
}

// Process the form submission for adding a family member
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $args = sanitize($_POST['family']);

    // Required fields for family member
    $required_fields = ['first_name', 'last_name', 'birthdate', 'street_address', 'city', 'state', 'zip', 'email', 'phone'];

    // Check if all required fields are present
    foreach ($required_fields as $field) {
        if (!isset($args[$field]) || empty($args[$field])) {
            echo "Missing required field: $field";
            exit;
        }
    }

    // Validate fields
    $member_birthdate = validateDate($args['birthdate']);
    if (!$member_birthdate) {
        echo "Invalid birthdate.";
        exit;
    }

    $member_email = strtolower($args['email']);
    $member_email = validateEmail($member_email);
    if (!$member_email) {
        echo "Invalid email.";
        exit;
    }

    $member_phone = validateAndFilterPhoneNumber($args['phone']);
    if (!$member_phone) {
        echo "Invalid phone number.";
        exit;
    }

    // Generate a username and password if the user wants login for the family member
    if (isset($args['has_login']) && $args['has_login'] === 'yes') {
        if (!isset($args['username']) || !isset($args['password']) || $args['password'] !== $args['password_reenter']) {
            echo "Password mismatch or missing details.";
            exit;
        }
        $username = $args['username'];
        $password = password_hash($args['password'], PASSWORD_BCRYPT);
    } else {
        // Generate a default username and password if no login is required
        $timestamp = date('YmdHis');
        $username = strtolower($args['first_name'] . '_' . $args['last_name'] . '_' . $timestamp);
        $password = password_hash("TempPass123!", PASSWORD_BCRYPT); // Default password
    }

    // Create the new family member
    $newperson = new Person(
        $username,  // username
        $password,  // password
        date("Y-m-d"), // registration date
        $args['first_name'],
        $args['last_name'],
        $member_birthdate,
        $args['street_address'],
        $args['city'],
        $args['state'],
        $args['zip'],
        $member_phone,
        $args['phone_type'],
        $member_email,
        $args['emergency_contact_first_name'],
        $args['emergency_contact_last_name'],
        $args['emergency_contact_phone'],
        $args['emergency_contact_phone_type'],
        $args['emergency_contact_relation'],
        $args['tshirt_size'],
        $args['school_affiliation'],
        $args['photo_release'],
        $args['photo_release_notes'],
        'participant', // Role type
        'Active', // Status
        0, // Archived status
        $args['how_you_heard_of_stepva'],
        $args['preferred_feedback_method'],
        $args['hobbies'],
        $args['professional_experience'],
        $args['disability_accomodation_needs'],
        0, // Training status
        null, // Training date
        0, // Orientation status
        null, // Orientation date
        0, // Background check status
        null, // Background check date
        null, // Skills
        null, // Networks
        null, // Contributions
        $familyId, // Family ID
        $args['profile_feature'],
        $args['identification_preference'],
        $args['headshot_publish'],
        $args['likeness_usage']
    );

    // Add the new person to the database
    $result = add_person($newperson);
    if ($result) {
        // Add family member relationship in the family member table
        $add_to_family = add_family_member($username, $familyId);
        if ($add_to_family) {
            echo "<script>
            alert('Family member has been successfully added!');
            window.location.href = 'familyManagementPortal.php'; // Redirect to the family management page
          </script>";
          exit;
        } else {
            echo "There was an issue adding the family member to the family.";
        }
    } else {
        echo "Error: Unable to add new family member.";
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Family Member Registration</title>
</head>
<body><?php require_once('header.php');?></body>
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
            <!-- Add other states -->
            <option value="VA" selected>Virginia</option>
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

    <!-- Optional Fields -->
    <fieldset class="section-box">
        <legend>Optional Fields</legend>

        <label>T-shirt Size</label>
        <input type="text" name="family[tshirt_size]" placeholder="Enter T-shirt size"><br>

        <label>School Affiliation</label>
        <input type="text" name="family[school_affiliation]" placeholder="Enter school affiliation"><br>

        <label>Photo Release</label>
        <select name="family[photo_release]">
            <option value="yes">Yes</option>
            <option value="no" selected>No</option>
        </select><br>

        <label>Photo Release Notes</label>
        <textarea name="family[photo_release_notes]" placeholder="Enter notes about photo release"></textarea><br>

        <label>How did you hear about Step VA?</label>
        <input type="text" name="family[how_you_heard_of_stepva]" placeholder="Enter how you heard about us"><br>

        <label>Preferred Feedback Method</label>
        <select name="family[preferred_feedback_method]">
            <option value="email">Email</option>
            <option value="phone">Phone</option>
            <option value="text">Text</option>
        </select><br>

        <label>Hobbies</label>
        <textarea name="family[hobbies]" placeholder="Enter your hobbies"></textarea><br>

        <label>Professional Experience</label>
        <textarea name="family[professional_experience]" placeholder="Enter professional experience"></textarea><br>

        <label>Disability Accommodation Needs</label>
        <textarea name="family[disability_accomodation_needs]" placeholder="Enter any accommodation needs"></textarea><br>

        <label>Profile Feature</label>
        <input type="text" name="family[profile_feature]" placeholder="Enter profile feature"><br>

        <label>Identification Preference</label>
        <input type="text" name="family[identification_preference]" placeholder="Enter identification preference"><br>

        <label>Headshot Publish</label>
        <select name="family[headshot_publish]">
            <option value="yes">Yes</option>
            <option value="no" selected>No</option>
        </select><br>

        <label>Likeness Usage</label>
        <select name="family[likeness_usage]">
            <option value="yes">Yes</option>
            <option value="no" selected>No</option>
        </select><br>
    </fieldset>

    <!-- Login Option -->
    <fieldset class="section-box">
        <legend>Login Option</legend>
        <label><em>* </em>Should this family member have their own login?</label>
        <div class="radio-group">
            <input type="radio" id="has_login_yes" name="family[has_login]" value="yes" required onclick="toggleLoginFields()">
            <label for="has_login_yes">Yes</label>
            <input type="radio" id="has_login_no" name="family[has_login]" value="no" required onclick="toggleLoginFields()" checked>
            <label for="has_login_no">No</label>
        </div>
    </fieldset>

    <!-- Username and Password Fields (hidden by default) -->
    <div id="loginFields" style="display:none;">
        <label><em>* </em>Username</label>
        <input type="text" name="family[username]" placeholder="Enter username"><br>

        <label><em>* </em>Password</label>
        <input type="password" name="family[password]" placeholder="Enter password"><br>

        <label><em>* </em>Re-enter Password</label>
        <input type="password" name="family[password_reenter]" placeholder="Re-enter password"><br>
    </div>

    <!-- Submit Button -->
    <input type="submit" value="Register Family Member">
</form>
</main>

<script>
    function toggleLoginFields() {
        var loginFields = document.getElementById('loginFields');
        var hasLoginYes = document.getElementById('has_login_yes');

        if (hasLoginYes.checked) {
            loginFields.style.display = 'block'; // Show the login fields
        } else {
            loginFields.style.display = 'none'; // Hide the login fields
        }
    }
</script>
<a class="button cancel" href="familyManagementPortal.php">Return to Family Management Page</a>
</body>
</html>