<?php
include_once "dbconnection.php";
session_start();

if (isset($_POST['event-submit'])) {	
	$user_id = $_SESSION['u_id'];
	$event_id = $_SESSION['event_id'];
	$event_name = $_SESSION['event_name'];

	$event_location = mysqli_real_escape_string($conn, $_POST['event-location']);
	$event_date = mysqli_real_escape_string($conn, $_POST['event-date']);
	$event_time = mysqli_real_escape_string($conn, $_POST['event-time']);
	$event_description = mysqli_real_escape_string($conn, $_POST['event-description']);
	$event_category = mysqli_real_escape_string($conn, $_POST['event-category']);

	$sql = "UPDATE events SET event_location='$event_location', event_date='$event_date', event_time='$event_time', event_description='$event_description', event_category='$event_category' WHERE event_id='$event_id';";
	$result = mysqli_query($conn, $sql);

	header("Location: ../event.php?event_name=".$event_name."&event_id=".$event_id."");

} else {
	echo "You are not supposed to be here";
}
?>