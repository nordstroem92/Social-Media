<?php

if (isset($_POST['signup-submit'])) {
	
	include_once 'dbconnection.php';

	$first = mysqli_real_escape_string($conn, $_POST['firstname']);
	$last = mysqli_real_escape_string($conn, $_POST['lastname']);
	$email = mysqli_real_escape_string($conn, $_POST['signup-email']);
	$password = mysqli_real_escape_string($conn, $_POST['signup-password']);

	//Error handlers
	//Check for empty field
	if(empty($first)|| empty($last) || empty($email) || empty($password)){
		header("Location: ../index.php?signup=empty");
		exit();
	} else {
		//Check if input characters are valid
		if(!preg_match("/^[a-zA-Z æøå\/]*$/", $first) || !preg_match("/^[a-zA-Z æøå\/]*$/", $last)) {
			header("Location: ../index.php?signup=invalid");
			exit();
		} else {
			//Check if email is valid
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				header("Location: ../index.php?signup=email");
				exit();
			} else {
				$sql = "SELECT * FROM users WHERE user_email='$email'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);

				if($resultCheck > 0) {
					header("Location: ../index.php?signup=emailtaken");
					exit();
				} else {
					//Hashing the password
					$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
					//Insert the user into the database
					$sql_usertable = "INSERT INTO users (user_firstname, user_lastname, user_email, user_password) VALUES ('$first', '$last', '$email', '$hashedPwd');";
					mysqli_query($conn, $sql_usertable);

					$sql = "SELECT * FROM users WHERE user_email='$email'";
					$result = mysqli_query($conn, $sql);
					if ($row = mysqli_fetch_assoc($result)) {
						$id = $row['user_id'];
						$sql_useraddinfo = "INSERT INTO useraddinfo (user_id, user_img_status) VALUES ('$id', '0');";
						mysqli_query($conn, $sql_useraddinfo);
						header("Location: ../index.php?signup=success");
						exit();
					} else {
						echo "Your user was not correctly signed up";
					}
				}
			}
		}
	}

} else {
	header("Location: ../index.php");
	exit();
}