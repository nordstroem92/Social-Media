 <?php
session_start();
include_once 'dbconnection.php';

$id = $_SESSION['u_id'];

if (isset($_POST['profile-img-submit'])) {
	$file = $_FILES['file'];

	$fileName = $_FILES['file']['name'];
	$fileTmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileError = $_FILES['file']['error'];
	$fileType = $_FILES['file']['type'];
	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('jpg', 'jpeg', 'png');

	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 500000) {
				$fileNameNew = "profile".$id.".".$fileActualExt;
				$fileDestination = '../uploads/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
				$sql = "UPDATE useraddinfo SET user_img_status=1 WHERE user_id='$id';";
				mysqli_query($conn, $sql);
				header("Location: ../frontpage.php?upload=success");
	
			} else {
				echo "file is too big";
			}
		} else {
			echo "there was an error uploading your file";
		}
	} else {
		echo "you cannot upload this filetype";
	}
}