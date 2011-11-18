<?php
include "../global.php";
session_start();
$confirmation = $_GET['confirmation'];
$reset_code = substr($confirmation, 0, 8);
$reset = $DB->fetch_one_array("select username, email from ".$db_prefix."reset where reset_code='".$reset_code."'");
if(!empty($reset))
{
  $username = $reset['username'];
  $email = $reset['email'];
}
else
{
  go("/register/register_form.php","没有这个注册用户！",2);
  exit;
}
if ($username!="" && $email!=""){
	$result = $DB->fetch_one_array("select * from ".$db_prefix."user where username='".$username."' AND email='".$email."'");
	if($result)
	{
		if($result['activate'] == 1)
		{
		  go("/index.php","您之前已经激活了，请登录！",2);
		  exit;
		}
		else
		{
		  $upresult=$DB->query("update ".$db_prefix."user set activate='1'  WHERE username='".$username."'");
		  if($upresult)
		  {
			  $userresult=$DB->fetch_one_array("select id, username from ".$db_prefix."user where username='".$username."' AND email='".$email."'");
			  $_SESSION['uid']=$userresult['id'];
			  $_SESSION['username']=$userresult['username'];
			  go("/member/source.php","用户激活成功！",2);
			  exit;
		  }
		}	
	}
}
?>
