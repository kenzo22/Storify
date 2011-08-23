<?php
@header('Content-Type:text/html;charset=utf-8');
include "../connect_db.php";
session_start();
require_once( '../yupoo/config.php' );
require_once('../yupoo/yupoo.php');

$operation = $_POST['operation'];
if($operation == 'add')
{
  $y = new YupooAPI( YB_AKEY , YB_SKEY);
  $yurl = $y->generate_authurl();
  echo $yurl;
}
else
{
  $result=$DB->query("update ".$db_prefix."user set yupoo_token='' WHERE id='".$_SESSION['uid']."'");
}
?>
