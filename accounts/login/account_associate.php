<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
session_start();
if(isset($_POST['email']))
{
  $weibo_uid = $_POST['weibo_uid'];
  $weibo_photo = $_POST['weibo_photo'];
  $email = $_POST['email'];
  $pwd = sha1(trim($_POST["pwd"]));
  $photo_result = $DB->fetch_one_array("select photo from ".$db_prefix."user WHERE email='".$email."'");
  if(!empty($photo_result['photo']))
  {
    $upresult=$DB->query("update ".$db_prefix."user set weibo_user_id='".$weibo_uid."', weibo_access_token='".$_SESSION['last_wkey']['oauth_token']."', weibo_access_token_secret='".$_SESSION['last_wkey']['oauth_token_secret']."', activate='1'  WHERE email='".$email."'");
  }
  else
  {
    $upresult=$DB->query("update ".$db_prefix."user set photo='".$weibo_photo."', weibo_user_id='".$weibo_uid."', weibo_access_token='".$_SESSION['last_wkey']['oauth_token']."', weibo_access_token_secret='".$_SESSION['last_wkey']['oauth_token_secret']."', activate='1' WHERE email='".$email."'");
  }
  $result = $DB->fetch_one_array("select id, username from ".$db_prefix."user WHERE weibo_user_id='".$weibo_uid."' AND email='".$email."'");
  $_SESSION['uid']=intval($result['id']);
  $_SESSION['username']=$result['username'];
}
else
{
  $weibo_uid = $_POST['weibo_uid'];
  $weibo_photo = $_POST['weibo_photo'];
  $email = $_POST['user_email'];
  $user_name = $_POST['user_name'];
  $pwd = sha1(trim($_POST["user_pwd"]));
  $pwd_confirm = $_POST['user_pwd_confirm'];
  
  $register_time=date("Y-m-d H:i:s");
  $DB->query("insert into ".$db_prefix."user values
                         (null, '".$user_name."', '".$pwd."', '".$email."', '', '".$weibo_photo."', '', '".$weibo_uid."', '".$_SESSION['last_wkey']['oauth_token']."', '".$_SESSION['last_wkey']['oauth_token_secret']."', 0, '', '', 0, '', '', '', '".$register_time."', 1)");
  $_SESSION['username']=$user_name;
  $userresult = $DB->fetch_one_array("select id from ".$db_prefix."user WHERE weibo_user_id='".$weibo_uid."'");
  if(!empty($userresult))
  {
    $_SESSION['uid']=intval($userresult['id']);
  }
}
header("location: /tour"); 
?>
