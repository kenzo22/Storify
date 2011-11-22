<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/config.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/sinaweibo.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['wkeys']['oauth_token'] , $_SESSION['wkeys']['oauth_token_secret']  );

$last_wkey = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
$_SESSION['last_wkey'] = $last_wkey;
$weibo_uid;
$accessToken = $_SESSION['last_wkey']['oauth_token'];
$accessTokenSecret = $_SESSION['last_wkey']['oauth_token_secret'];

$c = new WeiboClient( WB_AKEY , 
                      WB_SKEY , 
                      $accessToken , 
                      $accessTokenSecret);

$msg = $c->verify_credentials();
if ($msg === false || $msg === null){
	echo "Error occured";
	return false;
}
if (isset($msg['error_code']) && isset($msg['error'])){
	echo ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
	return false;
}
if (isset($msg['id'])){
	$weibo_uid = $msg['id'];
	$weibo_nick = $msg['screen_name'];
	$photo = $msg['profile_image_url'];
}

$result = $DB->fetch_one_array("select * from ".$db_prefix."user where weibo_user_id='".$weibo_uid."'");
if(empty($result))
{
  header("location: /accounts/login/associate_form.php"); 
  exit;
}
else
{
  $_SESSION['uid']=intval($result['id']);
  $_SESSION['username']=$result['username'];
  
  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	  
  if($result['tweibo_access_token'] == '')
  {
	$_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
	$_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
  }
  else
  {
	$_SESSION['last_tkey']['oauth_token']=$result['tweibo_access_token'];
	$_SESSION['last_tkey']['oauth_token_secret']=$result['tweibo_access_token_secret'];
  }
  
  $_SESSION['last_dkey']['oauth_token']=$result['douban_access_token'];
  $_SESSION['last_dkey']['oauth_token_secret']=$result['douban_access_token_secret'];
  $_SESSION['yupoo_token'] = $result['yupoo_token'];
  
  header("location: /"); 
  exit;	
}
?>
