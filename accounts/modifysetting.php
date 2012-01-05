<?php
include "../connect_db.php";
include "../include/functions.php";
include '../include/secureGlobals.php';
//require $_SERVER['DOCUMENT_ROOT'].'/include/secureCommon.php';
session_start();
$uid=intval($_SESSION['uid']);

$search = array ("'<script[^>]*?>.*?</script>'si","'<head[^>]*?>.*?</head>'si");
$intro=preg_replace($search,"",trim($_POST['userintro']));
$user_name = trim($_POST['username']);
//$user_name = strip_tags(trim($_POST['username']));
//$intro = strip_tags(trim($_POST['userintro']));
$DB->query("update ".$db_prefix."user set username='".$user_name."', intro='".$intro."' where id=".$uid);
$userresult = $DB->fetch_one_array("SELECT email FROM ".$db_prefix."user where id='".$uid."'");
$email = $userresult['email'];
$DB->query("update ".$db_prefix."reset set username='".$user_name."' where email='".$email."'");
echo "<div style='width:68%; text-align:center; background-color: #FFF6EE;' class='update_notify'><span>更新设置成功</span></div>"
?>
