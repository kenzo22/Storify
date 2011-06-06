<?php
//include "../global.php";
include "../config/global_config.php";
require_once "../connect_db.php";
require_once "../include/functions.php";
session_start();

$story_title=$_POST['story_title'];
$story_summary=$_POST['story_summary'];
$weibo_author=$_POST['weibo_author'];
$weibo_content=$_POST['weibo_content'];
$weibo_date=$_POST['weibo_date'];
$weibo_photo=$_POST['weibo_photo'];
$weibo_from_id=$_POST['weibo_from_id'];

//save the story information in the story_post table
$pulish_time=date("Y-m-d H:i:s");
$DB->query("insert into ".$db_prefix."posts values
                         (null, '".$_SESSION['uid']."', '".$pulish_time."', '".$pulish_time."', '".$story_title."', '".$story_summary."', '".published."', '".$pulish_time."', '".$pulish_time."')");
//end save the story information in the story_post table

//get the post_id
$result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."' AND post_date='".$pulish_time."'" );

$post_id = intval($result['ID']);
$weibo_type = "normal";
			  
for($i=0; $i<sizeof($weibo_author); $i++)
{
  $result = $DB->query("insert into ".$db_prefix."weibo values
                         (null, '".$post_id."', '".$weibo_author[$i]."', '".$weibo_photo[$i]."', '".$weibo_date[$i]."', '".$weibo_date[$i]."', '".$weibo_content[$i]."', '".$weibo_type."', '".$weibo_from_id[$i]."')");
}

$redirect_url = "/storify/member/user.php?post_id=".$post_id;
echo $redirect_url;

?>