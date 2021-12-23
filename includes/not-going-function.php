<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$eventId = $_SESSION['event_id'];
$event = $_SESSION['event_name'];

$sqlDelete = "DELETE FROM going WHERE user_id='$thisUserId' AND event_id='$eventId';";
mysqli_query($conn, $sqlDelete);

header("Location: ../event.php?event_not_going=success&event_name=".$event."&event_id=".$eventId."");
exit();