<?php
@header('Content-Type:text/html;charset=utf-8');
include $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
require_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/sinaweibo.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$wkeys = $o->getRequestToken();
$aurl = $o->getAuthorizeURL( $wkeys['oauth_token'] ,false , 'http://koulifang.com/accounts/weibo_login');
$_SESSION['wkeys'] = $wkeys;
echo $aurl;
?>

