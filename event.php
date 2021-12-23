<?php
	include_once 'index-navbar.php';
	include_once 'includes/dbconnection.php';

	$_SESSION['event_name'] = mysqli_real_escape_string($conn, $_GET['event_name']);
	$event = $_SESSION['event_name'];
	$_SESSION['event_id'] = mysqli_real_escape_string($conn, $_GET['event_id']);
	$eventId = $_SESSION['event_id'];
	$userId = $_SESSION['u_id'];
				
?>


<div class="custom-container">
	<div class="row">
		<?php
		include_once "userinfo.php"
		?>	
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-body bg-light">
				<?php
				//check if going
				$sqlCheckIfGoing = "SELECT * FROM going WHERE user_id='$userId' AND event_id='$eventId';";
					$resultCheckIfGoing = mysqli_query($conn, $sqlCheckIfGoing);
					$resultCheckIfGoingNumRows = mysqli_num_rows($resultCheckIfGoing);
					if ($resultCheckIfGoingNumRows > 0) {
						echo "
							<form action='includes/not-going-function.php' method='POST'><h1 style='display: inline-block'>".$event."</h1>
								<button class='btn btn-danger' style='float:right'><i class='fa fa-times' aria-hidden='true'></i></button>
							</form>
						";
					} else {
						echo "
							<form action='includes/going-function.php' method='POST'><h1 style='display: inline-block'>".$event."</h1>
								<button class='btn btn-info' style='float:right'><i class='fa fa-check' aria-hidden='true'></i></button>
							</form>
						";
					}
				$sql = "SELECT * FROM events WHERE event_name='$event';";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo "<p>@ ".$row['event_location']." | ".$row['event_date']." | ".$row['event_time']."</p>";
						}
					}
				?>

				<?php
					$sqlThisEvent = "SELECT * FROM events WHERE event_name='$event';";
					$result = mysqli_query($conn, $sqlThisEvent);
					while ($row = mysqli_fetch_assoc($result)) {
						$imageStatus = $row['event_img_status'];
						$eventId = $row['event_id'];
						if($imageStatus == 1) {
							$filename = "uploads/event".$eventId."*";
							$fileinfo = glob($filename);
							$fileext = explode(".", $fileinfo[0]);
							$fileactualext = $fileext[1];
							list($width, $height) = getimagesize("uploads/event".$eventId.".".$fileactualext);
							if ($width == 700 && $height == 100) {
								echo "<img style='width: 100%; height: auto;' src='uploads/event".$eventId.".".$fileactualext."?".mt_rand()."'></br>";
							} else {
								echo "<p style='color:red'>Event image cant be displayed</p>";
							}
						} else {
							// no event image
						}
					}
				?>
				<?php
					$sql = "SELECT * FROM events WHERE event_name='$event';";
					$result = mysqli_query($conn, $sql);
					while($row = mysqli_fetch_assoc($result)){
						$adminId = $row["user_id"];
						$sqlGetAdminId = "SELECT * FROM users WHERE user_id='$adminId';";
						$resultGetAdminId = mysqli_query($conn, $sqlGetAdminId);
						while($row = mysqli_fetch_assoc($resultGetAdminId)){
							$adminName = $row["user_firstname"]." ".$row["user_lastname"];
							echo "<p><b>Event created by:</b> <span class='badge badge-pill badge-danger'>".$adminName."</span></p>";
						}
					}
				?>
				<p><b>Going:</b>
				<?php //Output all going users
					$sql = "SELECT * FROM going WHERE event_id='$eventId';";
					$result = mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result)) {
						 $thisUserId = $row['user_id'];
						 $sqlGetUserId = "SELECT * FROM users WHERE user_id=$thisUserId;";
						 $resultGetUserId = mysqli_query($conn, $sqlGetUserId);
						 while ($row = mysqli_fetch_assoc($resultGetUserId)) {
						 	$userFirstName = $row['user_firstname'];
						 	$userLastName = $row['user_lastname'];

						 	//Link to specific user
						 	echo "<span class='badge badge-pill badge-info' style='margin-right: 4px;'><a href='user-profile.php?user_id=".$thisUserId."'>".$userFirstName." ".$userLastName."</a></span>";
						 }
					}
					echo "</p>";
				?>

				<div id="event-description"> 
					<p>
					<?php //output event info description
						$sql = "SELECT * FROM events WHERE event_name='$event';";
						$result = mysqli_query($conn, $sql);
						while ($row = mysqli_fetch_assoc($result)){
							$event_description = $row['event_description'];
							if (strpos($event_description,"TimedDataStart")){
								$exploded1 = explode("TimedDataStart", $event_description);
								$exploded2 = explode("TimedDataStop", $exploded1[1]);
								$dateTime = substr($exploded2[0],0,12);
								$timedMessage = substr($exploded2[0],12);

								$todaysDate = date('YmdHi');
								if($todaysDate >= $dateTime){
									echo $exploded1[0].$timedMessage.$exploded2[1];
								} else {
									echo $exploded1[0].$exploded2[1]; 
								}
							} else {
								echo $event_description;
							}
						}			
					?>
					</p>
				</div>
	
				<?php //edit info accordion
					$sql = "SELECT * FROM events WHERE event_name='$event';";
					$result = mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result)){ 
						if ($row['user_id'] == $userId){ //echo edit info accordion
							echo "<div id='accordion'><div class='card'><div class='card-header' id='headingOne'><button class='btn btn-link' data-toggle='collapse' data-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>Event options</button></div><div id='collapseOne' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'><div class='card-body'><h4>Upload event image</h4><p>The image must be 700 x 100px</p><form action='includes/upload-event-img-function.php?event_name=".$event."'' method='POST' enctype='multipart/form-data'><input type='file' name='file'><button class='btn btn-primary' type='submit' name='event-img-submit'>Upload</button></form>";

							if ($row['event_img_status'] == 1) {
								echo "<form action='includes/delete-event-img-function.php' method='POST'>
									<button class='btn btn-primary' type='submit' name='delete-submit'>Delete</button>
								</form>";
							}
							echo "</br><button class='btn btn-info' class='btn btn-info' data-toggle='modal' data-target='#testEventModal'>Edit event</button>"; //edit event button 
							echo "</div></div></div></div>"; //closing tags for accordion
							include 'edit-event-modal.txt';
						}
					}
				?>
			</div>
			</br>
			<div class="card card-body bg-light">
				<?php
					//output comments
					$sql = "SELECT * FROM comments WHERE event_id='$eventId';";
					$result = mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result)) {
						$commentPosterId = $row['user_id'];
						$commentPosterName = "";
						$comment = $row['comment_input'];
						$commentId = $row['relation_id'];
						$comment_timestamp = $row['comment_datetime'];
						$sqlGetCommentPosterName = "SELECT * FROM users WHERE user_id='$commentPosterId';";
						$resultGetCommentPosterName = mysqli_query($conn, $sqlGetCommentPosterName);
						$resultGetCommentPosterNameNumRows = mysqli_num_rows($resultGetCommentPosterName);
						if (!$resultGetCommentPosterNameNumRows > 0) {
							echo "DELETED USER";
						} else {
							while ($row = mysqli_fetch_assoc($resultGetCommentPosterName)) {
								$commentPosterName = $row['user_firstname']." ".$row['user_lastname'];
							}
						}

						if($userId == $commentPosterId){
							echo "<form action='includes/delete-comment.php' method='POST'>

							<p><b>".$commentPosterName."</b> | ".$comment_timestamp." | #<span id='commentIdSpan'>".$commentId."</span>

							<input type='hidden' name='commentId' value='".$commentId."'>

							<button type='submit' class='close' aria-label='Close'>&times;</button>

							</br>".$comment."</form>";
						} else {
							echo "<p><b>".$commentPosterName."</b> | ".$comment_timestamp." | #<span id='".$commentId."'>".$commentId."</br>".$comment."</p>";
						}
					}
				?>
			</div>
			</br>
			<div class="card card-body bg-light">
				<form action="includes/add-comment.php" method="POST">
					<textarea id="comment-textarea" class="form-control" type="textarea" name="comment" placeholder="Write a comment"></textarea>
					<button class="btn btn-warning" type="submit" name="comment-submit" style="float: right;">Add comment</button>
				</form>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">
	/*$(document).ready(function() {

		$eventId = location.search.split('event_id=')[1].split('&')[0];
		$eventId = location.search.split('event_id=')[1].split('&')[0];
		$userId = location.search.split('user_id=')[1];
		alert($eventId+" ");
    	$.post('includes/get-event-info.php', 
    		function(data, status){
    			alert(data);
    		});
	});*/
</script>

</body>
</html>