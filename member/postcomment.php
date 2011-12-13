<?php
require_once "../connect_db.php";
include '../include/secureGlobals.php';
include '../include/user_auth_fns.php';
session_start();

$user_id=$_GET['user_id'];
$post_id=$_GET['post_id'];
$comment_content=$_GET['comment_content'];
$comment_time=date("Y-m-d H:i:s");

$userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$user_id."'");
if($userresult)
{
  $comment_author_name = $userresult['username'];
  $comment_author_pic = $userresult['photo'];
}
else
{
  $comment_author_name = '';
  $comment_author_pic = '';
}

$DB->query("insert into ".$db_prefix."comments values(null, '".$post_id."', '".$comment_author_name."', '".$comment_author_pic."', '".$comment_time."', '".$comment_time."', '".$comment_content."', '".$user_id."')");
$score = getPopularScore($post_id);
$result=$DB->query("update ".$db_prefix."posts set popular_count='".$score."'  WHERE ID='".$post_id."'");
$commentresult = $DB->fetch_one_array("select comment_id, comment_content from ".$db_prefix."comments WHERE comment_content='".$comment_content."' and comment_date='".$comment_time."'");
if($commentresult)
{
  $comment_id = $commentresult['comment_id'];
  $comment_content = nl2br($commentresult['comment_content']);
}

$content ="<li id='comment_".$comment_id."' style='display:none;'>
			  <a class='float_l' href='/user/".$user_id."' target='_blank'><img alt='' src='".$comment_author_pic."' /></a>
			  <div class='comment_wrapper'>
			    <div class='comment_author'><a href='/user/".$user_id."' target='_blank'>".$comment_author_name."</a></div>
				<div>".$comment_content."</div>
				<div class='comment_action'><span class='float_r'><a href='#' class='reply_comment'>回复</a> | <a href='#' class='del_comment'>删除</a></span><span>一分钟前</span></div>
			  </div>
			</li>";
echo $content;
?>
