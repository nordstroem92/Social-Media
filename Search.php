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
				<h1>Matching results</h1>
			</div>
			</br>
			<div class="card card-body bg-light">
				<?php
					if (isset($_POST['submit-search'])) {
						$search = mysqli_real_escape_string($conn, $_POST['search']);
						$sql = "SELECT * FROM events WHERE event_name LIKE '%$search%' OR event_location LIKE '%$search%' OR event_description LIKE '%$search%';";
						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<a href='event.php?event_name=".$row['event_name']."&event_id=".$row['event_id']."'><p><b style='display: inline;'>".$row['event_name']."</b> @ ".$row['event_location']." | ".$row['event_date']." | ".$row['event_time']."</br>".$row['event_description']."</p></a>";
							}
						} else {
							echo "No results matching your search";
						}
					}
				?>
			</div>
		</div>
	</div>	
</div>

