<?php
require_once "../connect_db.php";
$story_content = '';
$result=$DB->query("SELECT * FROM ".$db_prefix."posts ORDER BY RAND() limit 4");
while ($story_item = mysql_fetch_array($result))
{
  $post_author = $story_item['post_author'];
  $post_pic_url = $story_item['post_pic_url'];
  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
  $user_profile_img = $userresult['photo'];
  $post_title = $story_item['post_title'];
  $post_date = $story_item['post_date'];
  $temp_array = explode(" ", $story_item['post_date']);
  $post_date = $temp_array[0];
  $story_content .= "<li><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='member/user.php?user_id=".$post_author."&post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='story_meta' 
  ><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;' href='member/user.php?user_id=".$post_author."'>".$userresult['username']."</a><a style='float:right; vertical-align:top;'>".$post_date."</a></span></div></li>";
}
echo $story_content;
?>