<?php
require_once "../connect_db.php";
session_start();
if(isset($_POST['email']))
{
  $weibo_uid = $_POST['weibo_uid'];
  $email = $_POST['email'];
  $pwd = md5(trim($_POST["pwd"]));
  $userresult = $DB->fetch_one_array("select weibo_access_token, weibo_access_token_secret from ".$db_prefix."user WHERE weibo_user_id='".$weibo_uid."'");
  $DB->query("delete from ".$db_prefix."user where weibo_user_id='".$weibo_uid."' AND passwd=''");
  $upresult=$DB->query("update ".$db_prefix."user set weibo_user_id='".$weibo_uid."', weibo_access_token='".$userresult['weibo_access_token']."', weibo_access_token_secret='".$userresult['weibo_access_token_secret']."'  WHERE email='".$email."'");
}
else
{
  $weibo_uid = $_POST['weibo_uid'];
  $email = $_POST['user_email'];
  $user_name = $_POST['user_name'];
  $pwd = md5(trim($_POST["user_pwd"]));
  $pwd_confirm = $_POST['user_pwd_confirm'];
  $upresult=$DB->query("update ".$db_prefix."user set username='".$user_name."', passwd='".$pwd."', email='".$email."'  WHERE weibo_user_id='".$weibo_uid."'");
}

header("location: /index.php"); 
exit;
?>
