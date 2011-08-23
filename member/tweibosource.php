<?php
@header('Content-Type:text/html;charset=utf-8');
session_start();
require_once( '../tweibo/config.php' );
require_once('../tweibo/txwboauth.php');

$t = new TWeiboOAuth( MB_AKEY , MB_SKEY  );
$tkeys = $t->getRequestToken('http://story.com/storify/tweibo/callback.php');//这里的*********************填上你的回调URL
$turl = $t->getAuthorizeURL( $tkeys['oauth_token'] ,false,'');

$_SESSION['tkeys'] = $tkeys;
echo $turl;
?>
