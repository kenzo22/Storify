<?php
  require_once "../connect_db.php"; 
  require_once('../include/class.phpmailer.php');
  
  function check_email($field,$email,$table){
	try 
	{
		global $DB;
		$result = $DB->query("select * from ".$table." where ".$field."='".$email."'");
		if ($DB->num_rows($result)>0)
			return true;
		else 
			return false;
	}
	catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
  }
  
  function sendEmail($dest,$subject='',$message='')
  {
  	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	$mail->ishtml(true);
  	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "localhost"; // SMTP server
	$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	//$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Host       = "smtp.qq.com";      // sets qq as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->Username   = "11473124@qq.com";  // qq username
	$mail->Password   = "kenzo22";            // qq password

	$mail->SetFrom('11473124@qq.com', 'StoryBing');

	$mail->AddReplyTo("kenzo@storybing.com","StoryBing");

	$mail->Subject    = $subject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($message);

	$mail->AddAddress($dest);

	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	}
	return true;
  }
  
?>