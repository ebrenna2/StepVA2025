<?php
/*
 * Copyright 2013 by Jerrick Hoang, Ivy Xing, Sam Roberts, James Cook, 
 * Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/**
 * @version March 1, 2012
 * @author Oliver Radwan and Allen Tucker
 */

/* 
 * Created for Gwyneth's Gift in 2022 using original Homebase code as a guide
 */


include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Event.php');

/*
 * add an event to dbEvents table: if already there, return false
 */

function add_event($event) {
    if (!$event instanceof Event)
        die("Error: add_event type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $event->getID() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbevents VALUES("' .
                $event->getID() . '","' .
                $event->getDate() . '","' .
                $event->getStartTime() . "," .
                #$event->get_venue() . '","' .
                $event->getName() . '","' . 
                $event->getDescription() . '","' .
                $event->getCapacity() . "," .
                $event->getCompleted() . "," .
                $event->getEventType() . "," .
                $event->getRestrictedSignup() . "," .
                #$event->getID() .            
                '");');							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

/*function fetch_event_name_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select name from dbevents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}*/

function request_event_signup($eventID, $account_name, $role, $notes) {
    $connection = connect();
    $query1 = "SELECT id FROM dbevents WHERE name LIKE '$eventID'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    $value = $row['id'];
   
    $query2 = "SELECT userID FROM dbeventpersons WHERE eventID LIKE '$value' AND userID LIKE '$account_name'";
    $result2 = mysqli_query($connection, $query2);

    $query3 = "SELECT username FROM dbpendingsignups WHERE eventname LIKE '$value' AND username LIKE '$account_name'";
    $result3 = mysqli_query($connection, $query3);

    $row2 = null;
    $row2 = mysqli_fetch_assoc($result2);
    $row3 = null;
    $row3 = mysqli_fetch_assoc($result3);

    if(!is_null($row2) || !is_null($row3)) {
            $value2 = $row2['userID'];
            $value3 = $row3['username'];
            if($value2 == $account_name || $value3 == $account_name){
                return null;
        } 
    } else {       
            $query = "insert into dbpendingsignups (username, eventname, role, notes) values ('$account_name', '$value', '$role', '$notes')";
            $result = mysqli_query($connection, $query);
            mysqli_commit($connection);
            return $value;
        }
    return $value;
}
function sign_up_for_event($eventID, $account_name, $role, $notes) {
    $connection = connect();
    $query1 = "SELECT id FROM dbevents WHERE name LIKE '$eventID'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    $value = $row['id'];
   
    $query2 = "SELECT userID FROM dbeventpersons WHERE eventID LIKE '$value' AND userID LIKE '$account_name'";
    $result2 = mysqli_query($connection, $query2);

    $row2 = null;
    $row2 = mysqli_fetch_assoc($result2);

    if(!is_null($row2)) {
            $value2 = $row2['userID'];
            if($value2 == $account_name){
                return null;
        } 
    } else {       
            $query = "insert into dbeventpersons (eventID, userID, position, notes) values ('$value', '$account_name', '$role', '$notes')";
            $result = mysqli_query($connection, $query);
            mysqli_commit($connection);
            return $value;
        }
    return $value;
}

/* @@@ Thomas's work! */
/*
 * Check if a user is is signed up for an event. Return true or false.
 */
function check_if_signed_up($eventID, $userID) {
    // look up event+user pair
    $connection = connect();
    $query1 = "SELECT * FROM dbeventpersons WHERE eventID = '$eventID' and userID = '$userID'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    mysqli_close($connection);

    // check if a row was returned
    if ($row) {
        return True;
    } else {
        return False;
    }
}

/* @@@ Madison's work! */
/*
 * Check for all users signed up for an event. 
 */
function fetch_event_signups($eventID) {
    $connection = connect();
    $query = "SELECT userID, position, notes FROM dbeventpersons WHERE eventID = '$eventID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    mysqli_close($connection);
    return $signups;
}

function get_signed_up_events_by($userID) {
    $connection = connect();
    $query = "
        SELECT e.id, e.name
        FROM dbevents e
        JOIN dbeventpersons ep ON e.id = ep.eventID
        WHERE ep.userID = '$userID'
        ORDER BY e.date ASC
    ";
    $result = mysqli_query($connection, $query);

    $events = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }
    mysqli_close($connection);
    return $events; 
}


function fetch_pending($eventID) {
    $connection = connect();
    $query = "SELECT username, role, notes FROM dbpendingsignups WHERE eventname = '$eventID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }
    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    mysqli_close($connection);
    return $signups;
}

function fetch_all_pending() {
    $connection = connect();
    $query = "SELECT eventname, username, role, notes FROM dbpendingsignups";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }
    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    mysqli_close($connection);
    return $signups;
}

function all_pending_names() {
    $connection = connect();
    $query = "SELECT eventname FROM dbpendingsignups";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    $event_names = [];
    $length = sizeof($signups);
    for ($x = 0; $x < $length; $x++) {
        $val = (int)$signups[$x]['eventname'];
        $query2 = "SELECT name FROM dbevents WHERE id = $val";
        $result2 = mysqli_query($connection, $query2);
        while ($row = mysqli_fetch_assoc($result2)) {
            $event_names[] = $row;
        }
    }

    mysqli_close($connection);
    return $event_names;
}

function all_pending_ids() {
    $connection = connect();
    $query = "SELECT eventname FROM dbpendingsignups";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    mysqli_close($connection);
    return $signups;
}

function remove_user_from_event($event_id, $user_id) {    
    $query = "DELETE FROM dbeventpersons WHERE eventID LIKE '$event_id' AND userID LIKE '$user_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function remove_user_from_pending_event($event_id, $user_id) {    
    $query = "DELETE FROM dbpendingsignups WHERE eventname = '$event_id' AND username = '$user_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

/* @@@ Thomas's work! */
/*
 * Returns true if the given event is archived.
 */
function is_archived($id) {
    // look-up 'completed' in the event's DB entry
    $connection = connect();
    $query1 = "SELECT completed FROM dbevents WHERE id = '$id'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    mysqli_close($connection);

    if ($row == NULL) return False; // no match for that event ID

    if ($row['completed'] == 'yes') {
        // event is archived
        return True;
    } else {
        return False;
    }
}

/*
 * Mark an event as archived in the DB by setting the 'completed' column to 'yes'.
 */
function archive_event($id) {
    $con=connect();
    $query = "UPDATE dbevents SET completed = 'yes' WHERE id = '" .$id. "'";
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}

/*
 * Mark an event as not archived in the DB by setting the 'completed' column to 'no'.
 */
function unarchive_event($id) {
    $con=connect();
    $query = "UPDATE dbevents SET completed = 'no' WHERE id = '" .$id. "'";
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return $result;
}

/* end of Thomas's work*/

/**/

/*
 * remove an event from dbEvents table.  If already there, return false
 */

function remove_event($id) {
    $con=connect();
    $query = 'SELECT * FROM dbevents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbevents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}


/*
 * @return an Event from dbEvents table matching a particular id.
 * if not in table, return false
 */

function retrieve_event($id) {
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $theEvent = make_an_event($result_row);
//    mysqli_close($con);
    return $theEvent;
}

function retrieve_event2($id) {
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
//    var_dump($result_row);
    return $result_row;
}

// not in use, may be useful for future iterations in changing how events are edited (i.e. change the remove and create new event process)
function update_event_date($id, $new_event_date) {
	$con=connect();
	$query = 'UPDATE dbevents SET event_date = "' . $new_event_date . '" WHERE id = "' . $id . '"';
	$result = mysqli_query($con,$query);
	mysqli_close($con);
	return $result;
}

function make_an_event($result_row) {
	/*
	 ($en, $v, $sd, $description, $ev))
	 */
    $theEvent = new Event(
                    $result_row['id'],
                    $result_row['name'],                   
                    date: $result_row['date'],
                    startTime: $result_row['startTime'],
                    endTime: $result_row['endTime'],
                    description: $result_row['description'],
                    capacity: $result_row['capacity'],
                    completed: $result_row['completed'],
                    event_type: $result_row['event_type'],
                    restricted_signup: $result_row['restricted_signup']
                ); 
    return $theEvent;
}

function get_all_events() {
    $con=connect();
    $query = "SELECT * FROM dbevents" . 
            " ORDER BY completed";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }
 
 function get_all_events_sorted_by_date_not_archived() {
    $con=connect();
    $query = "SELECT * FROM dbevents" .
            " WHERE completed = 'no'" .
            " ORDER BY date ASC";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }

 function get_all_events_sorted_by_date_and_archived() {
    $con=connect();
    $query = "SELECT * FROM dbevents" .
            " WHERE completed = 'yes'" .
            " ORDER BY date ASC";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }

// retrieve only those events that match the criteria given in the arguments
function getonlythose_dbEvents($name, $day, $venue) {
   $con=connect();
   $query = "SELECT * FROM dbevents WHERE event_name LIKE '%" . $name . "%'" .
           " AND event_name LIKE '%" . $name . "%'" .
           " AND venue = '" . $venue . "'" . 
           " ORDER BY event_name";
   $result = mysqli_query($con,$query);
   $theEvents = array();
   while ($result_row = mysqli_fetch_assoc($result)) {
       $theEvent = make_an_event($result_row);
       $theEvents[] = $theEvent;
   }
   mysqli_close($con);
   return $theEvents;
}

function fetch_events_in_date_range($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbevents
              where date >= '$start_date' and date <= '$end_date'
              order by startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $key = $result_row['date'];
        if (isset($events[$key])) {
            $events[$key] []= hsc($result_row);
        } else {
            $events[$key] = array(hsc($result_row));
        }
    }
    mysqli_close($connection);
    return $events;
}

function fetch_events_on_date($date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $date);
    $query = "select * from dbevents
              where date = '$date' order by startTime asc";
    $results = mysqli_query($connection, $query);
    if (!$results) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = [];
    foreach ($results as $row) {
        $events []= hsc($row);
    }
    mysqli_close($connection);
    return $events;
}

function fetch_event_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select * from dbevents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}

function create_event($event) {
    $connection = connect();
    $name = $event["name"];
    //$abbrevName = $event["abbrev-name"];
    $date = $event["date"];
    $startTime = $event["start-time"];    
    $endTime = $event["end-time"];
    $description = $event["description"];
    if (isset($event["capacity"])) {
        $capacity = $event["capacity"];
    } else {
        $capacity = 999;
    }
    if (isset($event["location"])) {
        $location = $event["location"];
    } else {
        $location = "";
    }
    //$completed = $event["completed"];
    //$event_type = $event["event_type"];
    $restricted_signup = $event["role"];
    if ($restricted_signup == "r") {
        $restricted = 1;
    } else {
        $restricted = 0;
    }
    $description = $event["description"];
    //$location = $event["location"];
    //$services = $event["service"];

    //$animal = $event["animal"];
    $completed = "no";
    $query = "
        insert into dbevents (name, date, startTime, endTime, restricted_signup, description, capacity, completed, location, event_type)
        values ('$name', '$date', '$startTime', '$endTime', $restricted, '$description', $capacity, '$completed', '$location', 'New')
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    //add_services_to_event($id, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

function set_recurring($event) {
    $recurring = $event["recurring"];
    $recurrence = isset($event["recurrence"]) ? $event["recurrence"] : "Daily";  // Default to "Daily"
    $startDate = $event["date"];
    $endDate = $event["end-date"];

    // Check if startDate and endDate are valid before using strtotime
    if (empty($startDate) || empty($endDate)) {
        echo 'Invalid start or end date';
        die();  // Exit if dates are invalid
    }

    // Convert startDate and endDate to timestamps
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    // Check if strtotime failed to convert the dates
    if ($startDate === false || $endDate === false) {
        echo 'Invalid start or end date';
        die();  // Exit if date conversion failed
    }

    // Create the first event on the start date
    $event["date"] = date("Y-m-d", $startDate);  // Set the first event date
    $event_id = create_event($event);

    // If recurrence is not enabled, just return the event ID
    if ($recurring == 'n') {
        return $event_id;
    }

    // Calculate next occurrences based on the frequency
    $currentDate = $startDate;
    $occurrenceCount = 0;

    // Only continue if the current date is within the range of the end date
    while ($currentDate <= $endDate && $occurrenceCount < 100) {  // Limit to 100 occurrences to prevent infinite loops
        switch ($recurrence) {
            case "Daily":
                // Add 1 day for daily recurrence
                $currentDate = strtotime("+1 day", $currentDate);
                break;
            case "Weekly":
                // Add 7 days for weekly recurrence
                $currentDate = strtotime("+1 week", $currentDate);  // Add 7 days
                break;
            case "Biweekly":
                // Add 2 weeks for biweekly recurrence
                $currentDate = strtotime("+2 weeks", $currentDate);
                break;
            case "Monthly":
                // Add 1 month for monthly recurrence
                $currentDate = strtotime("+1 month", $currentDate);
                break;
        }

        // Ensure we're not creating an event on the same day more than once
        if ($currentDate > $endDate) break;

        // Increment occurrence count
        $occurrenceCount++;

        // Create the recurring event with the new date
        $event["date"] = date("Y-m-d", $currentDate);

        // You may want to ensure that the event creation logic isn't accidentally looping
        $event_id = create_event($event);
    }

    // Return the ID of the first created event
    return $event_id;
}


function add_services_to_event($eventID, $serviceIDs) {
    $connection = connect();
    foreach($serviceIDs as $serviceID) {
        $query = "insert into dbeventsservices (eventID, serviceID) values ('$eventID', '$serviceID')";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            return null;
        }
        $id = mysqli_insert_id($connection);
    }
    mysqli_commit($connection);
    return $id;
}

function update_event($eventID, $eventDetails) {
    $connection = connect();
    $id = $eventDetails["id"];
    $name = $eventDetails["name"];
    #$abbrevName = $eventDetails["abbrev-name"];
    $date = $eventDetails["date"];
    $startTime = $eventDetails["start-time"];
    #$restricted = $eventDetails["restricted"];
    $endTime = $eventDetails["end-time"];
    $description = $eventDetails["description"];
    $capacity = $eventDetails["capacity"];
    #$completed = $eventDetails["completed"];
    #$event_type = $eventDetails["event_type"];
    #$restricted_signup = $eventDetails["restricted_signup"];
    $location = $eventDetails["location"];
    //$services = $eventDetails["service"];
    
    #$completed = $eventDetails["completed"];
    #$query = "
       # update dbEvents set name='$name', abbrevName='$abbrevName', date='$date', startTime='$startTime', restricted='$restricted', description='$description', locationID='$location', completed='$completed'
       # where id='$eventID'
    #";
   # $query = "
    #    update dbevents set id='$id', name='$name', date='$date', startTime='$startTime', endTime='$endTime', description='$description', capacity='$capacity', completed='$completed', event_type='$event_type', restricted_signup='$restricted_signup'
    #    where id='$eventID'
    #";
    $query = "
        update dbevents set id='$id', name='$name', date='$date', startTime='$startTime', endTime='$endTime', description='$description', location='$location', capacity=$capacity
        where id='$eventID'
    ";
    $result = mysqli_query($connection, $query);
    // update_services_for_event($eventID, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}

function update_event2($eventID, $eventDetails) {
    $connection = connect();
    $id = $eventDetails["id"];
    $name = $eventDetails["name"];
    #$abbrevName = $eventDetails["abbrevName"];
    $date = $eventDetails["date"];
    $startTime = $eventDetails["startTime"];
    $endTime = $eventDetails["endTime"];
    $description = $eventDetails["description"];
    $capacity = $eventDetails["capacity"];
    $completed = $eventDetails["completed"];
    $event_type = $eventDetails["event_type"];
    $restricted_signup = $eventDetails["restricted_signup"];
    #$query = "
    #    update dbEvents set name='$name', abbrevName='$abbrevName', date='$date', startTime='$startTime', endTime='$endTime', description='$description', locationID='$location', capacity='$capacity', animalId='$animalID', completed='$completed'
    #    where id='$eventID'
    #";
    $query = "
        update dbevents set id='$id', name='$name', date='$date', startTime='$startTime', endTime='$endTime', description='$description', capacity='$capacity', completed='$completed', event_type='$event_type', restricted_signup='$restricted_signup'
        where id='$eventID'
    ";
    $result = mysqli_query($connection, $query);
    //update_services_for_event($eventID, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}

function update_services_for_event($eventID, $serviceIDs) {
    $connection = connect();

    $current_services = get_services($eventID);
    foreach($current_services as $curr_serv) {
        $curr_servIDs[] = $curr_serv['id'];
    }

    // add new services
    foreach($serviceIDs as $serviceID) {
        if (!in_array($serviceID, $curr_servIDs)) {
            $query = "insert into dbeventsservices (eventID, serviceID) values ('$eventID', '$serviceID')";
            $result = mysqli_query($connection, $query);
        }
    }
    // remove old services
    foreach($curr_servIDs as $curr_serv) {
        if (!in_array($curr_serv, $serviceIDs)) {
            $query = "delete from dbeventsservices where serviceID='$curr_serv'";
            $result = mysqli_query($connection, $query);
        }
    }
    mysqli_commit($connection);
    return;
}

function find_event($nameLike) {
    $connection = connect();
    $query = "
        select * from dbevents
        where name like '%$nameLike%'
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $all;
}

function fetch_events_in_date_range_as_array($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbevents
              where date >= '$start_date' and date <= '$end_date'
              order by date, startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function fetch_all_events() {
    $connection = connect();
    $query = "select * from dbevents
              order by date, startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function get_animal($id) {
    $connection = connect();
    $query = "select * from dbanimals
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $animal = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $animal;
}

function get_description($id) {
    $connection = connect();
    $query = "select description from dbevents
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $description = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $description;
}
  

function get_location($id) {
    $connection = connect();
    $query = "select * from dblocations
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $location = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $location;
}

function get_services($eventID) {
    $connection = connect();
    $query = "select * from dbservices AS serv JOIN dbeventsservices AS es ON es.serviceID = serv.id
              where es.eventID='$eventID'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $services = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $services;
}

function get_media($id, $type) {
    $connection = connect();
    $query = "select * from dbeventmedia
              where eventID='$id' and type='$type'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $media = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $media;
}

function get_event_training_media($id) {
    return get_media($id, 'training');
}

function get_post_event_media($id) {
    return get_media($id, 'post');
}

function attach_media($eventID, $type, $url, $format, $description) {
    $query = "insert into dbeventmedia
              (eventID, type, url, format, description)
              values ('$eventID', '$type', '$url', '$format', '$description')";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if (!$result) {
        return false;
    }
    return true;
}

function attach_event_training_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'training', $url, $format, $description);
}

function attach_post_event_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'post', $url, $format, $description);
}

function detach_media($mediaID) {
    $query = "delete from dbeventmedia where id='$mediaID'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if ($result) {
        return true;
    }
    return false;
}

function delete_event($id) {
    $query = "delete from dbevents where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function cancel_event($event_id, $account_name) {
    $query = "DELETE from dbeventpersons where userID LIKE '$account_name' AND eventID LIKE $event_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function approve_signup($event_id, $account_name, $position, $notes) {
    $query = "DELETE from dbpendingsignups where username = '$account_name' AND eventname = $event_id";
    $connection = connect();
    //echo "username " . $account_name . " eventname " . $event_id;
    $result = mysqli_query($connection, $query);
    $result = boolval($result);

    //echo "hello" . $account_name;

    $query2 = "insert into dbeventpersons (eventID, userID, position, notes) values ('$event_id', '$account_name',  '$position', '$notes')";
    $result2 = mysqli_query($connection, $query2);
    //$result2 = boolval($result2);
    //mysqli_close($connection);
    mysqli_commit($connection);
    return $result2;
}

function reject_signup($event_id, $account_name, $position, $notes) {
    $query = "DELETE from dbpendingsignups where username = '$account_name' AND eventname = '$event_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    
    return $result;
}

function complete_event($id) {
    $event = retrieve_event2($id);
    $animal = get_animal($event["animalID"])[0];
    $date = $event["date"];
    $event["completed"] = "yes";

    $services = get_services($event["id"]);
    $length = count($services);

    for ($i = 0; $i < $length; $i++) { 
        $check = $services[$i]['name'];
        $dur = $services[$i]['duration_years'];
        if(stripos($check, "spay") !== false || stripos($check, "neuter") !== false){
            $animal["spay_neuter_done"] = "yes";
            $animal["spay_neuter_date"] = $date;
        }
        else if(stripos($check, "rabie") !== false){
            $animal["rabies_given_date"] = $date;
            $animal["rabies_due_date"] = date('Y-m-d', strtotime($date."+".$dur." years"));
        }
        else if(stripos($check, "heartworm") !== false){
            $animal["heartworm_given_date"] = $date;
            $animal["heartworm_due_date"] = date('Y-m-d', strtotime($date."+".$dur." years"));
        }
        else if(stripos($check, "distemper 1") !== false){
            $animal["distemper1_given_date"] = $date;
            $animal["distemper1_due_date"] = date('Y-m-d', strtotime($date."+".$dur." years"));
        }
        else if(stripos($check, "distemper 2") !== false){
            $animal["distemper2_given_date"] = $date;
            $animal["distemper2_due_date"] = date('Y-m-d', strtotime($date."+".$dur." years"));
        }
        else if(stripos($check, "distemper 3") !== false){
            $animal["distemper3_given_date"] = $date;
            $animal["distemper3_due_date"] = date('Y-m-d', strtotime($date."+".$dur." years"));
        }
        else if(stripos($check, "microchip") !== false){
            $animal["microchip_done"] = "yes";
        }
        else{
            $animal["notes"] = $animal["notes"]." | ".$check.": ".$date;
        }
    
    }
//    var_dump($event);
    $result = update_animal2($animal);
    $result = update_event2($event["id"], $event);
    return $result;
}

function update_animal2($animal) {
    $connection = connect();
    $id = $animal['id'];
	$odhsid = $animal["odhs_id"];
    $name = $animal["name"];
	$breed = $animal["breed"];
    $age = $animal["age"];
    $gender = $animal["gender"];
    $notes = $animal["notes"];
    $spay_neuter_done = $animal["spay_neuter_done"];
	$spay_neuter_date = $animal["spay_neuter_date"];
    if (empty($animal["spay_neuter_date"])) {
        $spay_neuter_date = '0000-00-00';
    }
    $rabies_given_date = $animal["rabies_given_date"];
    if (empty($animal["rabies_given_date"])) {
        $rabies_given_date = '0000-00-00';
    }
	$rabies_due_date = $animal["rabies_due_date"];
    if (empty($animal["rabies_due_date"])) {
        $rabies_due_date = '0000-00-00';
    }
    $heartworm_given_date = $animal["heartworm_given_date"];
    if (empty($animal["heartworm_given_date"])) {
        $heartworm_given_date = '0000-00-00';
    }
	$heartworm_due_date = $animal["heartworm_due_date"];
    if (empty($animal["heartworm_due_date"])) {
        $heartworm_due_date = '0000-00-00';
    }
	$distemper1_given_date = $animal["distemper1_given_date"];
    if (empty($animal["distemper1_given_date"])) {
        $distemper1_given_date = '0000-00-00';
    }
	$distemper1_due_date = $animal["distemper1_due_date"];
    if (empty($animal["distemper1_due_date"])) {
        $distemper1_due_date = '0000-00-00';
    }
	$distemper2_given_date = $animal["distemper2_given_date"];
    if (empty($animal["distemper2_given_date"])) {
        $distemper2_given_date = '0000-00-00';
    }
	$distemper2_due_date = $animal["distemper2_due_date"];
    if (empty($animal["distemper2_due_date"])) {
        $distemper2_due_date = '0000-00-00';
    }
	$distemper3_given_date = $animal["distemper3_given_date"];
    if (empty($animal["distemper3_given_date"])) {
        $distemper3_given_date = '0000-00-00';
    }
	$distemper3_due_date = $animal["distemper3_due_date"];
    if (empty($animal["distemper3_due_date"])) {
        $distemper3_due_date = '0000-00-00';
    }
	$microchip_done = $animal["microchip_done"];
    $query = "
        UPDATE dbanimals set odhs_id='$odhsid', name='$name', breed='$breed', age='$age', gender='$gender', notes='$notes', spay_neuter_done='$spay_neuter_done', spay_neuter_date='$spay_neuter_date', rabies_given_date='$rabies_given_date', rabies_due_date='$rabies_due_date', heartworm_given_date='$heartworm_given_date', heartworm_due_date='$heartworm_due_date', distemper1_given_date='$distemper1_given_date', distemper1_due_date='$distemper1_due_date', distemper2_given_date='$distemper2_given_date', distemper2_due_date='$distemper2_due_date', distemper3_given_date='$distemper3_given_date', distemper3_due_date='$distemper3_due_date', microchip_done='$microchip_done'
        where id='$id'
        ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}


function check_if_pending_sign_up($eventID, $userID) {
    $connection = connect();
    
    if (!$connection) {
        return false;
    }
    
    $stmt = $connection->prepare(
        "SELECT COUNT(*) FROM dbpendingsignups WHERE eventname = ? AND username = ?"
    );
    $stmt->bind_param("ss", $eventID, $userID);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    $stmt->close();
    $connection->close();
    
    return $count > 0;
}

// In database/dbEvents.php
function get_event_name_by_id($eventId) {
    try {
        $con = connect(); // Using your existing connect() function
        $query = "SELECT name FROM dbevents WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $eventId); // Using bind_param instead of execute with array
        $stmt->execute();
        $stmt->bind_result($name); // Bind the result to a variable
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $name ? $name : '';
    } catch (Exception $e) {
        return '';
    }
}

//Needed for git stuff

function sign_up_for_event_by_id($eventId, $account_name, $role, $notes) {
    try {
        $con = connect();
        $query = "INSERT INTO dbeventpersons (eventID, userID, position, notes) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in sign_up_for_event_by_id: " . $con->error);
            $con->close();
            return false;
        }
        // Use "isss" for int eventID, string userID, string position, string notes
        $eventId = (int)$eventId;
        $stmt->bind_param("isss", $eventId, $account_name, $role, $notes);
        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed in sign_up_for_event_by_id: " . $stmt->error . " | Params: eventId=$eventId, userID=$account_name, position=$role, notes=$notes");
        }
        $insertId = $success ? $con->insert_id : false;
        $stmt->close();
        $con->close();
        return $insertId;
    } catch (Exception $e) {
        return false;
    }
}

function request_event_signup_by_id($eventId, $account_name, $role, $notes) {
    try {
        $con = connect();
        
        // Get the event name from the event ID
        $eventName = get_event_name_by_id($eventId);
        if (empty($eventName)) {
            error_log("No event name found for eventId=$eventId in request_event_signup_by_id");
            $con->close();
            return false;
        }

        // Check for existing pending signup
        $checkQuery = "SELECT COUNT(*) FROM dbpendingsignups WHERE username = ? AND eventname = ?";
        $checkStmt = $con->prepare($checkQuery);
        $checkStmt->bind_param("ss", $account_name, $eventId);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
        
        if ($count > 0) {
            error_log("User $account_name already has pending signup for event $eventName (ID: $eventId)");
            $con->close();
            return false;
        }

        $query = "INSERT INTO dbpendingsignups (username, eventname, role, notes) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in request_event_signup_by_id: " . $con->error);
            $con->close();
            return false;
        }
        $stmt->bind_param("ssss", $account_name, $eventId, $role, $notes); // All strings: "ssss"
        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed in request_event_signup_by_id: " . $stmt->error . " | Params: username=$account_name, eventname=$eventName, role=$role, notes=$notes");
        }
        $insertId = $success ? $con->insert_id : false;
        $stmt->close();
        $con->close();
        return $insertId;
    } catch (Exception $e) {
        error_log("Exception in request_event_signup_by_id: " . $e->getMessage());
        return false;
    }
}

