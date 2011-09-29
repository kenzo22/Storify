<?php
require_once "../connect_db.php";
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

$result=$DB->query("update ".$db_prefix."user set douban_user_id='".$douban_uid."', douban_access_token='".$_SESSION['last_dkey']['oauth_token']."', douban_access_token_secret='".$_SESSION['last_dkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");

header("location: ../member/source.php"); 
exit;
?>

