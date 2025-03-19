<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>StepVA System | Inbox</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Inbox</h1>
        <main class="general">
            <?php 
                require_once('database/dbMessages.php');
                //$messages = array(); //get_user_messages($userID);
                $messages = get_user_read_messages($userID);
                $newMessages = get_user_unread_messages($userID);
                mark_all_as_read($userID);
                if (count($newMessages) > 0): ?>
                    <div class="table-wrapper">
                        <h2>New Notifications</h2>
                        <table class="general">
                            <thead>
                                <tr>
                                    <th style="width:1px">From</th>
                                    <th>Title</th>
                                    <th style="width:1px">Received</th>
                                    <th style="width:1px">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="standout">
                                <?php 
                                    require_once('database/dbPersons.php');
                                    require_once('include/output.php');
                                    $id_to_name_hash = [];
                                    foreach ($newMessages as $message) {
                                        $sender = $message['senderID'];
                                        if (isset($id_to_name_hash[$sender])) {
                                            $sender = $id_to_name_hash[$sender];
                                        } else {
                                            $lookup = get_name_from_id($sender);
                                            $id_to_name_hash[$sender] = $lookup;
                                            $sender = $lookup;
                                        }
                                        $messageID = $message['id'];
                                        $title = $message['title'];
                                        $timePacked = $message['time'];
                                        $pieces = explode('-', $timePacked);
                                        $year = $pieces[0];
                                        $month = $pieces[1];
                                        $day = $pieces[2];
                                        $time = time24hto12h($pieces[3]);
                                        $class = 'message';
                                        if (!$message['wasRead']) {
                                            $class .= ' unread';
                                        }
                                        if ($message['prioritylevel'] == 1) {
                                            $class .= ' prio1';
                                        }
                                        if ($message['prioritylevel'] == 2) {
                                            $class .= ' prio2';
                                        }
                                        if ($message['prioritylevel'] == 3) {
                                            $class .= ' prio3';
                                        }
                                        echo "
                                            <tr class='$class' data-message-id='$messageID'>
                                                <td>$sender</td>
                                                <td>$title</td>
                                                <td>$month/$day/$year $time</td>
                                                <td>
                                                    <button class='delete-button' data-message-id='$messageID'>Delete Notification</button>
                                                </td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
                <?php
                if (count($messages) > 0): ?>
                <div class="table-wrapper">
                    <h2>Past Notifications</h2>
                    <table class="general">
                        <thead>
                            <tr>
                                <th style="width:1px">From</th>
                                <th>Title</th>
                                <th style="width:1px">Received</th>
                                <th style="width:1px">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                require_once('database/dbPersons.php');
                                require_once('include/output.php');
                                $id_to_name_hash = [];
                                foreach ($messages as $message) {
                                    $sender = $message['senderID'];
                                    if (isset($id_to_name_hash[$sender])) {
                                        $sender = $id_to_name_hash[$sender];
                                    } else {
                                        $lookup = get_name_from_id($sender);
                                        $id_to_name_hash[$sender] = $lookup;
                                        $sender = $lookup;
                                    }
                                    $messageID = $message['id'];
                                    $title = $message['title'];
                                    $timePacked = $message['time'];
                                    $pieces = explode('-', $timePacked);
                                    $year = $pieces[0];
                                    $month = $pieces[1];
                                    $day = $pieces[2];
                                    $time = time24hto12h($pieces[3]);
                                    $class = 'message';
                                    if (!$message['wasRead']) {
                                        $class .= ' unread';
                                    }
                                    if ($message['prioritylevel'] == 1) {
                                        $class .= ' prio1';
                                    }
                                    if ($message['prioritylevel'] == 2) {
                                        $class .= ' prio2';
                                    }
                                    if ($message['prioritylevel'] == 3) {
                                        $class .= ' prio3';
                                    }
                                    echo "
                                        <tr class='$class' data-message-id='$messageID'>
                                            <td>$sender</td>
                                            <td>$title</td>
                                            <td>$month/$day/$year $time</td>
                                            <td>
                                                <button class='delete-button' data-message-id='$messageID'>Delete Notification</button>
                                            </td>
                                        </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-messages standout">You currently have no unread messages.</p>
            <?php endif ?>
            <!-- <button>Compose New Message</button> -->
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    </body>
</html>