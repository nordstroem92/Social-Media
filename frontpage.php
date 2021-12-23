<?php
	include_once 'index-navbar.php';
	include_once 'includes/dbconnection.php';
?>

<div class="custom-container">
	<div class="row">
		<?php
			include_once "userinfo.php"	
		?>
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-body bg-light">
				<div style="width: 100%">
					<h1 style="display: inline-block;">Welcome
					<?php
						echo $_SESSION['u_first'];
					?>
					</h1>
					<button style="display: inline-block; float: right;" type="button" class="btn btn-info" data-toggle="modal" data-target="#createEventModal">
						<i class="fa fa-plus" aria-hidden="true"></i> Event
					</button>
				</div>
			</div>
			</br>
			<form action="search.php" method="POST">
				<input style="max-width: 85%; display: inline" class="form-control" type="text" name="search" placeholder="Search">
				<button style="width: 15%; float:right; "class="btn btn-warning " type="submit" name="submit-search"><i class="fa fa-search" aria-hidden="true"></i>
				</button>
			</form>
			<br>
			<div class="card card-body bg-light">
				<h4>View all events here</h4>
				<p>Categories: 
					<span id="sport" class="badge badge-danger filterBtn">All <i class="fa fa-circle" aria-hidden="true"></i></span>
					<span id="sport" class="badge badge-info filterBtn">Sport <i class="fa fa-heartbeat" aria-hidden="true"></i></span>
					<span id="art" class="badge badge-info filterBtn">Art <i class="fa fa-paint-brush" aria-hidden="true"></i></span>
					<span id="food" class="badge badge-info filterBtn">Food <i class="fa fa-birthday-cake" aria-hidden="true"></i></span>
					<span id="learning" class="badge badge-info filterBtn">Learning <i class="fa fa-university" aria-hidden="true"></i></span>
				</p>

				<div id="eventsContainer">
				<?php
					$sql_events = "SELECT * FROM events LIMIT 4";
					$result = mysqli_query($conn, $sql_events);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo "<span class='event-link'><a href='event.php?event_name=".$row['event_name']."&event_id=".$row['event_id']."'><p><b style='display: inline;'>".$row['event_name']."</b> @ ".$row['event_location']." | ".$row['event_date']." | ".$row['event_time']."</br>".$row['event_description']."</p></a></span>";
						} 
					} else {
						echo "No events yet";
					}
				?>
				</div>
			<button id="loadEventsBtn" type="button" class="btn btn-basic">
					See more events
			</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create an event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	     <form id="myform" class="form-group" action="includes/create-event-function.php" method="POST">
			<input class="form-control" type="text" name="event-name" placeholder="Name of event"></br>
			<input class="form-control" type="text" name="event-location" placeholder="Location"></br>
			<input class="form-control" type="date" name="event-date" placeholder="Date"></br>
			<input class="form-control" type="time" name="event-time" placeholder="Time"></br>
			<span class="badge badge-info category-label">Sport <i class="fa fa-heartbeat" aria-hidden="true"></i></span>
			<span class="badge badge-info category-label">Art <i class="fa fa-paint-brush" aria-hidden="true"></i></span>
			<span class="badge badge-info category-label">Food <i class="fa fa-birthday-cake" aria-hidden="true"></i></span>
			<span class="badge badge-info category-label">Learning <i class="fa fa-university" aria-hidden="true"></i></span>
			<input type="hidden" id="event-category" name="event-category" value="none">
			</br>
			</br>
			<textarea class="form-control" name="event-description" placeholder="Description"></textarea></br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="myform" name="event-submit">Save changes</button>
      </div>
    </div>
  </div>
</div>
	
<script type="text/javascript">
	$(document).ready(function() {

		$(".category-label").click(function(){ //change label color when clicked
			$labelValue = $.trim($(this).text());
			$("#event-category[value]").val($labelValue); //change hidden input value to that of clicked
		});

		$filtered = false;
		$eventCount = 4; 
		$("#loadEventsBtn").click(function(){ //show more events on frontpage
			$eventCount += 2;
			if ($filtered) {
				$("#eventsContainer").load("includes/filter-events-function.php",{
					filterCategory: $filterCategory,
					eventNewCount: $eventCount
				});
			} else {
				$("#eventsContainer").load("includes/show-events-function.php",{
					eventNewCount: $eventCount
				});
			}
		});

		$(".filterBtn").click(function(){ //show events that match selected category filter
			$filtered = true;
			$eventCount = 4;
			$filterCategory = $.trim($(this).text());
			if ($filterCategory == "All"){
				$filtered = false;
				$("#eventsContainer").load("includes/show-events-function.php",{
					eventNewCount: $eventCount
				});
			} else {	
				$("#eventsContainer").load("includes/filter-events-function.php",{
					filterCategory: $filterCategory,
					eventNewCount: $eventCount
				});
			}
		});

		$(".badge").click(function(){ //change label color when clickedW
			addLabelColor($(this));	
		});

		//Less readable functions
		function addLabelColor(clicked){ //change label color when clicked
			if($(clicked).hasClass("filterBtn")){
				$(".filterBtn").addClass( "badge-info"); 
				$(".filterBtn").removeClass( "badge-danger");
			} else if($(clicked).hasClass("category-label")){
				$(".category-label").addClass( "badge-info"); 
				$(".category-label").removeClass( "badge-danger");
			}
			$(clicked).addClass( "badge-danger");
			$(clicked).removeClass( "badge-info");
		}

		function removeLabelColor(){
			$(".badge").removeClass("badge-danger");
			$(".badge").addClass( "badge-info");
		}
	});
</script>

</body>
</html>