<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$eventId = $_SESSION['event_id'];
$event = $_SESSION['event_name'];

$commentId = $_POST['commentId'];

$sql = "DELETE FROM comments WHERE relation_id='$commentId';";
mysqli_query($conn, $sql);

header("Location: ../event.php?comment=deleted&event_name=".$event."&event_id=".$eventId."");
exit();