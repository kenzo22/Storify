<?php
require_once "../connect_db.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );

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
var_dump($msg);
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

$result = $DB->query("select * from ".$db_prefix."user where weibo_user_id='".$weibo_uid."'");
if(!$result)
{
  throw new Exception('Could not execute query.');
}
if ($DB->num_rows($result) == 0)
{
  $register_time=date("Y-m-d H:i:s");
  $DB->query("insert into ".$db_prefix."user values
                         (null, '".$weibo_nick."', '', '', '".$photo."', '', '".$weibo_uid."', '".$accessToken."', '".$accessTokenSecret."', 0, '', '', 0, '', '', '', '".$register_time."', 1)");
  $userresult=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user where weibo_user_id='".$weibo_uid."'");
  if(!$userresult)
  {
	throw new Exception('Could not execute query.');
  }
  if(!empty($result))
  {
    $_SESSION['uid']=intval($userresult['id']);
	$_SESSION['username']=$userresult['username'];
  }
  header("location: /login/account_associate.php"); 
  exit;
}
else if ($DB->num_rows($result) == 1)
{
    $userresult=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user where weibo_user_id='".$weibo_uid."'");
	if(!$userresult)
	{
	  throw new Exception('Could not execute query.');
	}
	if(!empty($result))
	{
	  $_SESSION['uid']=intval($userresult['id']);
	  $_SESSION['username']=$userresult['username'];
	  
	  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
		  
	  if($userresult['weibo_access_token'] == '')
	  {
		$_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
		$_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	  }
	  else
	  {
		$_SESSION['last_wkey']['oauth_token']=$userresult['weibo_access_token'];
		$_SESSION['last_wkey']['oauth_token_secret']=$userresult['weibo_access_token_secret'];
	  }
	  if($userresult['tweibo_access_token'] == '')
	  {
		$_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
		$_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
	  }
	  else
	  {
		$_SESSION['last_tkey']['oauth_token']=$userresult['tweibo_access_token'];
		$_SESSION['last_tkey']['oauth_token_secret']=$userresult['tweibo_access_token_secret'];
	  }
	  
	  $_SESSION['last_dkey']['oauth_token']=$userresult['douban_access_token'];
	  $_SESSION['last_dkey']['oauth_token_secret']=$userresult['douban_access_token_secret'];
	  $_SESSION['yupoo_token'] = $userresult['yupoo_token'];
	  
	  header("location: /index.php"); 
	  exit;
	}
}
?>