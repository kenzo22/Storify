<?php
include "../global.php";
require_once('../include/class.phpmailer.php');


if($_GET['act']!="forget_pwd")
{
  $content = "<form method='get'>
  <div class='inner' style='padding-top:50px;'> 
    <span class='title'> 重设密码 </span>  
    <div id='forget_passwd' style='margin-top:20px; margin-bottom:20px;'> 
	  <div>
	    <span class='field_name'>您的邮箱:</span> 
		<span ><input type='text' name='email' id='signup_email' size='30' maxlength='100'></span>
		<span class='form_tip' id='email_tip'></span>
	  </div> 	
      <div style='margin-top:20px;'>
		<span> 
	      <input type='submit' class='bn_submit' id='btn_submit_forget' value='重设密码'></input>  
	      <input type='hidden' name='act' value='forget_pwd'>
	    </span> 
	  </div>
	</div></div></form>";
  echo $content;
}
else
{
  $email=addslashes(htmlspecialchars(trim($_GET['email'])));  
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$email."'");
  if (!$result)
  {
    go("forget_form.php","没有这个注册用户",2);
  }
  else
  {
  $username = $result['username']; 
  $url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/forget_passwd.php';
  //$url = 'http://'.$_SERVER['SERVER_NAME'].':8080'.dirname($_SERVER['SCRIPT_NAME']).'/forget_passwd.php';
  $url .= '?username='.urlencode($username);

  $mail = new PHPMailer();
  $mail->CharSet = "gb2312";
  $mail->ishtml(true);
  $body = '<p>您的密码重设要求已经得到验证。请点击以下链接输入您新的密码: <br/><br/>

(pleae click on the following link to reset your password:)<br/><br/>

<a href="'.$url.'" target="_blank">'.$url.'</a><br/><br/>

如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入StoryBing。<br/><br/>

感谢您对口立方的支持，再次希望您在口立方的体验有益和愉快。<br/><br/>

口立方 http://www.koulifang.com<br/><br/>

(这是一封自动产生的email，请勿回复。)</p>';

  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->Host       = "localhost"; // SMTP server
  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  //$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Host       = "smtp.qq.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  //$mail->Username   = "xinxinzhang22@gmail.com";  // GMAIL username
  //$mail->Password   = "kooky233";            // GMAIL password
  $mail->Username   = "11473124@qq.com";  // qq username
  $mail->Password   = "kenzo22";            // qq password

  $mail->SetFrom('11473124@qq.com', 'Koulifang');

  $mail->AddReplyTo("kenzo@koulifang.com","Koulifang");

  $mail->Subject    = "重设".$username."在口立方的密码";

  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

  $mail->MsgHTML($body);

  $address = "11473124@qq.com";
  $mail->AddAddress($address, "kooky_233");

  if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
    //echo "Message sent!";
	$content="<div class='inner' > <span class='title'> 重设密码 </span> 
	<div><span>请到 ".$email." 查阅来自口立方的邮件, 从邮件重设你的密码。<span></div>
	<div><a target='_blank' href='http://mail.google.com'><span>登录Gmail邮箱查收确认信</span></a></div></div>";
	echo $content;
  }
  }
}

include "../include/footer.htm";	 
?>