<?php
	include_once 'dbconnection.php';	
	session_start();	
	$thisUserId = $_SESSION['u_id'];
	$otherUserId = $_SESSION['other_user_id'];

	$sql = "SELECT * FROM sharedcalendar WHERE user_id='$thisUserId' AND other_user_id='$otherUserId';";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) == 1) {
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['request_accepted'] == 1){
				$isShared=true;
				echo $isShared;
				$isAccepted=true;
				echo $isAccepted;
			} else if ($row['request_accepted'] == 0){
				$isShared=true;
				echo $isShared;
				$isAccepted=false;
				echo $isAccepted;
			}
		}
	} else {
		$isShared=false;
		echo $isShared;
	}
?>