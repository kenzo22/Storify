<?php
require_once "../connect_db.php";
require_once "../include/functions.php";
include_once "../include/weibo_functions.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );

header("content-type: text/javascript");

if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['callback']))
{
	$obj->id = $_GET['id'];
	$obj->name = $_GET['name'];
	$obj->message = "Hello " . $obj->name;

	echo $_GET['callback']. '(' . json_encode($obj) . ');';
}
?>