<?php
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
include '../include/secureGlobals.php';
session_start();

$operation=$_POST['operation'];

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );

if('publish' == $operation)
{
  $weibo_content = $_POST['weibo_content'];
  $c->update($weibo_content);
}
else if('comment' == $operation)
{
  $weibo_id = $_POST['id'];
  $weibo_content = $_POST['weibo_content'];
  $c->send_comment($weibo_id, $weibo_content);
}
else if('repost' == $operation)
{
  $weibo_id = $_POST['id'];
  $weibo_content = $_POST['weibo_content'];
  $c->repost($weibo_id, $weibo_content);
}
else if('add_fav' == $operation)
{
  $weibo_id = $_POST['id'];
  $c->add_to_favorites($weibo_id);
}
else if('del_fav' == $operation)
{
  $weibo_id = $_POST['id'];
  $c->remove_from_favorites($weibo_id);
}

?>
