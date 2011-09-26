<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( 'config.php' );
include_once( 'yupoo.php' );

$y = new YupooAPI( YB_AKEY , YB_SKEY);

$frob = $y->get_frob();

$token = $y->get_token($frob);

echo "<br/><br/>frob".$frob;
echo "<br/><br/>token".$token;


//$picData  = $y->search_photo('哈哈哈哈哈哈哈哈哈哈', 1, $_SESSION['yupoo_token']);
//$userdata = $y->get_userid_by_name('kenzo22');
//var_dump($userdata);


//$picData  = $y->search_user('2496568', 1, $_SESSION['yupoo_token']);
//var_dump($picData);
?>