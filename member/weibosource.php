<?php
@header('Content-Type:text/html;charset=utf-8');
include "../connect_db.php";
session_start();
require_once( '../weibo/config.php' );
require_once( '../weibo/sinaweibo.php' );

$operation = $_POST['operation'];
if($operation == 'add')
{
  $o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

  $keys = $o->getRequestToken();
  //$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['SCRIPT_URI'].'/callback.php');
  //$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['REQUEST_URI'].'/callback.php');
  $aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , 'http://koulifang.com/weibo/callback.php');
  $_SESSION['keys'] = $keys;
  echo $aurl;
}
else
{
  $result=$DB->query("update ".$db_prefix."user set weibo_user_id='0', weibo_access_token='', weibo_access_token_secret='' WHERE id='".$_SESSION['uid']."'");
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新新浪微博设置成功</span></div>";
}
?>

