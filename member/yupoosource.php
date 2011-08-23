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
  echo "<div class='modify_notify' style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新又拍社区设置成功</span></div>";
}
?>
