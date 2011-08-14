<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
$uid=intval($_SESSION['uid']);

$search = array ("'<script[^>]*?>.*?</script>'si","'<head[^>]*?>.*?</head>'si");
$intro=addslashes(preg_replace($search,"",trim($_POST['userintro'])));
$user_name = $_POST['username'];
$DB->query("update ".$db_prefix."user set username='".$user_name."', intro='".$intro."' where id=".$uid);
echo "<div style='width:100%; text-align:center; background-color: #FFF6EE;'><span>更新设置成功</span></div>"
?>