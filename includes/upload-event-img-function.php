<?php
session_start();
include_once 'dbconnection.php';

$event = mysqli_real_escape_string($conn, $_GET['event_name']);

if (isset($_POST['event-img-submit'])) {
	$file = $_FILES['file'];

	$fileName = $_FILES['file']['name'];
	$fileTmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileError = $_FILES['file']['error'];
	$fileType = $_FILES['file']['type']; 

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('jpg', 'jpeg', 'png');

	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 500000) {
				$eventId = "";
				$sqlGetEventID = "SELECT * FROM events WHERE event_name='$event';";
				$resultGetEvent = mysqli_query($conn, $sqlGetEventID);
				while ($row = mysqli_fetch_assoc($resultGetEvent)){
					$eventId = $row['event_id'];
				}
				$fileNameNew = "event".$eventId.".".$fileActualExt;
				$fileDestination = '../uploads/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
				$sql = "UPDATE events SET event_img_status=1 WHERE event_name='$event';";
				mysqli_query($conn, $sql);
				header("Location: ../event.php?upload=success&event_name=".$event."&event_id=".$eventId."");
	
			} else {
				echo "file is too big";
			}
		} else {
			echo "there was an error uploading your file";
		}
	} else {
		echo "you cannot upload this filetype";
	}
}