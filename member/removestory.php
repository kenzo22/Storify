<?php
require_once '../include/user_auth_fns.php';
include '../include/secureGlobals.php';
session_start();

if(!islogin())
{
  exit;
}
$user_id=$_POST['uid']; 
$post_id=$_POST['pid'];

$query = "SELECT COUNT(*) as num FROM ".$db_prefix."posts where ID='".$post_id."' and post_author=".$user_id;
$count = mysql_fetch_array(mysql_query($query));
$count = $count['num'];

if($count!=0)
{
  $query="select tag_id from ".$db_prefix."tag_story where story_id=".$post_id;
  $results=$DB->query($query);

  $query="delete from ".$db_prefix."tag_story where story_id=".$post_id;
  $DB->query($query);

  $query="delete from ".$db_prefix."pageview where story_id=".$post_id;
  $DB->query($query);

  while($item=$DB->fetch_array($results))
  {
    $query="select * from ".$db_prefix."tag_story where tag_id=".$item['tag_id'];
    $res=$DB->query($query);
    if($DB->num_rows($res) == 0)
    {
      $query="delete from ".$db_prefix."tag where id=".$item['tag_id'];
      $DB->query($query);
    }
  }

  $result=$DB->query("DELETE FROM ".$db_prefix."posts where ID='".$post_id."'");
  echo $_SESSION['uid'];
}
?>
