<?php
include "../global.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );

$o = new DoubanOAuth( DB_AKEY , DB_SKEY , $_SESSION['dkeys']['oauth_token'] , $_SESSION['dkeys']['oauth_token_secret']  );

$last_dkey = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
$_SESSION['last_dkey'] = $last_dkey;
$douban_uid;
$accessToken = $_SESSION['last_dkey']['oauth_token'];
$accessTokenSecret = $_SESSION['last_dkey']['oauth_token_secret'];

$c = new DoubanClient( DB_AKEY , 
                      DB_SKEY , 
                      $accessToken , 
                      $accessTokenSecret);
					  

$msg = $c->verify_credentials();
$douban_uid = $msg['db:uid']['$t'];

/*$msg = $c->verify_credentials();
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
}*/

$result=$DB->query("update ".$db_prefix."user set douban_user_id='".$douban_uid."', douban_access_token='".$_SESSION['last_dkey']['oauth_token']."', douban_access_token_secret='".$_SESSION['last_dkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");

header("location: ../member/source.php"); 
?>
<?php include "../include/footer.htm"; ?>
