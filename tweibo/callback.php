<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
include_once 'config.php';
include_once 'txwboauth.php';


$o = new TWeiboOAuth( MB_AKEY , MB_SKEY , $_SESSION['tkeys']['oauth_token'] , $_SESSION['tkeys']['oauth_token_secret']  );

$last_tkey = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;//获取ACCESSTOKEN

$_SESSION['last_tkey'] = $last_tkey;

//$taccessToken = $_SESSION['last_tkey']['oauth_token'];
//$taccessTokenSecret = $_SESSION['last_tkey']['oauth_token_secret'];

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
$ms  =  $c->getinfo();
$user = $ms[data];

$userresult=$DB->fetch_one_array("SELECT tweibo_access_token,photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if($userresult['tweibo_access_token'] == '')
{
  $photo = $userresult['photo']?$userresult['photo']:$user['head']."/50";
}
else
{
    if(substr($userresult['photo'],0,1) == '/')
        $photo = $userresult['photo'];
    else
        $photo = $profile_img_url;
}
  $result=$DB->query("update ".$db_prefix."user set photo='".$photo."', tweibo_user_id='".$user[Uid]."', tweibo_access_token='".$_SESSION['last_tkey']['oauth_token']."', tweibo_access_token_secret='".$_SESSION['last_tkey']['oauth_token_secret']."' WHERE id='".$_SESSION['uid']."'");

header("location: /accounts/source"); 
exit;
?>
