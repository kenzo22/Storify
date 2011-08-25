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

$userresult=$DB->fetch_one_array("SELECT weibo_access_token FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if($userresult['weibo_access_token'] == '')
{
  $profileImgUrl = $user['head']."/50";
  $result=$DB->query("update ".$db_prefix."user set photo='".$profileImgUrl."', tweibo_user_id='".$user[Uid]."', tweibo_access_token='".$_SESSION['last_tkey']['oauth_token']."', tweibo_access_token_secret='".$_SESSION['last_tkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");
}
else
{
  $result=$DB->query("update ".$db_prefix."user set tweibo_user_id='".$user[Uid]."', tweibo_access_token='".$_SESSION['last_tkey']['oauth_token']."', tweibo_access_token_secret='".$_SESSION['last_tkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");
}
header("location: ../member/source.php"); 
?>
