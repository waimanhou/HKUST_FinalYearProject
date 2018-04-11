<?php
require("./phpMailer/class.phpmailer.php");

function sendPasswordMail($newPassword, $email) {
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465;
	$mail->CharSet = "utf-8";
	$mail->Encoding = "base64";
	$mail->Username = "fyp.gsn@gmail.com";
	$mail->Password = "Password.";
	$mail->From = "fyp.gsn@gmail.com";
	$mail->FromName = "Geo-Social Network";
	$mail->Subject = "Password Reset";
	$bd = "Dear User,<br /><br />  Your account password in Geo-Social Network is reset. Please log on the system with the following password and update the password in your personal profile.<br /><br />New Password: ".($newPassword)."<br /><br />Please do not reply to this email.<br /><br />Yours faithfully,<br />Geo-Social Network";
	$mail->Body = $bd;    
	$mail->IsHTML(true);

	$mail->AddAddress($email, $email);

	return ($mail->Send());
}

if (sendPasswordMail("newPassword~", "no1yugioh@gmail.com"))
	echo 'success';
else 
	echo 'fail';
?>
