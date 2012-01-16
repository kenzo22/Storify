<?php
require_once "../connect_db.php";
require_once "../include/functions.php";
include '../include/secureGlobals.php';
session_start();
$weibo_user_id=intval($_POST['weibo_user_id']);
$result=$DB->query("update ".$db_prefix."user set weibo_user_id='".$weibo_user_id."' WHERE id='".$_SESSION['uid']."'");
?>
