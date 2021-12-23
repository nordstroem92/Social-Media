<?php
	include_once 'index-navbar.php';
	include_once 'includes/dbconnection.php';
	$thisUserId = $_SESSION['u_id'];
?>

<div class="custom-container">
	<div class="row">
		<?php
			include_once "userinfo.php"	
		?>
		<div class="col-lg-8 offset-lg-2" id="main-div">
			<div class="card card-body bg-light">
				<h1>Shared requests</h1>
				</br>
				<?php
					$sql = "SELECT * FROM sharedcalendar WHERE other_user_id='$thisUserId' AND request_send='1' AND request_accepted='0';";
					$result = mysqli_query($conn, $sql);
					while($row = mysqli_fetch_assoc($result)){
						//get full name of user that send request
						$requstUserId = $row['user_id'];
						$sqlRequestUserName = "SELECT * FROM users WHERE user_id='$requstUserId';"; 
						$requestUserNameResult = mysqli_query($conn, $sqlRequestUserName);
						$requestUserFirstname = "";
						$requestUserLastname = "";
						while($rowGetName = mysqli_fetch_assoc($requestUserNameResult )){
							$requestUserFirstname = $rowGetName['user_firstname'];
							$requestUserLastname = $rowGetName['user_lastname'];
						}

						echo $requestUserFirstname." ".$requestUserLastname." has send you a request";
						echo "</br></br><form class='form-group' action='includes/accept-share-request-function.php?requester_user_id=".$requstUserId."' method='POST'>
								<button class='btn btn-small btn-primary' type='submit'>Accept request</button>
							</form><form class='form-group' action='includes/reject-share-request-function.php?requester_user_id=".$requstUserId."' method='POST'>
								<button class='btn btn-danger' type='submit'>Reject request</button>
							</form>";
					}
				?>
			</div>
			</br>
			<div class="card card-body bg-light">
				<p><b>Followers</b></br>
				<?php
					//echo all friend names
					$sql = "SELECT * FROM sharedcalendar WHERE other_user_id='$thisUserId' AND request_accepted='1';";
					$result = mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result)){
						$friend_id = $row['user_id'];
						$sqlNames = "SELECT * FROM users WHERE user_id='$friend_id';";
						$namesResult = $result = mysqli_query($conn, $sqlNames);
						while ($row = mysqli_fetch_assoc($namesResult)){
							echo "<span class='badge badge-pill badge-info' style='margin-right: 4px;'><a href='user-profile.php?user_id=".$friend_id."'>".$row['user_firstname']." ".$row['user_lastname']."</a></span>";
						}
					}
				?>
			</p>
			</div>
			</br>
			<div class="card card-body bg-light">
				<p><b>Following</b></br>
				<?php
					//echo all friend names
					$sql = "SELECT * FROM sharedcalendar WHERE user_id='$thisUserId' AND request_accepted='1';";
					$result = mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result)){
						$friend_id = $row['other_user_id'];
						$sqlNames = "SELECT * FROM users WHERE user_id='$friend_id';";
						$namesResult = $result = mysqli_query($conn, $sqlNames);
						while ($row = mysqli_fetch_assoc($namesResult)){
							echo "<span class='badge badge-pill badge-info' style='margin-right: 4px;'><a href='user-profile.php?user_id=".$friend_id."'>".$row['user_firstname']." ".$row['user_lastname']."</a></span>";
						}
					}
				?>
				</p>
			</div>
		</div>
	</div>
</div>