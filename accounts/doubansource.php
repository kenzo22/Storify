<?php
@header('Content-Type:text/html;charset=utf-8');
include $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/douban/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/douban/doubanapi.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

$operation = $_POST['operation'];
if($operation == 'add')
{
  $d = new DoubanOAuth( DB_AKEY , DB_SKEY  );
  $dkeys = $d->getRequestToken();
  $durl = $d->getAuthorizeURL( $dkeys['oauth_token'] ,false , 'http://koulifang.com/douban/callback');
  $_SESSION['dkeys'] = $dkeys;
  echo $durl;
}
else
{
  $result=$DB->query("update ".$db_prefix."user set douban_user_id='0', douban_access_token='', douban_access_token_secret='' WHERE id='".$_SESSION['uid']."'");
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新豆瓣社区设置成功</span></div>";
}
?>

