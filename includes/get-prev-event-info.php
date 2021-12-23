<?php
include_once "dbconnection.php";
session_start();
$event_id = $_SESSION['event_id'];

$sql = "SELECT * FROM events WHERE event_id='$event_id'";
$result = mysqli_query($conn, $sql);

$event_info = array();

if ($row = mysqli_fetch_assoc($result)) {
	$event_location = $row['event_location'];
	$event_date = $row['event_date'];
	$event_time = $row['event_time'];
	$event_description = $row['event_description'];
	$event_category = $row['event_category'];

	array_push($event_info, $event_location);
	array_push($event_info, $event_date);
	array_push($event_info, $event_time);
	array_push($event_info, $event_description);
	array_push($event_info, $event_category);
		
} 
echo json_encode($event_info);
