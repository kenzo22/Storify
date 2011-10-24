<?php
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
include '../include/secureGlobals.php';
session_start();

$operation=$_POST['operation'];
$weibo_content = $_POST['weibo_content'];

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );

if('publish' == $operation)
{
  $c->update($weibo_content);
}
else if('comment' == $operation)
{
  $weibo_id = $_POST['id'];
  $c->send_comment($weibo_id, $weibo_content);
}
else if('repost' == $operation)
{
  $weibo_id = $_POST['id'];
  $c->repost($weibo_id, $weibo_content);
}

?>
