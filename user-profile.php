<?php
	include_once 'index-navbar.php';
	include_once 'includes/dbconnection.php';
	$thisUserId = $_SESSION['u_id'];
	$profileUserId = mysqli_real_escape_string($conn, $_GET['user_id']);
	$_SESSION['other_user_id'] = $profileUserId;

	$profileUserFirstname = "";
	$profileLastLastname = "";

	$sqlFindUser = "SELECT * FROM users WHERE user_id='$profileUserId ';";
	$findUserResult = mysqli_query($conn, $sqlFindUser);
	$findUserResultNumRows = mysqli_num_rows($findUserResult);
	if($findUserResultNumRows > 0) {
		while ($row = mysqli_fetch_assoc($findUserResult)) {
			$profileUserFirstname= $row['user_firstname'];
			$profileUserLastname= $row['user_lastname'];
		}
	} else {
		echo "This user does not exist anymore";
	}
?>

<div class="custom-container">
	<div class="row">
		<!--SHOW PROFILE GENERAL INFO-->
		<?php
			include_once "userinfo.php"	
		?>
		<div class="col-lg-8 offset-lg-2" id="main-div">
			<div class="card card-body bg-light">
				<?php
					$user_id = $profileUserId;
					$sql = "SELECT * FROM useraddinfo WHERE user_id='$user_id';";
					$result = mysqli_query($conn, $sql);
					echo "<div id='profile-info-container' style='width: 100%;'><div id='text-container' style='display:inline-block; float:left; width: auto'><h2>".$profileUserFirstname." ".$profileUserLastname."</h2><p>Lorem ipsum info text</p></div>";
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							if($row['user_img_status'] == 1){
								$filename = "uploads/profile".$user_id."*";
								$fileinfo = glob($filename);
								$fileext = explode(".", $fileinfo[0]);
								$fileactualext = $fileext[1];

								echo "<img style='display: inline; float: right; width:100px; height: 100px;' src='uploads/profile".$user_id.".".$fileactualext."?".mt_rand()."'></div>";
							} else {
								echo "<img style='display: inline; float: right; width:100px; height: 100px;' src='uploads/profiledefault.jpg'></div>";
							}
						}
					} else {
						echo "no matching user";
					}


					//check if calendar is shared
					$sqlGetThisUser ="SELECT * FROM sharedcalendar WHERE user_id='$thisUserId';";
					$getThisUserResult = mysqli_query($conn, $sqlGetThisUser);
					$requestSent = false;
					$isAccepted = false;
					while($row = mysqli_fetch_assoc($getThisUserResult)){
						if($row['other_user_id'] == $profileUserId){ //is request sent
							$requestSent = true;
						} 
						if($row['request_accepted'] == 1){ //is request accepted
							$isAccepted = true;
						}
					}
					if($requestSent && $isAccepted) { //if request is accepted
						echo "<p>You are friends</p>";
						echo "<form action='includes/unshare-calendar-function.php' method='POST'>
								<button class='btn btn-primary'>Unshare calendar</button>
							</form>";
					} else if($requestSent && !$isAccepted ){ //if request has been sent
						echo "<p>Request sent</p>";
						echo "<form action='includes/unshare-calendar-function.php' method='POST'>
								<button class='btn btn-primary'>Delete request</button>
							</form>";
					} else if (!$requestSent){ //if request is not send
						echo "<p>You are not friends</p>";
						echo "<form action='includes/send-share-request-function.php' method='POST'>
								<button class='btn btn-primary'>Share calendar</button>
							</form>";
					}
				?>
			</div>

			<br/>
		</div>
	</div>
</div>

<script type="text/javascript">
	$isShared = false;
	$isAccepted = false;
	$( document ).ready(function() {

		//check if calendar is shared
		$.post("includes/is-calendar-shared.php", {
			}, function(data, status) {
				for(var i = 0; i < data.length; i=i+2) {
					$isShared = data[i];
					$isAccepted = data[i+1];
					if($isAccepted == null){
						$isAccepted = 0;
					}
					console.log($isShared);
					console.log($isAccepted);

				} 
				if ($isShared && $isAccepted) {

					$date = new Date();
					$month = $date.getMonth();
					const monthNames = ["January", "February", "March", "April", "May", "June",
					  "July", "August", "September", "October", "November", "December"
					];
					$monthName = monthNames[$month];
					$year = new Date().getFullYear();
					$daysInMonth = new Date($year, $month+1, 0).getDate();
					$firstDayFullString = new Date($date.getFullYear(), $month, 1);
					$firstDay = $firstDayFullString.toString().substring(0, 3);

					$eventDates = [];
					let $thCount = 0;

					$('#main-div').append("<div style='text-align:center; width: 100%;'><button id='previous-month' class='btn btn-default' style='float: left'><i class='fa fa-angle-double-left' aria-hidden='true'></i></button><h3 id='calendar-title' style='display: inline-block;'></h3><button id='next-month' class='btn btn-default' style='float: right'><i class='fa fa-angle-double-right' aria-hidden='true'></i></button><div></br>");

					getEventDates();

					$("#next-month").click(function(){ //next month button
						if ($month == 11) { 
							$month = 0;
							$year ++;
						} else {
							$month++;
						}
						$monthName = monthNames[$month];
						$daysInMonth = new Date($year, $month+1, 0).getDate();
						$firstDayFullString = new Date($year, $month, 1);
						$firstDay = $firstDayFullString.toString().substring(0, 3);
						$thCount = 0;
						$("#calendar-title").empty();
					  	$("#calendar-div").empty();
					  	createCalendar();
					});

					$("#previous-month").click(function() { //previous month button
						if ($month == 0) { 
							$month = 11;
							$year--;
						} else {
							$month--;
						}
						$monthName = monthNames[$month];
						$daysInMonth = new Date($year, $month+1, 0).getDate();
						$firstDayFullString = new Date($year, $month, 1);
						$firstDay = $firstDayFullString.toString().substring(0, 3);
						$thCount = 0;
						$("#calendar-title").empty();
					  	$("#calendar-div").empty();
					  	createCalendar();
					});
				} else if($isShared && !$isAccepted){
					$('#main-div').append("<p>Request is not accepted yet</p>");
				}
		});
	});

	function getEventDates(){
	$.post("includes/get-other-profiles-event-dates.php", {
		}, function(data, status) {
			$eventDay = "";
		
			for(var i = 0; i < data.length; i++) {
				$eventDay += data[i];
				if($eventDay.length == 10){
					$eventDates = [...$eventDates, $eventDay]; //$eventDates.push($eventDay);
					$eventDay = "";
				}
			}
			createCalendar();
		});
	};

	function createCalendar(){
		//CREATE BLANK TABLE ENTRIES
		$('#calendar-title').append("Your plans for "+$monthName+" "+$year+"");
		$('#main-div').append("<div id=calendar-div></div>");
		$('#calendar-div').append("<table id='calendar' class='table table-bordered' style='width:100%; table-layout: fixed; text-align: center;'>");
		$('#calendar').append("<tr id='calendar-header' style='background-color:#f8f9fa; width: 100%'>");
		$('#calendar-header').append("<th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th><th>S</th>");
		$('#calndar').append("</tr><th>");
		switch ($firstDay) {
		    case "Mon":
		    	break;
		    case "Tue":
		        $('#calendar').append("<td>");
		        $thCount = 1;
		        break;
		    case "Wed":
		        $('#calendar').append("<td><td>");
		        $thCount = 2;
		        break;
		    case "Thu":
		        $('#calendar').append("<td><td><td>");
		        $thCount = 3;
		        break;
		    case "Fri":
		        $('#calendar').append("<td><td><td><td>");
		        $thCount = 4;
		        break;
		    case "Sat":
		        $('#calendar').append("<td><td><td><td><td>");
		        $thCount = 5;
		        break;
		    case "Sun":
		        $('#calendar').append("<td><td><td><td><td><td>");
		        $thCount = 6;
		        break;
		    default:
		        console.log("There was an error with the calendar");
		}

	    for ($i = 1; $i < $daysInMonth+1; $i++) {
	    	$dayCount = $i;
	    	$dayCreated = false;

	 		for($eventDays in $eventDates){
	 			$event_month = parseInt($eventDates[$eventDays].substring(5,7))-1;
	 			$event_day = parseInt($eventDates[$eventDays].substring(8,10));
	 			$event_year = parseInt($eventDates[$eventDays].substring(0,4));

	 			if($dayCreated == false){
		 			if ($event_month == $month && $event_day == $dayCount && $event_year == $year) {
		 				$('#calendar').append("<td style='background-color:#33ffad'>"+$i+"</td>");
		 				$thCount++;
		 				$dayCreated = true;
		 			}	
	 			}
	 		}
	 		if ($dayCreated == false){
	 			$('#calendar').append("<td>"+$i+"</td>");
				$thCount++;
				$dayCreated = true;
	 		}
			if ($thCount == 7) {
					$('#calendar').append("</tr><tr>");
					$thCount = 0;
			} 
	 
		}
		$('#calendar-div').append("</table>");
	};
</script>