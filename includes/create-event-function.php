<?php
include_once "dbconnection.php";
session_start();

if (isset($_POST['event-submit'])) {
	$user_id = $_SESSION['u_id'];
	$event_name = mysqli_real_escape_string($conn, $_POST['event-name']);
	$event_location = mysqli_real_escape_string($conn, $_POST['event-location']);
	$event_date = mysqli_real_escape_string($conn, $_POST['event-date']);
	$event_time = mysqli_real_escape_string($conn, $_POST['event-time']);
	$event_description = mysqli_real_escape_string($conn, $_POST['event-description']);
	$event_category = mysqli_real_escape_string($conn, $_POST['event-category']);


	if(empty($event_name)|| empty($event_location) || empty($event_date) || empty($event_time)){
		header("Location: ../frontpage.php?eventinfo=empty");
		exit();
	} else {
		if(!preg_match("/^[a-zA-Z æøå\/]*$/", $event_name) || !preg_match("/^[a-zA-Z0-9 æøå\/]*$/", $event_location) || !preg_match("/^[a-zA-Z æøå\/]*$/", $event_description)) {
			header("Location: ../frontpage.php?eventinfo=invalid");
			exit();
		} else {
			//echo $event_name." ".$event_location." ".$event_date." ".$event_time ." ".$event_description." ".$_SESSION['u_id'];

			$sql_insert_into_events = "INSERT INTO events (user_id, event_name, event_location, event_date, event_time, event_description, event_img_status, event_category) VALUES ('$user_id', '$event_name','$event_location', '$event_date', '$event_time','$event_description', '0', '$event_category');";
			mysqli_query($conn, $sql_insert_into_events);
			header("Location: ../frontpage.php?event=created");
			exit();		
		}
	}
} else {
	echo "You are not supposed to be here";
}
?>