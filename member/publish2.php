<?php
//include "../global.php";
require_once "../connect_db.php";
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
//$result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."'" );

$post_id = intval($result['ID']);
//$post_id="33";
$weibo_type = "normal";

$content = "<div id='publish_container' class='showborder'>
			  <div style='padding-left:20px;'><h2>".$story_title."</h2></div>
			  <div style='padding-left:20px;'>".$_SESSION['username']."</div>
			  <div style='padding-left:20px; border-bottom:1px solid #C9C9C9;'>".$story_summary."</div>
			  <ul style='padding:0;'>";
for($i=0; $i<sizeof($weibo_author); $i++)
{
  $result = $DB->query("insert into ".$db_prefix."weibo values
                         (null, '".$post_id."', '".$weibo_author[$i]."', '".$weibo_photo[$i]."', '".$weibo_date[$i]."', '".$weibo_date[$i]."', '".$weibo_content[$i]."', '".$weibo_type."', '".$weibo_from_id[$i]."')");

  $content .= "<li class='weibo_drop'>
		<div class='story_wrapper'>
		  <div><span class='weibo_text'>".$weibo_content[$i]."</span></div>
		  <div id='story_signature'>
		    <div style='float:right;'>
			  <a href='http://weibo.com/".$weibo_from_id[$i]."' target='_blank'>
			    <img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$weibo_photo[$i]."' alt='".$weibo_author[$i]."' border=0 />
			  </a>
			</div>
			<div id='signature_text' style='float:right; margin-right:5px;'>
		      <a class='weibo_from' href='http://weibo.com/".$weibo_from_id[$i]."' target='_blank' style='display:block; height:16px;'><span>".$weibo_author[$i]."</span></a>
			  <span class='weibo_date' style='display:block; height:16px;'>".$weibo_date[$i]."</span>
			</div>
		  </div>
		</div>
		</li>";
}
$content .="</ul><div style='display: block; padding:0 10px 0 5px; text-align:right;'>Powered by <a name='poweredby' target='_blank' href='http://storybing.com'>StoryBing</a></div></div>";	
echo $content;

?>