<?php
require_once "../connect_db.php";
require_once "../include/functions.php";
require_once "../include/user_auth_fns.php";
include '../include/secureGlobals.php';
session_start();

$post_id=intval($_POST['post_id']);
$first=intval($_POST['first']);
$limit = 10;
$date_t = date("Y-m-d H:i:s");

$userresult = $DB->fetch_one_array("SELECT post_author FROM ".$db_prefix."posts where ID='".$post_id."'");
if($userresult)
{
  $user_id = $userresult['post_author'];
}
$login_status = islogin();
if($login_status && $user_id == $_SESSION['uid'])
{
  $self_flag = true;
}
else
{
  $self_flag = false;
}

$query="select COUNT(*) as num from ".$db_prefix."comments where comment_post_id =".$post_id." and comment_id <".$first;
$comment_result = mysql_fetch_array(mysql_query($query));
$comment_count = $comment_result[num];

$sql="select * from ".$db_prefix."comments where comment_post_id=".$post_id." and comment_id < $first order by comment_id desc limit $limit";

$result = mysql_query($sql);
$content = '';

if($self_flag || !$login_status)
{
  if($self_flag)
  {
	$comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a> | <a href='#' class='del_comment'>删除</a></span>";
  }
  else
  {
	$comment_action = '';
  }
  while ($item = mysql_fetch_array($result))
  {
	$comment_id = $item['comment_id'];
	$pic_url = $item['comment_author_pic'];
	if($pic_url == '')
	{
	  $pic_url = '/img/douban_user_dft.jpg';
	}
	$comment_author = $item['comment_author'];
	$comment_author_id = $item['user_id'];
	$comment_time = dateFormatTrans($item['comment_date'],$date_t);
	$comment_content = nl2br($item['comment_content']);
	$content.="<li id='comment_".$comment_author_id."_".$comment_id."'>
		   <a href='/user/".$comment_author_id."' target='_blank'><img alt='' src='".$pic_url."' /></a>
		   <div class='comment_wrapper'>
			 <div class='comment_author'><a href='/user/".$comment_author_id."' target='_blank'>".$comment_author."</a></div>
			 <div>".$comment_content."</div>
			 <div class='comment_action'>".$comment_action."<span>".$comment_time."</span></div>
		   </div>
		 </li>";
  }
}
else
{
  while ($item = mysql_fetch_array($result))
  {
	$comment_id = $item['comment_id'];
	$pic_url = $item['comment_author_pic'];
	if($pic_url == '')
	{
	  $pic_url = '/img/douban_user_dft.jpg';
	}
	$comment_author = $item['comment_author'];
	$comment_author_id = $item['user_id'];
	if(0 == strcmp($comment_author_id, $_SESSION['uid']))
	{
	  $comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a> | <a href='#' class='del_comment'>删除</a></span>";
	}
	else
	{
	  $comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a></span>";
	}
	$comment_time = dateFormatTrans($item['comment_date'],$date_t);
	$comment_content = nl2br($item['comment_content']);
	$content.="<li id='comment_".$comment_author_id."_".$comment_id."'>
		   <a href='/user/".$comment_author_id."' target='_blank'><img alt='' src='".$pic_url."' /></a>
		   <div class='comment_wrapper'>
			 <div class='comment_author'><a href='/user/".$comment_author_id."' target='_blank'>".$comment_author."</a></div>
			 <div>".$comment_content."</div>
			 <div class='comment_action'>".$comment_action."<span>".$comment_time."</span></div>
		   </div>
		 </li>";
  }
}

if($comment_count > $limit)
{
  $content .="<li><a id='more_comments_".$post_id."_".$comment_id."' class='load_more'>更多评论</a></li>";
}
echo $content;
?>
