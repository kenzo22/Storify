<?php
@header('Content-Type:text/html;charset=utf-8');
include "../connect_db.php";
session_start();
require_once( '../tweibo/config.php' );
require_once('../tweibo/txwboauth.php');

$operation = $_POST['operation'];
if($operation == 'add')
{
  $t = new TWeiboOAuth( MB_AKEY , MB_SKEY  );
  $tkeys = $t->getRequestToken('http://koulifang.com/tweibo/callback.php');//这里的*********************填上你的回调URL
  $turl = $t->getAuthorizeURL( $tkeys['oauth_token'] ,false,'');

  $_SESSION['tkeys'] = $tkeys;
  echo $turl;
}
else
{
  $result=$DB->query("update ".$db_prefix."user set tweibo_user_id='0', tweibo_access_token='', tweibo_access_token_secret='' WHERE id='".$_SESSION['uid']."'");
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新腾讯微博设置成功</span></div>";
}

?>
