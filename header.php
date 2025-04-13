<?php
/*
 * Copyright 2013 by Allen Tucker.
 * This program is part of RMHP-Homebase, which is free software. It comes with
 * absolutely no warranty. You can redistribute and/or modify it under the terms
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/> for more information).
 */
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        #pageSearch {
            width: 200px;
            font-size: 14px;
            padding: 5px;
        }
        #suggestions {
            position: absolute;     /* Key for dropdown positioning */
            top: 100%;              /* Places it right below the input */
            left: 0;
            width: 100%;            /* Make it match the input width */
            background-color: white;
            border: 1px solid #ccc;
            border-top: none;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* optional visual polish */
        }
        .nav-search {
            margin-left: auto;
            display: flex;
            align-items: center;
            position: relative;
        }
    </style>
</head>

<header>
    <?php
    // Log-in security
    $showing_login = false;
    if (!isset($_SESSION['logged_in'])) {
        echo '
        <nav class="navbar navbar-expand">
            <span id="nav-top" class="d-flex align-items-center">
                <span class="logo">
                    <img src="images/stepvalogo.png" alt="Step VA Logo">
                    <span id="vms-logo">Step VA Management</span>
                </span>
                <img id="menu-toggle" src="images/menu.png" alt="Menu">
            </span>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
            </ul>
        </nav>';
    } else if ($_SESSION['logged_in']) {
        // Permission array (unchanged)
        $permission_array['index.php'] = 0;
        $permission_array['about.php'] = 0;
        $permission_array['apply.php'] = 0;
        $permission_array['logout.php'] = 0;
        $permission_array['register.php'] = 0;
        $permission_array['findanimal.php'] = 0;
        $permission_array['login.php'] = 0;
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
        $permission_array['viewallevents.php'] = 0;
        $permission_array['reportsdash.php'] = 2;
        $permission_array['personsearch.php'] = 2;
        $permission_array['personedit.php'] = 0;
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
        $permission_array['addservice.php'] = 2;
        $permission_array['addlocation.php'] = 2;
        $permission_array['viewservice.php'] = 2;
        $permission_array['viewlocation.php'] = 2;
        $permission_array['viewarchived.php'] = 2;
        $permission_array['eventsuccess.php'] = 2;
        $permission_array['viewsignuplist.php'] = 2;
        $permission_array['vieweventsignups.php'] = 2;
        $permission_array['viewalleventsignups.php'] = 2;
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
        $permission_array['addfamilymember.php'] = 1;
        $permission_array['videomanagerportal.php'] = 2;
        $permission_array['videouploadmanager.php'] = 2;
        $permission_array['videodeletionmanager.php'] = 2;
        $permission_array['videodeletionhelper.php'] = 2;
        $permission_array['deleteadmin.php'] = 2;
        $permission_array['familymanagementportal.php'] = 1;
        $permission_array['editchildprofile.php'] = 1;
        $permission_array['registeradmin.php'] = 2;
        $permission_array['viewvideos.php'] = 1;
        $permission_array['vmsdash.php'] = 2;
        $permission_array['volunteerportal.php'] = 1;
        $permission_array['participantportal.php'] = 0;
        $permission_array['adminportal.php'] = 2;
        $permission_array['eventsettings.php'] = 2;
        $permission_array['admindash.php'] = 2;
        $permission_array['viewall.php'] = 2;
        $permission_array['registervolunteerbar.php'] = 2;

        // Check permissions
        $current_page = strtolower(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
        if (isset($permission_array[$current_page]) && $permission_array[$current_page] > $_SESSION['access_level']) {
            header("Location: index.php");
            exit;
        }
        $path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']), strpos(strrev($_SERVER['SCRIPT_NAME']), '/')));
        $venues = array("portland" => "RMH Portland");

        // Define searchable pages for admin accounts
        $admin_pages_base = [
            ['name' => 'Admin Dashboard', 'url' => 'adminDash.php'],
            ['name' => 'Create Event', 'url' => 'addEvent.php'],
            ['name' => 'View Pending Sign-Ups', 'url' => 'viewAllEventSignUps.php'],
            ['name' => 'Find Volunteer', 'url' => 'personSearch.php'],
            ['name' => 'Register Volunteer', 'url' => 'registerVolunteerBar.php'],
            ['name' => 'View Events', 'url' => 'adminViewingEvents.php'],
            ['name' => 'View & Change Event Hours', 'url' => 'editHours.php'],
            ['name' => 'Reports Page', 'url' => 'reportsDash.php'],
            ['name' => 'View Profile', 'url' => 'viewProfile.php'],
            ['name' => 'Edit Profile', 'url' => 'editProfile.php'],
            ['name' => 'Edit Videos', 'url' => 'editVideos.php'],
            ['name' => 'Add Video', 'url' => 'videoUploadManager.php'],
            ['name' => 'Delete Videos', 'url' => 'videoDeletionManager.php'],
            ['name' => 'View Videos', 'url' => 'viewVideos.php'],
            ['name' => 'My Upcoming Events', 'url' => 'viewMyUpcomingEvents.php'],
            ['name' => 'View Volunteering Report', 'url' => 'volunteerReport.php'],
            ['name' => 'Manage Family', 'url' => 'familyManagementPortal.php'],
            ['name' => 'Notifications', 'url' => 'inbox.php'],
            ['name' => 'View Calendar', 'url' => 'calendar.php'],
            ['name' => 'Sign-Up for Event', 'url' => 'viewAllEvents.php'],
            ['name' => 'Change Password', 'url' => 'changePassword.php'],
            ['name' => 'Log out', 'url' => 'logout.php']
        ];

        $vmsroot_pages = [
            ['name' => 'Add Admin', 'url' => 'registerAdmin.php'],
            ['name' => 'Delete Admin', 'url' => 'deleteadmin.php']
        ];

        // Select pages for search bar (admins only)
        $user_type = isset($_SESSION['type']) ? strtolower(trim($_SESSION['type'])) : 'participant';
        $valid_types = ['admin', 'volunteer', 'participant'];
        if (!in_array($user_type, $valid_types)) {
            $user_type = 'participant';
        }

        // Debugging: Log user type (remove in production)
        error_log("User type: $user_type, Access level: " . ($_SESSION['access_level'] ?? 'not set') . ", vmsroot: " . (isset($_SESSION['_id']) && $_SESSION['_id'] === 'vmsroot' ? 'yes' : 'no'));
        echo "<!-- Debug: User type = $user_type, Access level = " . ($_SESSION['access_level'] ?? 'not set') . " -->";

        $searchable_pages = [];
        if ($user_type === 'admin') {
            $searchable_pages = $admin_pages_base;
            if (isset($_SESSION['_id']) && $_SESSION['_id'] === 'vmsroot') {
                $searchable_pages = array_merge($admin_pages_base, $vmsroot_pages);
            }
        }
        // Volunteers and participants get no search bar, so $searchable_pages stays empty

        // Output search bar JavaScript (only for admins)
        if ($user_type === 'admin' && !empty($searchable_pages)) {
            echo '<script>';
            echo 'const pages = ' . json_encode($searchable_pages) . ';';
            echo '
                document.addEventListener("DOMContentLoaded", function () {
                    const input = document.getElementById("pageSearch");
                    const suggestions = document.getElementById("suggestions");
                    if (input && suggestions) {
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
                                return;
                            }
                            filtered.forEach(page => {
                                const item = document.createElement("a");
                                item.classList.add("list-group-item", "list-group-item-action");
                                item.textContent = page.name;
                                item.href = page.url;
                                suggestions.appendChild(item);
                            });
                            suggestions.style.display = "block";
                        });
                        document.addEventListener("click", function (e) {
                            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                                suggestions.style.display = "none";
                            }
                        });
                    }
                });
            </script>';
        }

        // Logged-in navbar
        echo('<nav class="navbar navbar-expand">');
        echo('<span id="nav-top" class="d-flex align-items-center"><span class="logo"><a class="navbar-brand" href="' . $path . 'index.php"><img src="images/stepvalogo.png" alt="Step VA Logo"></a>');
        echo('<a class="navbar-brand" id="vms-logo">Step VA System</a></span><img id="menu-toggle" src="images/menu.png" alt="Menu"></span>');
        echo('<ul class="navbar-nav">');
        echo('<li class="nav-item"><a class="nav-link active" aria-current="page" href="' . $path . 'index.php">Home</a></li>');

        // Events Dropdown
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
        echo('</div></li>');

        // Volunteers Dropdown (Managers only)
        if ($_SESSION['access_level'] >= 2) {
            echo('<li class="nav-item dropdown">');
            echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Volunteers</a>');
            echo('<div class="dropdown-menu">');
            echo('<a class="dropdown-item" href="' . $path . 'personSearch.php">Search</a>');
            echo('<a class="dropdown-item" href="registerVolunteerBar.php">Add</a>');
            echo('</div></li>');
        }

        // My Profile Dropdown
        if ($_SESSION['access_level'] <= 2) {
            echo('<li class="nav-item dropdown">');
            echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Profile</a>');
            echo('<div class="dropdown-menu">');
            echo('<a class="dropdown-item" href="' . $path . 'viewProfile.php">View</a>');
            echo('<a class="dropdown-item" href="' . $path . 'editProfile.php">Edit</a>');
            echo('</div></li>');
        }

        // Others Dropdown
        echo('<li class="nav-item dropdown">');
        echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Others</a>');
        echo('<div class="dropdown-menu">');
        if ($_SESSION['access_level'] <= 1) {
            echo('<a class="dropdown-item" href="' . $path . 'volunteerReport.php">View Hours</a>');
        }
        echo('<a class="dropdown-item" href="' . $path . 'changePassword.php">Change Password</a>');
        echo('</div></li>');

        // Search Bar (only for admins)
        if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 3) {
            echo('<li class="nav-item nav-search">');
            echo('<input id="pageSearch" class="form-control" type="text" placeholder="Search pages..." autocomplete="off">');
            echo('<div id="suggestions" class="list-group position-absolute w-100 z-3"></div>');
            echo('</li>');
        }

        // Logout
        echo('<li class="nav-item"><a class="nav-link active" aria-current="page" href="' . $path . 'logout.php">Log out</a></li>');
        echo('</ul></nav>');

        // Theme Switcher
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