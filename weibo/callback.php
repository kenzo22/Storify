<?php
include "../global.php";
session_start();
include_once( 'config.php' );
//include_once( 'weibooauth.php' );
include_once( 'sinaweibo.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );

$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
$_SESSION['last_key'] = $last_key;
$weibo_uid;
$accessToken = $_SESSION['last_key']['oauth_token'];
$accessTokenSecret = $_SESSION['last_key']['oauth_token_secret'];

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
	$profile_img_url = $msg['profile_image_url'];
}

$userresult=$DB->fetch_one_array("SELECT tweibo_access_token FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if($userresult['tweibo_access_token'] == '')
{
  $result=$DB->query("update ".$db_prefix."user set photo='".$profile_img_url."', weibo_user_id='".$weibo_uid."', weibo_access_token='".$_SESSION['last_key']['oauth_token']."', weibo_access_token_secret='".$_SESSION['last_key']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");
}
else
{
  $result=$DB->query("update ".$db_prefix."user set weibo_user_id='".$weibo_uid."', weibo_access_token='".$_SESSION['last_key']['oauth_token']."', weibo_access_token_secret='".$_SESSION['last_key']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");
}

header("location: ../member/source.php"); 
?>
<?php include "../include/footer.htm"; ?>

