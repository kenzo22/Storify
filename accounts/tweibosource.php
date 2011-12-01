<?php
@header('Content-Type:text/html;charset=utf-8');
include $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/tweibo/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tweibo/txwboauth.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

$operation = $_POST['operation'];
if($operation == 'add')
{
  $t = new TWeiboOAuth( MB_AKEY , MB_SKEY  );
  $tkeys = $t->getRequestToken('http://koulifang.com/tweibo/callback');//这里的*********************填上你的回调URL
  $turl = $t->getAuthorizeURL( $tkeys['oauth_token'] ,false,'');

  $_SESSION['tkeys'] = $tkeys;
  echo $turl;
}
elseif($operation == 'delete')
{
    $photoresult=$DB->fetch_one_array("SELECT photo FROM story_user WHERE id=".$_SESSION['uid']);
    if(strstr($photoresult['photo'],'qlogo'))
        $photo=NULL;
    else
        $photo=$photoresult['photo'];
  $result=$DB->query("update ".$db_prefix."user set photo='".$photo."', tweibo_user_id='0', tweibo_access_token='', tweibo_access_token_secret='' WHERE id='".$_SESSION['uid']."'");
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新腾讯微博设置成功</span></div>";
}

?>
