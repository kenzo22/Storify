<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 

function islogin()
{
 global $_SESSION;
 global $DB;
 global $db_prefix;
 if(empty($_SESSION['uid']))
 {
   if($_COOKIE['email'] != '' && $_COOKIE['password'] != '')
   {
     $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
     if(!empty($userinfo['id']))
     {
       $_SESSION['uid']=intval($userinfo['id']);
       $_SESSION['username']=$userinfo['username'];
	   
	   $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	   if($userinfo['weibo_access_token'] == '')
	   {
		 $_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
		 $_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	   }
	   else
	   {
		 $_SESSION['last_wkey']['oauth_token']=$userinfo['weibo_access_token'];
		 $_SESSION['last_wkey']['oauth_token_secret']=$userinfo['weibo_access_token_secret'];
	   }
	   if($userinfo['tweibo_access_token'] == '')
	   {
		 $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
		 $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
	   }
	   else
	   {
		 $_SESSION['last_tkey']['oauth_token']=$userinfo['tweibo_access_token'];
		 $_SESSION['last_tkey']['oauth_token_secret']=$userinfo['tweibo_access_token_secret'];
	   }
	  
	   $_SESSION['last_dkey']['oauth_token']=$userinfo['douban_access_token'];
	   $_SESSION['last_dkey']['oauth_token_secret']=$userinfo['douban_access_token_secret'];
	   $_SESSION['yupoo_token'] = $userinfo['yupoo_token'];
	   
	   return 1;
     }
   }
   return 0;
 }
 else
 {
   return 1;
 }
}

function getUserInfo($email, $password)
{
global $DB;
global $db_prefix;
$email=(trim($email));
$passwd=trim($password);
$result = $DB->fetch_one_array("SELECT * FROM story_user WHERE email='".$email."' AND passwd='".$passwd."'");
return $result;
}

function getUserPic($uid)
{
  global $DB;
  $userresult = $DB->fetch_one_array("SELECT photo FROM story_user where id='".$uid."'");
  if($userresult['photo'] == '')
  {
	$user_profile_img = '/img/douban_user_dft.jpg';
  }
  else
  {
	$user_profile_img =$userresult['photo'];
  }
  return $user_profile_img;
}

function getPublicToken()
{
  global $_SESSION;
  global $DB;
  global $db_prefix;
  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
  if($_SESSION['last_wkey']['oauth_token'] == '')
  {
	$_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
	$_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
  }
  $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
  $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
}

?>
