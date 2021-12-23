<?php
session_start();
include_once "dbconnection.php";

if (isset($_POST['delete-submit'])) { 
	$id = $_SESSION['u_id'];

	$filename = "../uploads/profile".$id."*";
	$fileinfo = glob($filename);
	$fileext = explode(".", $fileinfo[0]);
	$fileactualext = $fileext[3];

	$file = "../uploads/profile".$id.".".$fileactualext;

	if (!unlink($file)) {
		echo "file was not deleted";
	} else {
		echo "file was deleted";
	}

	$sql = "UPDATE useraddinfo SET user_img_status=0 WHERE user_id='$id';";
	mysqli_query($conn, $sql);
	header("Location: ../frontpage.php?delete=success");
}