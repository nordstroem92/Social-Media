<?php
	include_once 'dbconnection.php';	
	session_start();	
	$userId = $_SESSION['other_user_id'];
	$eventDates = array();

	$sql = "SELECT * FROM going WHERE user_id='$userId';";
	$result = mysqli_query($conn, $sql);
	$resultNumRows = mysqli_num_rows($result);
	if ($resultNumRows == 0) {
		echo "You don't have any upcoming events";
	} else {
		while ($row = mysqli_fetch_assoc($result)) {
			$eventId = $row['event_id'];
			$sqlEventsDB = "SELECT * FROM events WHERE event_id='$eventId';";
			$resultEventsDB = mysqli_query($conn, $sqlEventsDB);
			$resultEventsDBNumRows = mysqli_num_rows($resultEventsDB);   
			if ($resultEventsDBNumRows == 0) {
				echo "Event doesn't exist no more";
			} else {
				while($row = mysqli_fetch_assoc($resultEventsDB)){
	
					$eventDate = $row['event_date'];
					array_push($eventDates, $eventDate);
		
					echo $eventDate;
					
				}
			}
		}
	}
?>