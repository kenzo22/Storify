<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();

if(isset($_GET['logout']))
{
	//unset($_SESSION['uid']);
	unset($_SESSION['username']);
	if(!empty($_COOKIE['email']) || empty($_COOKIE['password']))
	{  
	  setcookie("email", null, time()-3600*24*365, "/", ".koulifang.com", 0);  
	  setcookie("password", null, time()-3600*24*365, "/", ".koulifang.com", 0);  
    } 
	echo "<script language='javascript' >
			window.onload = function()
			{
			  WB2.logout(function() 
			  {
				self.location = '/index.php';
			  });
			}
		  </script>";	 
	session_destroy();
	go("/index.php");
	exit;
}

$email=addslashes(htmlspecialchars(trim($_POST['email'])));
$passwd=sha1(trim($_POST["passwd"]));
$autologin=$_POST["autologin"];

if($email && $passwd)
{
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$email."' AND passwd='".$passwd."'" );

  if(!empty($result))
  {
	$activate = intval($result['activate']);
	if($activate == 0)
	{
	  go("/login/login_form.php?next=inactivate");
	  exit;
	}
	$_SESSION['uid']=intval($result['id']);
	$_SESSION['username']=$result['username'];
	if(!empty($autologin))
	{
	  setcookie("email", $email, time()+3600*24*365, "/", ".koulifang.com", 0);  
	  setcookie("password", $passwd, time()+3600*24*365, "/", ".koulifang.com", 0); 
	}
	$_SESSION['weibo_uid']=intval($result['weibo_user_id']);
	if(0 == $_SESSION['weibo_uid'] && '' == $result['tweibo_access_token'])
	{
	  go("/member/source.php");
	  exit;
	}
	else
	{
	  //select a random item from the publictoken pool
	  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	  
	  if($result['weibo_access_token'] == '')
	  {
		$_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
		$_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	  }
	  else
	  {
		$_SESSION['last_wkey']['oauth_token']=$result['weibo_access_token'];
		$_SESSION['last_wkey']['oauth_token_secret']=$result['weibo_access_token_secret'];
	  }
	  if($result['tweibo_access_token'] == '')
	  {
		$_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
		$_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
	  }
	  else
	  {
		$_SESSION['last_tkey']['oauth_token']=$result['tweibo_access_token'];
		$_SESSION['last_tkey']['oauth_token_secret']=$result['tweibo_access_token_secret'];
	  }
	  
	  $_SESSION['last_dkey']['oauth_token']=$result['douban_access_token'];
	  $_SESSION['last_dkey']['oauth_token_secret']=$result['douban_access_token_secret'];
	  $_SESSION['yupoo_token'] = $result['yupoo_token'];
	  
	  
	  if(isset($_GET['next']) && !empty($_GET['next']) && isLocalURL($_GET['next']))
	  {
		header("location: ".$_GET['next']); 
	  }
	  else
	  {
		$temparray = parse_url($_SERVER['HTTP_REFERER']);
		if($temparray['path'] == '/login/login.php')
		{
		  go("/index.php");
		}
		else
		{
		  go($_SERVER['HTTP_REFERER']);
		}
	  }
	}
	
  }
  else
  {
	go("/login/login_form.php");
  }
}
?>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2417356638" type="text/javascript" charset="utf-8"></script>
