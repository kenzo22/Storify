<?php
@header('Content-Type:text/html;charset=utf-8');
include "../connect_db.php";
session_start();
require_once( '../weibo/config.php' );
require_once( '../weibo/sinaweibo.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$wkeys = $o->getRequestToken();
$aurl = $o->getAuthorizeURL( $wkeys['oauth_token'] ,false , 'http://koulifang.com/login/weibo_login.php');
$_SESSION['wkeys'] = $wkeys;
echo $aurl;
?>

