<?php
require_once('database/dbinfo.php');

if (isset($_GET['childID'])) {
    $id = $_GET['childID'];
    $con=connect();
    
    //Remove from family member table
    $query = "DELETE FROM dbfamilymember WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    //Remove from dbeventpersons
    $query2 = "DELETE FROM dbeventpersons WHERE userID = ?";
    $stmt2 = mysqli_prepare($con, $query2);
    mysqli_stmt_bind_param($stmt2, "s", $id);
    mysqli_stmt_execute($stmt2);

    mysqli_stmt_close($stmt2);

    //Remove from dbpendingsignups
    $query3 = "DELETE FROM dbpendingsignups WHERE username = ?";
    $stmt3 = mysqli_prepare($con, $query3);
    mysqli_stmt_bind_param($stmt3, "s", $id);
    mysqli_stmt_execute($stmt3);

    mysqli_stmt_close($stmt3);

    //Remove from dbpersons
    $query4 = "DELETE FROM dbpersons WHERE id = ?";
    $stmt4 = mysqli_prepare($con, $query4);
    mysqli_stmt_bind_param($stmt4, "s", $id);
    mysqli_stmt_execute($stmt4);

    mysqli_stmt_close($stmt4);

    mysqli_close($con);
}

header("Location: familyManagementPortal.php?message=deleted");

exit();
?>