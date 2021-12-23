<?php
	include_once 'dbconnection.php';	
	session_start();	
	$date = $_POST['date'];
	$userId = $_SESSION['u_id'];

	$eventNames = array();

	$sqlGetEventsByDate = "SELECT * FROM events WHERE event_date='$date';"; //events for current date
	$resultEvents = mysqli_query($conn, $sqlGetEventsByDate);
	while ($row = mysqli_fetch_assoc($resultEvents)) {
		$eventId = $row['event_id'];
		$sqlGoing = "SELECT * FROM going WHERE event_id='$eventId' AND user_id='$userId';"; //is user going to events
		$resultGoing = mysqli_query($conn, $sqlGoing); 
		while ($row = mysqli_fetch_assoc($resultGoing)) {
			$goingEventId = $row['event_id'];
			$sqlGoingEventId = "SELECT * FROM events WHERE event_id='$goingEventId';"; //fetch all event data
			$resultGoingEventId = mysqli_query($conn, $sqlGoingEventId); 
			while ($row = mysqli_fetch_assoc($resultGoingEventId)){
				$eventName = $row['event_name'];
				array_push($eventNames, $eventName);
			}
		}

	}
	echo json_encode($eventNames);
?>