<?php
include "../config/global_config.php";
require_once "../connect_db.php";
require_once "../include/functions.php";
session_start();

$story_id=$_POST['story_id'];
$story_title=$_POST['story_title'];
$story_summary=$_POST['story_summary'];
$story_tag=$_POST['story_tag'];
$story_pic=$_POST['story_pic'];
$story_content=$_POST['story_content'];

$pulish_time=date("Y-m-d H:i:s");
$post_id = $story_id;
$weibo_type = "normal";
if(0 == $story_id)
{
  $DB->query("insert into ".$db_prefix."posts values
                         (null, '".$_SESSION['uid']."', '".$pulish_time."', '".$pulish_time."', '".$story_title."', '".$story_summary."', '".$story_pic."', '".$story_content."', '".Draft."', '".$pulish_time."', '".$pulish_time."')");
  $result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."' AND post_date='".$pulish_time."'" );
  $post_id = intval($result['ID']); 
}
else
{
  $result=$DB->query("update ".$db_prefix."posts set post_title='".$story_title."', post_summary='".$story_summary."', post_pic_url='".$story_pic."', post_content='".$story_content."', post_status='Draft',
  post_modified='".$pulish_time."', post_modified_gmt='".$pulish_time."' WHERE ID='".$post_id."'");
}

$redirect_url = "/storify/member/user.php?post_id=".$post_id;
echo $redirect_url;

?>
