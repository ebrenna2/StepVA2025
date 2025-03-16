<?php
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Video.php');

function add_video($video) {
    $con=connect();
    $query = "SELECT * FROM dbvideos WHERE id = '" . $video->get_id() . "'";
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con, 'INSERT INTO dbvideos VALUES ("' .
        $video->get_id() . '","' .
        $video->get_url() . '","' .
        $video->get_title() . '","' .
        $video->get_synopsis() . '","' .
        $video->get_type() . '");'
    );
    mysqli_close($con);
    return true;
}
mysqli_close($con);
return false;
}

function remove_video($id) {
    $con=connect();
    $query = 'SELECT * FROM dbvideos WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbvideos WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}
