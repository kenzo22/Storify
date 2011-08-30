<?php
include "../global.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );

$result=$DB->fetch_one_array("SELECT douban_access_token, douban_access_token_secret FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
$_SESSION['last_dkey']['oauth_token']=$result['douban_access_token'];
$_SESSION['last_dkey']['oauth_token_secret']=$result['douban_access_token_secret'];

$douban_uid;
$accessToken = $_SESSION['last_dkey']['oauth_token'];
$accessTokenSecret = $_SESSION['last_dkey']['oauth_token_secret'];

$c = new DoubanClient( DB_AKEY , DB_SKEY , $accessToken , $accessTokenSecret);
					  
$msg1 = $c->verify_credentials();

$msg2 = $c->get_user();
echo "<br/><br/><br/><br/><br/>";
var_dump($msg1);
//$temp = $c->last_status();
//echo "text".$temp;

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
?>
<?php include "../include/footer.htm"; ?>

