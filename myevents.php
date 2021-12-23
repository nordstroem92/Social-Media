<?php
	include_once 'index-navbar.php';
	include_once 'includes/dbconnection.php';		
	$userId = $_SESSION['u_id'];
	$eventDates = array();
?>
<div class="custom-container">
	<div class="row">
		<?php
			include_once "userinfo.php"	
		?>
		<div class="col-lg-8 offset-lg-2" id="main-div">
			<div class="card card-body bg-light">
				<h1>My events</h1>
				</br>
				<?php
					$sql = "SELECT * FROM going WHERE user_id='$userId';";
					$result = mysqli_query($conn, $sql);
					$resultNumRows = mysqli_num_rows($result);
					if ($resultNumRows == 0) {
						echo "You don't have any upcoming events";
					} else {
						echo "<div id='accordion'>
									<div class='card'>
									    <div class='card-header' id='headingOne'>
										    <button class='btn btn-link' data-toggle='collapse' data-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>
										        	View all events
										    </button>
									    </div>
											<div id='collapseOne' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'>
												<div class='card-body'>";
						while ($row = mysqli_fetch_assoc($result)) {
							$eventId = $row['event_id'];
							$sqlEventsDB = "SELECT * FROM events WHERE event_id='$eventId';";
							$resultEventsDB = mysqli_query($conn, $sqlEventsDB);
							$resultEventsDBNumRows = mysqli_num_rows($resultEventsDB);
							if ($resultEventsDBNumRows == 0) {
								echo "Event doesn't exist no more";
							} else {
								while($row = mysqli_fetch_assoc($resultEventsDB)){
									echo "<div id='eventData'>";
									echo "<a href='event.php?event_name=".$row['event_name']."&event_id=".$row['event_id']."'><p><b style='display: inline;'>".$row['event_name']."</b> @ ".$row['event_location']." | ".$row['event_date']." | ".$row['event_time']."</br>".$row['event_description']."</p></a>";
									$eventDate = $row['event_date'];
									array_push($eventDates, $eventDate);
									echo "</div>";
								}
							}
						}
						echo "</div></div></div></div>";
					}
				?>
			</div>
			</br>
		</div>
	</div>
</div>

<!--MODAL-->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Events for this date</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        Modal body..
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
  crossorigin="anonymous"></script>

<script type="text/javascript">
	$( document ).ready(function() {
		let $date = new Date();
		$month = $date.getMonth();

		const $monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"];

		$monthName = $monthNames[$month];
		$year = new Date().getFullYear();
		$daysInMonth = new Date($year, $month+1, 0).getDate();
		$firstDayFullString = new Date($date.getFullYear(), $month, 1);
		$firstDay = $firstDayFullString.toString().substring(0, 3);

		$eventDates = [];
		$thCount = 0;

		//create header and buttons
		$('#main-div').append("<div style='text-align:center; width: 100%;'><button id='previous-month' class='btn btn-default' style='float: left'><i class='fa fa-angle-double-left' aria-hidden='true'></i></button><h3 id='calendar-title' style='display: inline-block;'></h3><button id='next-month' class='btn btn-default' style='float: right'><i class='fa fa-angle-double-right' aria-hidden='true'></i></button><div></br>");

		getEventDates();

		$("#next-month").click(function() {
			if ($month == 11) { 
				$month = 0;
				$year ++;
				console.log($year);
			} else {
				$month++;
			}
			$monthName = $monthNames[$month];
			$daysInMonth = new Date($year, $month+1, 0).getDate();
			$firstDayFullString = new Date($year, $month, 1);
			$firstDay = $firstDayFullString.toString().substring(0, 3);
			$thCount = 0;
		  	$("#calendar-title").empty();
		  	$("#calendar-div").empty();
		  	createCalendar();
		});

		$("#previous-month").click(function() {
			if ($month == 0) { 
				$month = 11;
				$year--;
			} else {
				$month--;
			}
			$monthName = $monthNames[$month];
			$daysInMonth = new Date($year, $month+1, 0).getDate();
			$firstDayFullString = new Date($year, $month, 1);
			$firstDay = $firstDayFullString.toString().substring(0, 3);
			$thCount = 0;
			$("#calendar-title").empty();
		  	$("#calendar-div").empty();
		  	createCalendar();
		});

	});

function showModal(date, id){ //show modal and get event names from database
	$.post("includes/get-events.php", { date: date
		}, function(data, status) {
			$stringBuffer = "";
			$parsedArray = JSON.parse(data);

			$.each( $parsedArray, function(i) {
			  	$stringBuffer += $parsedArray[i];
			  	$stringBuffer += "</br>";
			});
			$(".modal-body").html($stringBuffer);
			$('.modal').modal({
	        	show: true
	    	});
	});
};

function getEventDates(){
	$.post("includes/get-event-dates.php", {
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
	$('#main-div').append("<div id=calendar-div></div>");
	$('#calendar-title').append("Your plans for "+$monthName+" "+$year+"");
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

 		for($dates in $eventDates){
 			$event_month = parseInt($eventDates[$dates].substring(5,7))-1;
 			$event_day = parseInt($eventDates[$dates].substring(8,10));
 			$event_year = parseInt($eventDates[$dates].substring(0,4));

 			if($dayCreated == false){
	 			if ($event_month == $month && $event_day == $dayCount && $event_year == $year) {
	 				$event_month = parseInt($event_month);
	 				$event_month++;
	 				$event_month.toString();
	 				if (parseInt($event_month) < 10) {
	 					$event_month = "0"+$event_month;
	 				}
	 				if (parseInt($event_day) < 10) {
	 					$event_day = "0"+$event_day;
	 				}
	 				$eventFullDate = $event_year+"-"+$event_month+"-"+$event_day;
	 				$dateId = $eventFullDate+$i;

	 				$('#calendar').append("<td id='"+$dateId+"' class='clickable' style='background-color:#33ffad'>"+$i+"</td>");

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
	$(".clickable").click(function(event) { // call show modal function with parameters date & td_id
		$thisId = event.target.id;
		$eventFullDate = $thisId.substr(0, 10);
		$dateId =  $thisId.substr(10, $thisId.length-1);
		showModal($eventFullDate, $dateId);
	});
	$(".clickable").hover(function(){
		$(this).css("cursor", "pointer");
		$(this).animate({
		    backgroundColor: "#33ebff"
		  }, 200 );
	}, function(){
		//$(this).css("background-color","red");
		$(this).animate({
		    backgroundColor: "#33ffad"
		  }, 200 );
	});

	$('#calendar-div').append("</table>");
};
</script>
