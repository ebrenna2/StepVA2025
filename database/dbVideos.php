<?php
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Video.php');

function add_video($video) {
    $con = connect();

    $stmt = $con->prepare("SELECT * FROM dbvideos WHERE id = ?");
    $id = $video->get_id();
    $stmt->bind_param("i", $id); // assuming id is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result == null || $result->num_rows == 0) {
        $stmt = $con->prepare("INSERT INTO dbvideos (url, title, synopsis, type) VALUES (?, ?, ?, ?)");
        $url = $video->get_url();
        $title = $video->get_title();
        $synopsis = $video->get_synopsis();
        $type = $video->get_type();
        $stmt->bind_param("ssss", $url, $title, $synopsis, $type);
        $stmt->execute();
        $stmt->close();
        mysqli_close($con);
        return true;
    }

    $stmt->close();
    mysqli_close($con);
    return false;
}

function remove_video($id) {
    $con = connect();

    $stmt = $con->prepare("SELECT * FROM dbvideos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result == null || $result->num_rows == 0) {
        $stmt->close();
        mysqli_close($con);
        return false;
    }

    $stmt->close();

    $stmt = $con->prepare("DELETE FROM dbvideos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    mysqli_close($con);
    return true;
}

function retrieve_all_videos() {
    $con = connect();
    if (!$con) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $query = "SELECT id, title, url, synopsis, type FROM dbvideos ORDER BY title ASC";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $videos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $videos[] = $row;
    }

    mysqli_close($con);
    return $videos;
}

function update_video($id, $url, $title, $synopsis, $type){
    $con = connect();

    $stmt = $con->prepare("UPDATE dbvideos SET url = ?, title = ?, synopsis = ?, type = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $url, $title, $synopsis, $type, $id);
    $result = $stmt->execute();

    $stmt->close();
    mysqli_close($con);
    return $result;
}