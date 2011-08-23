<?php
include "../global.php";

session_start();
$username = urldecode($_GET['username']);
if (!empty($username) && !is_null($username)){				//激活注册用户
	$result = $DB->fetch_one_array("select * from ".$db_prefix."user where username='".$username."'");
	if ($result){
		$upresult=$DB->query("update ".$db_prefix."user set activate='1'  WHERE username='".$username."'");
		if($upresult){
			$_SESSION['username']=$username;
			echo "<script>alert('用户激活成功！');window.location.href='../index.php';</script>";
		}else{
			echo "<script>alert('您已经激活！');window.location.href='../index.php';</script>";
		}
		
	}else{
		echo "<script>alert('用户激活失败！');window.location.href='register_new.php';</script>";
	}
}
?>