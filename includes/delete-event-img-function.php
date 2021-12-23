<?php
session_start();
include_once "dbconnection.php";

if (isset($_POST['delete-submit'])) { 
	$eventName = $_SESSION['event_name'];

	$sqlThisEvent = "SELECT * FROM events WHERE event_name='$eventName';";
	$result = mysqli_query($conn, $sqlThisEvent);
	while ($row = mysqli_fetch_assoc($result)) {
		$eventId = $row['event_id']; 

		$filename = "../uploads/event".$eventId."*";
		$fileinfo = glob($filename);
		$fileext = explode(".", $fileinfo[0]);
		$fileactualext = $fileext[3];

		$file = "../uploads/event".$eventId.".".$fileactualext;
		if (!unlink($file)) {
			echo "file was not deleted";
		} else {
			echo "file was deleted";
		}
		$sql = "UPDATE events SET event_img_status=0 WHERE event_id='$eventId';";
		mysqli_query($conn, $sql);
		header("Location: ../event.php?delete=success&event_name=".$eventName."&event_id=".$eventId."");
	}
}