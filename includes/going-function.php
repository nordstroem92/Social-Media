<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$eventId = $_SESSION['event_id'];
$event = $_SESSION['event_name'];

$sql = "INSERT INTO going (user_id, event_id) VALUES ($thisUserId, $eventId);";
mysqli_query($conn, $sql);

header("Location: ../event.php?event_going=success&event_name=".$event."&event_id=".$eventId."");
exit();