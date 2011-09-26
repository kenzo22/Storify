<?php
include_once( 'config.php' );
include_once( 'txwboauth.php' );
session_start();

$weibo_content = $_POST['weibo_content'];

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );

$c->t_add($weibo_content);


?>