<?php
	include_once 'dbconnection.php';

	$eventNewCount = $_POST['eventNewCount'];

	$sql_events = "SELECT * FROM events LIMIT $eventNewCount";
	$result = mysqli_query($conn, $sql_events);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<span class='event-link'><a href='event.php?event_name=".$row['event_name']."&event_id=".$row['event_id']."'><p><b style='display: inline;'>".$row['event_name']."</b> @ ".$row['event_location']." | ".$row['event_date']." | ".$row['event_time']."</br>".$row['event_description']."</p></a></span>";
		} 
	} else {
		echo "No events yet";
	}
?>