# Step VA Web Management System
## Purpose
This project is the result of a semester's worth of collaboration among UMW students. The goal of the project was to create a web application that Step VA could utilize to make it easier to track and manage both volunteers and events. At-a-glance features include a web-based calendar of events, event sign up, volunteer registration & login system, reporting system, basic notification system, report generation, video management system, as well as participant and family management.

## Authors
Throughout the course of this projects' lifetime, the underlying code has been used in support of many different applications. Below is a comprehensive overview of those that have worked on this code, as well as the projects their changes were in support of. 

The ODHS Medicine Tracker is based on an old open source project named "Homebase". [Homebase](https://a.link.will.go.here/) was originally developed for the Ronald McDonald Houses in Maine and Rhode Island by Oliver Radwan, Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker.

Modifications to the original Homebase code were made by the Fall 2022 semester's group of students. That team consisted of Jeremy Buechler, Rebecca Daniel, Luke Gentry, Christopher Herriott, Ryan Persinger, and Jennifer Wells.

A major overhaul to the existing system took place during the Spring 2023 semester, throwing out and restructuring many of the existing database tables. Very little original Homebase code remains. This team consisted of Lauren Knight, Zack Burnley, Matt Nguyen, Rishi Shankar, Alip Yalikun, and Tamra Arant. Every page and feature of the app was changed by this team.

The Gwyneth's Gifts VMS code was modified in the Fall of 2023, revamping the code into the present ODHS Medicine Tracker code. Many of the existing database tables were reused, and many other tables were added. Some portions of the software's functionality were reused from the Gwyneth's Gifts VMS code. Other functions were created to fill the needs of the ODHS Medicine Tracker. The team that made these modifications and changes consisted of Garrett Moore, Artis Hart, Riley Tugeau, Julia Barnes, Ryan Warren, and Collin Rugless.

The ODHS Medicine Tracker code was modified in the Fall of 2024, changing the code to the present Step VA Volunteer Management System code. Many existing database tables were reused or renamed, and some others were added. Some files and portions of the software's functionality were reused from ODHS Medicine Tracker, while other functions were created to fill the needs of Step VA Volunteer Management. The team which made changes and new addtions consisted of Ava Donley, Thomas Held, Madison McCarty, Noah Stafford, Jayden Wynes, Gary Young, and Imaad Qureshi.

In the Spring of 2025, the Step VA system underwent additional changes as requested by the customer. Additional functionality for managing events was requested, as well as the added family management capabilities, and several other system management features. The student team that made these changes was comprised of Brandon Howar, Emma Brennan, James Collier, Tamarcus Daniel, Sullivan Smith, Maxwell Von Vort. (We made Dark Mode. You're welcome.)

## User Types
There are four types of users (also referred to as 'roles') within Step VA.
* Admins
* Volunteers
* Family Leader
* Participant (Family Member)

Admins can create and edit events, view and approve sign-ups, view sign-ups and volunteer hours, add training videos for users to view, generate reports, as well as create/delete other admin accounts.

Volunteers can create and edit their profile, sign up for events, check-in and check-out of events, view their hours, and generate reports based on the number of hours they have worked. Volunteer accounts can be archived by the Admin if the account is no longer in use.

Family Leaders can manage their family by signing both themseleves and Family Members up for events, editing their account details, deleting/adding Family Members to their account. The Family Members tied to the Family Leader account are of type "participant" listed below.

Participants can sign themselves up for events, manage attendance for upcoming schedules events, and update their account details (including photo release forms). 

There is also a root admin account with username 'vmsroot'. The default password for this account is 'vmsroot'. This account has hardcoded Admin privileges. It is crucial that this account be given a strong password and that the password be easily remembered, as it cannot easily be reset. This account should be used for system administration purposes only.

## Features
Below is an in-depth list of features that were implemented within the system this Spring 2025 semester.
* User Registration
  * Volunteer Registration
  * Participant/Family Registration
  * Admin Create/Remove Admin Accounts
* Dashboard
  * Role Specific Dashboard
  * Admin Page Search Bar
  * Dark Mode
* Volunteer Management
  * View Own Volunteer Hours (print-friendly)
  * Change Own hours
  * Modify Own profile
* Family Management
  * View Family Members
  * Sign Family Members Up For Event
  * Remove Family Member Sign Up From Event
  * Remove Family Member From System
  * Add Family Member To System
* Events and Event Management
  * Restrict Volunteer Numbers For Events
  * Set An Event As Recurring
  * Check-in and Check-out For Event
* Reports (print-friendly)
  * Volunteer Hour Reports
  * Participant Accomodations Report
  * Event Attendance Report
* Video Management
  * View Training Videos
  * Upload Videos
  * Delete Videos
  * Edit Videos
* Account Management
  * Photo Release Updates
  * Additional Details Updates


## Design Documentation
Several types of diagrams describing the design of the Step VA web management system. These diagrams include sequence diagrams, use case diagrams, and data flow diagrams all available by contacting Dr. Polack.

## "localhost" Installation
Below are the steps required to run the project on your local machine for development and/or testing purposes.
1. [Download and install XAMPP](https://www.apachefriends.org/download.html)
2. Open a terminal/command prompt and change directory (cd) to your XAMPP install's htdocs folder
  * For Mac, the htdocs path is `/Applications/XAMPP/xamppfiles/htdocs`
  * For Ubuntu, the htdocs path is `/opt/lampp/htdocs/`
  * For Windows, the htdocs path is `C:\xampp\htdocs`
3. Using the version control system Git (which may need to be installed [https://git-scm.com/book/en/v2/Getting-Started-Installing-Git]), clone the Step VA repo by running the following command:
  * $ git clone git@github.com:ebrenna2/StepVA2025.git
4. Start the XAMPP MySQL server and Apache server
5. Open the PHPMyAdmin console by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
6. Create a new database named `stepvadb`. With the database created, navigate to it by clicking on it in the lefthand pane
7. Import the `stepvadb.sql` file located in `stepvarepo/sql` into this new database
8. Create a new user by navigating to `Privileges -> New -> Add user account`
9. Enter the following credentials for the new user:
  * Name: `stepvadb`
  * Hostname: `Local`
  * Password: `stepvadb`
  * Leave everything else untouched
10. Navigate to [http://localhost/StepVA2025/login.php](http://localhost//) 
11. Log into the root user account using the username `vmsroot` with password `vmsroot`

Installation is now complete.

## Platform
Dr. Polack chose SiteGrounds as the platform on which to host the project. Below are some guides on how to manage the live project. The Step VA web system is available at [https://jenniferp130.sg-host.com/login.php].

### SiteGround Dashboard
Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground
Follow these steps to transfer your localhost version of the Step VA code to Siteground. For a video tutorial on how to complete these steps, contact Dr. Polack.
1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:
  - Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the 'Create Database' button. Database name is auto-generated and can be changed if you like.
  - User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the 'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be changed  if you like.
  - Password - Created when user is created. Password is auto generated and can be changed if you like.
4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and import the "vms.sql" file from your software files.
  - Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the database.
6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3. 

### Clearing the SiteGround cache
#### Chrome
1. Open Chrome and click on the three-dot menu icon in the top-right corner.
2. Navigate to **More Tools** > **Clear Browsing Data**.
3. In the pop-up window:
   - Select the **Time Range** (e.g., "Last 24 hours" or "All time").
   - Check the box for **Cached images and files**.
4. Click **Clear Data**.

#### Safari
1. Open Safari and click on **Safari** > **Settings** in the menu bar at the top of the screen.
2. Select **Preferences** > **Privacy**.
3. Click the **Manage Website Data** button.
4. In the pop-up window, click **Remove All**, then confirm by selecting **Remove Now**.

### Safari Part #2
In the case that the above does not clear the cache, try the following:
1. Open Safari and click on **Safari** > **Settings** in the menu bar at the top of the screen.
2. Select **Advances** in the menu bar at the top of the screen.
3. Click the **Show features for web developers** check box option at the bottom of the pop-up window.
4. Close the pop-up window and select **Develop** > **Empty Caches** in the menu bar at the top of the screen

Clearing your cache will help ensure that you're seeing the latest updates to the application. If you continue experiencing issues, consider reaching out for further support.

## External Libraries and APIs
The FPDF library was utilized to facilitate the generation of various printable report types within the system, enabling efficient creation of PDF-based reports. FPDF library details are available at "https://www.fpdf.org". The only other outside library utilized by the Step VA is the jQuery library. The version of jQuery used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and the hiding/showing of certain page elements. Additionally, the Font Awesome library was used for some of the icon pictures. This library is linked in the headers of some files "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css".

## Potential Improvements
Below is a list of improvements that could be made to the system in subsequent semesters.
* Further changes made to User Interface (UI)
* Cleanup of any unecessary files left over.
* Page search bar for Volunteer, Participant, and Family Leader account types.
* Enforcement of participant event capacity.
* YouTube link to embed converter capability.
* Generate and send emails to volunteers/participants. 

## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements
Thank you to Dr. Polack and Step VA for the chance to work on this exciting project. A lot of time, tears, and celsiuses went into making it!