<?php
session_start();
include_once 'dbconnection.php';

$thisUserId = $_SESSION['u_id'];
$eventId = $_SESSION['event_id'];
$event = $_SESSION['event_name'];

if (isset($_POST['comment-submit'])) {
	$comment = mysqli_real_escape_string($conn, $_POST['comment']);

	if(empty($comment)){
		header("Location: ../event.php?comment=empty&event_name=".$event."&event_id=".$eventId."");
		exit();
	} else {
		if(!preg_match("/^[a-zA-Z æøå\/]*$/", $comment)) {
			header("Location: ../event.php?comment=invalid&event_name=".$event."&event_id=".$eventId."");
			exit();
		} else {
			//Actual comment function
			$date = date('Y-m-d H:i:s');

			$sql = "INSERT INTO comments (user_id, event_id, comment_input, comment_datetime) VALUES ('$thisUserId', '$eventId', '$comment', '$date');";
			$result = mysqli_query($conn, $sql);

			header("Location: ../event.php?comment=success&event_name=".$event."&event_id=".$eventId."");
			exit();
		}
	}
} else {
	echo "You are not supposed to be here";
}