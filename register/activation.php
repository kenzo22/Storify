<?php
include "../global.php";

session_start();
$username = urldecode($_GET['un']);
$email = urldecode($_GET['em']);
if (!empty($username) && !is_null($username)){				//激活注册用户
	$result = $DB->fetch_one_array("select * from ".$db_prefix."user where username='".$username."' AND email='".$email."'");
	if($result)
	{
		if($result['activate'] == 1)
		{
		  echo "<script>alert('您已经激活了！');window.location.href='../member/source.php';</script>";
		}
		else
		{
		  $upresult=$DB->query("update ".$db_prefix."user set activate='1'  WHERE username='".$username."'");
		  if($upresult)
		  {
			  $userresult=$DB->fetch_one_array("select id, username from ".$db_prefix."user where username='".$username."' AND email='".$email."'");
			  $_SESSION['uid']=$userresult['id'];
			  $_SESSION['username']=$userresult['username'];
			  echo "<script>alert('用户激活成功！');window.location.href='../member/source.php';</script>";
		  }
		}	
	}
	else
	{
	  echo "<script>alert('用户激活失败！');window.location.href='/register/register_form.php';</script>";
	}
}
?>
