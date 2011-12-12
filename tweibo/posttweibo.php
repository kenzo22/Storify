<?php
include_once( 'config.php' );
include_once( 'txwboauth.php' );
include '../include/secureGlobals.php';
session_start();

$operation=$_POST['operation'];

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );


if('publish' == $operation)
{
  $tweibo_content = $_POST['weibo_content'];
  $c->t_add($tweibo_content);
}
else if('comment' == $operation)
{
  $tweibo_id = $_POST['id'];
  $tweibo_content = $_POST['weibo_content'];
  $c->t_comment($tweibo_id, $tweibo_content);
}
else if('repost' == $operation)
{
  $tweibo_id = $_POST['id'];
  $tweibo_content = $_POST['weibo_content'];
  $c->t_re_add($tweibo_id, $tweibo_content); 
}
else if('add_fav' == $operation)
{
  $tweibo_id = $_POST['id'];
  $c->fav_add_t($tweibo_id);
}
else if('del_fav' == $operation)
{
  $tweibo_id = $_POST['id'];
  $c->fav_del_t($tweibo_id);
}

?>
