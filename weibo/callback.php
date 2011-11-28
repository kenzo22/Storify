<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
include_once 'config.php';
include_once 'sinaweibo.php';

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
	$profile_img_url = $msg['profile_image_url'];
}

$userresult=$DB->fetch_one_array("SELECT weibo_access_token,photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if($userresult['weibo_access_token'] == '')
{
    $photo = $userresult['photo']?$userresult['photo']:$profile_img_url;
}
else
{
    if(substr($userresult['photo'],0,1) == '/')
        $photo = $userresult['photo'];
    else
        $photo = $profile_img_url;
}
    $result=$DB->query("update ".$db_prefix."user set photo='".$photo."', weibo_user_id='".$weibo_uid."', weibo_access_token='".$_SESSION['last_wkey']['oauth_token']."', weibo_access_token_secret='".$_SESSION['last_wkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");


header("location: /accounts/source"); 
exit;
?>


