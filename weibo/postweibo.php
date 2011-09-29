<?php
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
session_start();

$weibo_content = $_POST['weibo_content'];

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );

$c->update($weibo_content);

?>