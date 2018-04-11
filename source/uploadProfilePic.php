<?php
session_start();
$allowedExts = array("jpg", "jpeg", "gif", "png");
$extension = end(explode(".", $_FILES["file"]["name"]));
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 20000000)
&& in_array($extension, $allowedExts)){
	if ($_FILES["file"]["error"] > 0){
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	}else{
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

		//if (file_exists("images/user/profilePic/" . $_FILES["file"]["name"])){
		//	echo $_FILES["file"]["name"] . " already exists. ";
		//}else{
			move_uploaded_file($_FILES["file"]["tmp_name"],
			"images/user/profilePic/" . $_SESSION['username'] . "." . $ext);
			//copy($_FILES["file"]["tmp_name"],)
			echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		//}
   }
}else{
  echo "Invalid file";
}
?>