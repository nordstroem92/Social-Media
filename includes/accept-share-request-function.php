<?php
session_start();
include_once 'dbconnection.php';

$requester_user_id = $_GET['requester_user_id'];
$accepter_user_id = $_SESSION['u_id'];

//get relation_id of query
$sql = "SELECT relation_id FROM sharedcalendar WHERE user_id='$requester_user_id' AND other_user_id='$accepter_user_id';";
$result = mysqli_query($conn, $sql);
$relation_id = "";
while ($row = mysqli_fetch_assoc($result)) {
	$relation_id = $row['relation_id'];
}
//update row in table
$sqlUpdate = "UPDATE sharedcalendar SET request_accepted='1' WHERE relation_id='$relation_id';";
$updateResult = mysqli_query($conn, $sqlUpdate);

Header("Location: ../shared-requests.php?request_accepted=success");
exit();