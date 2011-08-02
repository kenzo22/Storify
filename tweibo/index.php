<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );



$o = new TWeiboOAuth( MB_AKEY , MB_SKEY  );

$tkeys = $o->getRequestToken('http://story.com/t/callback.php');//这里填上你的回调URL

$aurl = $o->getAuthorizeURL( $tkeys['oauth_token'] ,false,'');

$_SESSION['tkeys'] = $tkeys;


?>
<a href="<?php echo $aurl?>">用OAUTH授权登录 腾讯微博SDK php版 演示</a>