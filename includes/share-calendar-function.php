<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$otherUserId = $_SESSION['other_user_id'];

$sql = "INSERT INTO sharedcalendar (user_id, other_user_id) VALUES ($thisUserId, $otherUserId);";
mysqli_query($conn, $sql);

header("Location: ../user-profile.php?user_id=".$otherUserId."");
exit();