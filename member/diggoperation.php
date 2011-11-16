<?php
require_once "../connect_db.php";
include '../include/secureGlobals.php';
session_start();

$post_id=$_GET['post_id'];
$cookie_sid="";

if(!empty($_COOKIE['votesid']))
{
  $cookie_sid=split(',',$_COOKIE['votesid']);
  if(in_array($post_id,$cookie_sid))
  {
	echo 0;
	//exit();
  }
  else
  {
    $result=$DB->query("update ".$db_prefix."posts set post_digg_count=post_digg_count+1  WHERE ID='".$post_id."'");
    $cookie_sid[]=$post_id;
    $cookie_sid=join(',',$cookie_sid);
    setcookie("votesid",$cookie_sid,time()+3600*4);
    echo 1;
  }   
}
else
{
  $result=$DB->query("update ".$db_prefix."posts set post_digg_count=post_digg_count+1  WHERE ID='".$post_id."'");
  $cookie_sid=$post_id;
  setcookie("votesid",$cookie_sid,time()+3600*4);
  echo 1;
}

?> 
