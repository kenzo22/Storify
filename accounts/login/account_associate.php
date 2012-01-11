<?php
require $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
require $_SERVER['DOCUMENT_ROOT'].'/include/functions.php';

session_start();
$weibo_uid = $_POST['weibo_uid'];
if(!is_numeric($weibo_uid)){
    go("/accounts/associate_form","微博id不是数字！",5);
}
$weibo_photo = $_POST['weibo_photo'];
if(strpos($weibo_photo,' ') !== false | !preg_match('#http://#',$weibo_photo)){
    go("/accounts/associate_form","微博头像url出错",5);
}
if(isset($_POST['email']))
{
  $email = $_POST['email'];
   if(!is_email($email)){
        go("/accounts/associate_form","Email格式不正确，绕过前端验证",5);
    }
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
  $email = $_POST['user_email'];
   if(!is_email($email)){
        go("/accounts/associate_form","Email格式不正确，绕过前端验证",5);
    }
  $user_name = $_POST['user_name'];
    if(strpos($user_name,' ') !== false){
        go("/accounts/associate_form","用户名带空格？",5);
    }
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
