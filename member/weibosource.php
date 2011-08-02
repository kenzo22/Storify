<?php
@header('Content-Type:text/html;charset=utf-8');
session_start();
require_once( '../weibo/config.php' );
require_once( '../weibo/sinaweibo.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();
//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['SCRIPT_URI'].'/callback.php');
//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['REQUEST_URI'].'/callback.php');

$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , 'http://story.com/storify/weibo/callback.php');

$_SESSION['keys'] = $keys;
echo $aurl;
?>

