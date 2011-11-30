<?php
include $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
include $_SERVER['DOCUMENT_ROOT']."/include/mail_functions.php";
$email=$_POST['email'];
$username=$_POST['uname'];
$reset = $DB->fetch_one_array("select reset_code from ".$db_prefix."reset where username='".$username."' AND email='".$email."'");
if(!empty($reset))
{
  $reset_code = $reset['reset_code'];
}
$current_time = time();
try
{
	$url = 'http://'.$_SERVER['SERVER_NAME'].'/accounts/activation';
	$url .= '?confirmation='.$reset_code.$current_time;
	$subject="口立方注册用户激活邮件";
	$message='<p>欢迎您在口立方注册用户，请点击以下链接以激活您的帐户:<br/><br/>

	(pleae click on the following link to activate your account:)<br/><br/>

	<a href="'.$url.'" target="_blank">'.$url.'</a><br/><br/>

	如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入口立方。<br/><br/>

	感谢您对口立方的支持，希望您在口立方的体验有益和愉快。<br/><br/>

	口立方 http://www.koulifang.com<br/><br/>

	(这是一封自动产生的email，请勿回复。)</p>';
	if(sendEmail($email,$subject,$message))
	{
            echo '1';	
 	}
	else
	{
	    echo '0';
	}
}
catch (Exception $e) 
{
  echo $e->getMessage();
  exit;
}
?>
