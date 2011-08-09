<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( 'config.php' );
include_once( 'yupoo.php' );

$y = new YupooAPI( YB_AKEY , YB_SKEY);

$frob = $y->get_frob();
?>