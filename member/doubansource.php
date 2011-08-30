<?php
@header('Content-Type:text/html;charset=utf-8');
include "../connect_db.php";
session_start();
require_once( '../douban/config.php' );
require_once( '../douban/doubanapi.php' );

$operation = $_POST['operation'];
if($operation == 'add')
{
  $d = new DoubanOAuth( DB_AKEY , DB_SKEY  );
  $dkeys = $d->getRequestToken();
  $durl = $d->getAuthorizeURL( $dkeys['oauth_token'] ,false , 'http://story.com/storify/douban/callback.php');
  $_SESSION['dkeys'] = $dkeys;
  echo $durl;
}
else
{
  $result=$DB->query("update ".$db_prefix."user set douban_user_id='0', douban_access_token='', douban_access_token_secret='' WHERE id='".$_SESSION['uid']."'");
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新豆瓣社区设置成功</span></div>";
}
?>

