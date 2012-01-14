<?php
require_once '../include/user_auth_fns.php';
require '../include/secureGlobals.php';
session_start();

if(!islogin())
{
  exit;
}

$user_id=intval($_POST['uid']);
$comment_id=intval($_POST['cid']);

$query = "SELECT COUNT(*) as num FROM ".$db_prefix."comments where comment_id='".$comment_id."' and user_id=".$user_id;
$count = mysql_fetch_array(mysql_query($query));
$count = $count['num'];

if($count!=0)
{
  $query="delete from ".$db_prefix."comments where comment_id=".$comment_id;
  $DB->query($query);
}

?>
