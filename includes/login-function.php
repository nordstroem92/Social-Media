<?php 

session_start();

if(isset($_POST['login-submit'])) {

	include_once 'dbconnection.php';

	$email = mysqli_real_escape_string($conn, $_POST['login-email']);
	$password = mysqli_real_escape_string($conn, $_POST['login-password']);

	//Error handlers
	//Check if inputs are empty
	if(empty($email) || empty($password)){
		header("Location: ../index.php?login=empty");
		exit();
	} else {
		$sql = "SELECT * FROM users WHERE user_email='$email';";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			header("Location: ../index.php?login=error");
			exit();
		} else {
			if ($row = mysqli_fetch_assoc($result)) {
				//De-hashing the password
				$hashedPasswordCheck = password_verify($password, $row['user_password']);
				if ($hashedPasswordCheck == false) {
					header("Location: ../index.php?login=error");
					exit();
				} elseif($hashedPasswordCheck == true) {
					//Log in the user here
					$_SESSION['u_id'] = $row['user_id'];
					$_SESSION['u_first'] = $row['user_firstname'];
					$_SESSION['u_last'] = $row['user_lastname'];
					$_SESSION['u_email'] = $row['user_email'];
					header("Location: ../frontpage.php?login=succes");
					exit();
				}
			}
		}
	}
} else {
	header("Location: ../index.php?login=error");
	exit();
}
