<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$otherUserId = $_SESSION['other_user_id'];

$sqlDelete = "DELETE FROM sharedCalendar WHERE user_id='$thisUserId' AND other_user_id='$otherUserId';";
mysqli_query($conn, $sqlDelete);

header("Location: ../user-profile.php?user_id=".$otherUserId."&unshared=success");
exit();
