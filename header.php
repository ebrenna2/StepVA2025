<!-- This looks really, really great!  -Thomas -->

<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHP-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<header>
    <div class="container mt-3 position-relative" style="max-width: 400px;">
        <input id="pageSearch" class="form-control" type="text" placeholder="Search pages..." autocomplete="off">
        <div id="suggestions" class="list-group position-absolute w-100 z-3" style="top: 100%; max-height: 200px; overflow-y: auto; display: none;"></div>
    </div>
    <script>
    const pages = [
        { name: "Home", url: "index.php" },
        { name: "Calendar", url: "calendar.php" },
        { name: "Inbox", url: "inbox.php" },
        { name: "Add Event", url: "addevent.php" },
        { name: "Cancel Event", url: "cancelEvent.php"},
        { name: "Delete Admin", url: "deleteAdmin.php"},
        { name: "Delete Event", url: "deleteEvent.php"},
        { name: "Delete Family Member", url: "deleteFamilyMember.php"},
        { name: "View All Events", url: "viewAllEvents.php" },
        { name: "Edit Profile", url: "editProfile.php" },
        { name: "Change Password", url: "changePassword.php" },
        { name: "Volunteer Report", url: "volunteerReport.php" },
        { name: "Upload Resources", url: "resources.php" },
        { name: "Logout", url: "logout.php" }
    ];
    const input = document.getElementById("pageSearch");
    const suggestions = document.getElementById("suggestions");
    input.addEventListener("input", function () {
        const query = this.value.toLowerCase();
        suggestions.innerHTML = "";
        if (query === "") {
            suggestions.style.display = "none";
            return;
        }
        const filtered = pages.filter(page => page.name.toLowerCase().includes(query));
        if (filtered.length === 0) {
            suggestions.style.display = "none";
            return
        }
        filtered.forEach(page => {
            const item = document.createElement("a");
            item.classList.add("list-group-item", "list-group-item-action");
            item.textContent = page.name;
            item.href = page.url;
            suggestions.appendChild(item);
        });
        suggestions.style.display = "block";});
    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = "none";
        }
        });
    </script>

    <?PHP
    //Log-in security
    //If they aren't logged in, display our log-in form.
    $showing_login = false;
    if (!isset($_SESSION['logged_in'])) {
        echo '
        <nav>
            <span id="nav-top">
                <span class="logo">
                    <img src="images/stepvalogo.png">
                    <span id="vms-logo"> Step VA Management </span>
                </span>
                <img id="menu-toggle" src="images/menu.png">
            </span>
            <ul>
                <li><a href="login.php">Log in</a></li>
            </ul>
        </nav>';
        //      <li><a href="register.php">Register</a></li>     was at line 35

    } else if ($_SESSION['logged_in']) {

        /*         * Set our permission array.
         * anything a guest can do, a volunteer and manager can also do
         * anything a volunteer can do, a manager can do.
         *
         * If a page is not specified in the permission array, anyone logged into the system
         * can view it. If someone logged into the system attempts to access a page above their
         * permission level, they will be sent back to the home page.
         */
        //pages guests are allowed to view
        // LOWERCASE
        $permission_array['index.php'] = 0;
        $permission_array['about.php'] = 0;
        $permission_array['apply.php'] = 0;
        $permission_array['logout.php'] = 0;
        $permission_array['register.php'] = 0;
        $permission_array['findanimal.php'] = 0;
        $permission_array['login.php'] = 0;
        //pages volunteers can view
        $permission_array['help.php'] = 1;
        $permission_array['dashboard.php'] = 1;
        $permission_array['calendar.php'] = 1;
        $permission_array['eventsearch.php'] = 1;
        $permission_array['changepassword.php'] = 1;
        $permission_array['editprofile.php'] = 1;
        $permission_array['inbox.php'] = 1;
        $permission_array['date.php'] = 1;
        $permission_array['event.php'] = 1;
        $permission_array['viewprofile.php'] = 1;
        $permission_array['viewnotification.php'] = 1;
        $permission_array['volunteerreport.php'] = 1;
        $permission_array['viewmyupcomingevents.php'] = 1;
        //pages only managers can view
        $permission_array['viewallevents.php'] = 0;
        $permission_array['reportsdash.php'] = 2; // Or whatever permission level is appropriate
        $permission_array['personsearch.php'] = 2;
        $permission_array['personedit.php'] = 0; // changed to 0 so that applicants can apply
        $permission_array['viewschedule.php'] = 2;
        $permission_array['volunteerreportwithsearch.php'] = 2;
        $permission_array['addweek.php'] = 2;
        $permission_array['log.php'] = 2;
        $permission_array['reports.php'] = 2;
        $permission_array['editvideos.php'] = 2;
        $permission_array['participantreport.php'] = 2;
        $permission_array['eventedit.php'] = 2;
        $permission_array['modifyuserrole.php'] = 2;
        $permission_array['addevent.php'] = 2;
        $permission_array['editevent.php'] = 2;
        $permission_array['roster.php'] = 2;
        $permission_array['report.php'] = 2;
        $permission_array['reportspage.php'] = 2;
        $permission_array['resetpassword.php'] = 2;
        $permission_array['addappointment.php'] = 2;
       // $permission_array['addanimal.php'] = 2;
        $permission_array['addservice.php'] = 2;
        $permission_array['addlocation.php'] = 2;
        $permission_array['viewservice.php'] = 2;
        $permission_array['viewlocation.php'] = 2;
        $permission_array['viewarchived.php'] = 2;
        // $permission_array['animal.php'] = 2;
        // $permission_array['editanimal.php'] = 2;
        $permission_array['eventsuccess.php'] = 2;
        $permission_array['viewsignuplist.php'] = 2;
        $permission_array['vieweventsignups.php'] = 2;
        $permission_array['viewalleventsignups.php'] = 2;
        //$permission_array['resources.php'] = 2;
        $permission_array['adminregistervolunteer.php'] = 2;
        $permission_array['checkinoutvolunteer.php'] = 1;

        $permission_array['edithours.php'] = 2;
        $permission_array['eventlist.php'] = 1;
        $permission_array['eventsignup.php'] = 1;
        $permission_array['eventfailure.php'] = 1;
        $permission_array['signupsuccess.php'] = 1;
        $permission_array['edittimes.php'] = 1;
        $permission_array['adminviewingevents.php'] = 2;
        $permission_array['signuppending.php'] = 1;
        $permission_array['requestfailed.php'] = 1;
        $permission_array['settimes.php'] = 1;
        $permission_array['eventattendanceform.php'] = 2;
        $permission_array['eventfailurebaddeparturetime.php'] = 1;

        $permission_array['videomanagerportal.php'] = 2;
        $permission_array['videouploadmanager.php'] = 2;
        $permission_array['videodeletionmanager.php'] = 2;
        $permission_array['videodeletionhelper.php'] = 2;
        $permission_array['deleteadmin.php'] = 2;
        //For family leaders to view
        $permission_array['familymanagementportal.php'] = 1;
        $permission_array['editchildprofile.php'] = 1;
        
        $permission_array['registeradmin.php'] = 2;
        $permission_array['viewvideos.php'] = 1;
        // LOWERCASE
        $permission_array['vmsdash.php'] = 2;
        $permission_array['volunteerportal.php'] = 1;
        $permission_array['participantportal.php'] = 0;
        $permission_array['adminportal.php'] = 2;
        $permission_array['eventsettings.php'] = 2;
        $permission_array['admindash.php'] = 2;
        $permission_array['viewall.php'] = 2;
        $permission_array['registervolunteerbar.php'] = 2;
        // NEW UI


        //Check if they're at a valid page for their access level.
        $current_page = strtolower(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
        $current_page = substr($current_page, strpos($current_page,"/"));
        
        if($permission_array[$current_page]>$_SESSION['access_level']){
            //in this case, the user doesn't have permission to view this page.
            //we redirect them to the index page.
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
            //note: if javascript is disabled for a user's browser, it would still show the page.
            //so we die().
            die();
        }
        //This line gives us the path to the html pages in question, useful if the server isn't installed @ root.
        $path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']), strpos(strrev($_SERVER['SCRIPT_NAME']), '/')));
		$venues = array("portland"=>"RMH Portland");
        
        //they're logged in and session variables are set.
        echo('<nav>');
        echo('<span id="nav-top"><span class="logo"><a class="navbar-brand" href="' . $path . 'index.php"><img src="images/stepvalogo.png"></a>');
        echo('<a class="navbar-brand" id="vms-logo"> Step VA System </a></span><img id="menu-toggle" src="images/menu.png"></span>');
        echo('<ul>');
        //echo " <br><b>"."Gwyneth's Gift Homebase"."</b>|"; //changed: 'Homebase' to 'Gwyneth's Gift Homebase'

        echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'index.php">Home</a></li>');
        //echo('<span class="nav-divider">|</span>');

        echo('<li class="nav-item dropdown">');
        echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Events</a>');
        echo('<div class="dropdown-menu" aria-labelledby="navbarDropdown">');
        echo('<a class="dropdown-item" href="' . $path . 'calendar.php">Calendar</a>');
        echo('<a class="dropdown-item" href="' . $path . 'inbox.php">Notifications</a>');
        if ($_SESSION['access_level'] >= 2) {
            echo('<a class="dropdown-item" href="' . $path . 'addevent.php">Add</a>');
            echo('<a class="dropdown-item" href="' . $path . 'viewAllEvents.php">View All</a>');
            echo('<a class="dropdown-item" href="' . $path . 'viewAllEventSignUps.php">Pending Sign-Ups</a>');
        }
        echo('</div>');
        echo('</li>');

        //echo('<span class="nav-divider">|</span>');
        if ($_SESSION['access_level'] >= 2) {
        echo('<li class="nav-item dropdown">');
        echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Volunteers</a>');
        echo('<div class="dropdown-menu">');
        echo('<a class="dropdown-item" href="' . $path . 'personSearch.php">Search</a>
            <a class="dropdown-item" href="registerVolunteerBar.php">Add</a>');
        echo('</div>');
        echo('</li>');
        }


        //echo('<span class="nav-divider">|</span>');
        /*
        echo('<li class="nav-item dropdown">');
        echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Animals</a>');
        echo('<div class="dropdown-menu">');
        echo('<a class="dropdown-item" href="' . $path . 'findAnimal.php">Search</a>');
        echo('<a class="dropdown-item" href="' . $path . 'addAnimal.php">Add</a>');
        echo('<a class="dropdown-item" href="' . $path . 'report.php">Reports</a>');
        echo('<a class="dropdown-item" href="' . $path . 'viewArchived.php">Archived Animals</a>');

        echo('</div>');
        echo('</li>');
        */

         //echo('<span class="nav-divider">|</span>');
         if ($_SESSION['access_level'] <= 2) {
         echo('<li class="nav-item dropdown">');
         echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Profile</a>');
         echo('<div class="dropdown-menu">');
         echo('<a class="dropdown-item" href="' . $path . 'viewProfile.php">View</a>');
         echo('<a class="dropdown-item" href="' . $path . 'editProfile.php">Edit</a>');
         }
 
         echo('</div>');
         echo('</li>'); 

        //echo('<span class="nav-divider">|</span>');
        echo('<li class="nav-item dropdown">');
        echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Others</a>');
        echo('<div class="dropdown-menu">');
        //if ($_SESSION['access_level'] >= 2) {
        //echo('<a class="dropdown-item" href="' . $path . 'addService.php">Add Service</a>');
        //echo('<a class="dropdown-item" href="' . $path . 'addLocation.php">Add Location</a>');
        //}
        if ($_SESSION['access_level'] <= 1) {
            echo('<a class="dropdown-item" href="' . $path . 'volunteerReport.php">View Hours</a>');
        }
        echo('<a class="dropdown-item" href="' . $path . 'changePassword.php">Change Password</a>');
        echo('<a class="dropdown-item" href="' . $path . 'resources.php">Upload Resources</a>');
        echo('</div>');
        echo('</li>');

        //if ($_SESSION['access_level'] >= 1) {
            
            // echo('<li class="nav-item"><a class="nav-link active" aria-current="page" href="' . $path . 'about.php">About</a></li>');
            // echo('<li class="nav-item"><a class="nav-link active" aria-current="page" href="' . $path . 'help.php?helpPage=' . $current_page . '" target="_BLANK">Help</a></li>');
            //echo('<li class="sub-item"><a class="nav-link active" aria-current="page" href="' . $path . 'eventSearch.php">Search</a></li>');
            //echo('<button type="button" class="btn btn-link"><a href="' . $path . 'index.php" class="link-primary">home</a></button>');
            //echo(' | <button type="button" class="btn btn-link"><a href="' . $path . 'about.php">about</a></button>');
            //echo(' | <button type="button" class="btn btn-link"><a href="' . $path . 'help.php?helpPage=' . $current_page . '" target="_BLANK">help</a></button>');
            //echo(' | calendars: <a href="' . $path . 'calendar.php?venue=bangor'.''.'">Bangor, </a>');
            //echo(' | <button type="button" class="btn btn-link"><a href="' . $path . 'calendar.php?venue=portland'.''.'">calendar</a></button>'); //added before '<a': |, changed: 'Portland' to 'calendar'
        //}
        //if ($_SESSION['access_level'] >= 2) {
            //echo('<br>master schedules: <a href="' . $path . 'viewSchedule.php?venue=portland'."".'">Portland, </a>');
            //echo('<a href="' . $path . 'viewSchedule.php?venue=bangor'."".'">Bangor</a>');
            
            // TODO: update animal search to direct to animal search page and animal add to direct to animal add page
            
        //}
        //echo('<span class="nav-divider">|</span>');
        echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'logout.php">Log out</a></li>');
        echo '</ul></nav>';

        echo '
            <script type="text/javascript" src="darkmode.js" defer></script>

            <button id="theme-switch">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-400q48 0 88 26t59 71l10 23h25q42 0 70 29.5t28 70.5q0 42-29 71t-71 29H240q-66 0-113-47T80-240q0-67 47-113.5T240-400Zm210-440q-18 99 11 193.5T561-481q71 71 165.5 100T920-370q-26 142-135 234.5T533-40q32-26 49.5-62.5T600-180q0-68-42.5-117.5T449-357q-32-57-87.5-90T240-480q-32 0-62.5 8T120-448q2-145 94.5-255T450-840Z"/></svg>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/></svg>

            </button>

        ';
        
    }
    ?>
</header>