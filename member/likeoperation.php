<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/user_auth_fns.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
session_start();

if(!islogin())
{
  exit;
}

$operation = $_POST['operation'];
$user_id=intval($_POST['uid']); 
$post_id=intval($_POST['pid']);

echo "hello".$operation."uid".$user_id."pid".$post_id;

?>