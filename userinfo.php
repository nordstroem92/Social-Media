<div class="col-lg-2 d-none d-xl-block" id="user-info">
<div class="card card-body bg-light">
<?php
include_once "includes/dbconnection.php";

	$user_id = $_SESSION['u_id'];
	$sql = "SELECT * FROM useraddinfo WHERE user_id='$user_id'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			if($row['user_img_status'] == 1){
				$filename = "uploads/profile".$user_id."*";
				$fileinfo = glob($filename);
				$fileext = explode(".", $fileinfo[0]);
				$fileactualext = $fileext[1];

				echo "<img style='width: 100px; height: 100px; margin: 0px auto 10px auto;' src='uploads/profile".$user_id.".".$fileactualext."?".mt_rand()."'>";
			} else {
				echo "<img style='width: 100px; height: 100px; margin: 0px auto 10px auto;' src='uploads/profiledefault.jpg'>";
			}
		}
	} else {
		echo "no matching user";
	}

	//Get number of shared friend request
	$sqlSharedRequests = "SELECT * FROM sharedcalendar WHERE other_user_id='$user_id' AND request_send='1' AND request_accepted='0';";
	$sharedRequestsResult = mysqli_query($conn, $sqlSharedRequests);
	$sharedRequestsNumRows = mysqli_num_rows($sharedRequestsResult);

	//echo all user info
	if($sharedRequestsNumRows > 0){
		echo "<p><b>".$_SESSION['u_first']." ".$_SESSION['u_last']."</b></br>"."<a href='myevents.php'>My events</a></br><a href='shared-requests.php'>Calendar requests: <span class='badge badge-pill badge-danger'>".$sharedRequestsNumRows."</span></a></p>";
	} else {
		echo "<p><b>".$_SESSION['u_first']." ".$_SESSION['u_last']."</b></br>"."<a href='frontpage.php'>Frontpage</a></br><a href='myevents.php'>My events</a></br><a href='shared-requests.php'>Friends</a></p>";
	}
?>

<button id="show-img-buttons" class="btn btn-basic">Edit user info</button>
<div id="img-button-container"></div>
</div>
</br>
<div class="card card-body bg-light">
	<p><b>Stats</b></p>

</div>
</div>
