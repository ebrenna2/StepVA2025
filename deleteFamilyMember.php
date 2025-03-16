<?php
// deletefamilymember.php

if (isset($_GET['childID'])) {
    $id = $_GET['childID'];
    $con = connect();
    
    $query = "DELETE FROM dbfamilymember WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

header("Location: familymanagementportal.php?message=deleted");

exit();
?>