<?php
include "../global.php"; 
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );


$o = new TWeiboOAuth( MB_AKEY , MB_SKEY , $_SESSION['tkeys']['oauth_token'] , $_SESSION['tkeys']['oauth_token_secret']  );

$last_tkey = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;//获取ACCESSTOKEN

$_SESSION['last_tkey'] = $last_tkey;

//$taccessToken = $_SESSION['last_tkey']['oauth_token'];
//$taccessTokenSecret = $_SESSION['last_tkey']['oauth_token_secret'];

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
$ms  =  $c->getinfo();
$user = $ms[data];

$result=$DB->query("update ".$db_prefix."user set tweibo_user_id='".$user[Uid]."', tweibo_access_token='".$_SESSION['last_tkey']['oauth_token']."', tweibo_access_token_secret='".$_SESSION['last_tkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");


?>
<!--授权完成,<a href="demo.html">进入SDK测试用例</a>-->
<div class='inner' style='padding-top:50px;'>
  <div><a href="../member/source.php">添加其他源</a></div>
  <div><a href="../member/index.php">暂不添加其他源，马上体验口立方</a></div>
</div>
