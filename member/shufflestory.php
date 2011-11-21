<?php
require_once "../connect_db.php";
$flag=$_GET['flag'];
$sequence=$_GET['sequence'];

$now_time=time();

switch ($flag)
{
case 0:
  $time_range = 3;
  break;
case 1:
  $time_range = 7;
  break;
case 2:
  $time_range = 30;
  break;
case 3:
  $time_range = 365;
  break;
default:
  $time_range = 3;
  break;
}

function show_content(&$content, $db_result)
{
  global $DB;
  while ($story_item = mysql_fetch_array($db_result))
  {
	$post_author = $story_item['post_author'];
	$post_pic_url = $story_item['post_pic_url'];
	$userresult = $DB->fetch_one_array("SELECT username, photo FROM story_user where id='".$post_author."'");
	$author_name = $userresult['username'];
	$user_profile_img = $userresult['photo'];
	if($user_profile_img == '')
	{
	  $user_profile_img = '/img/douban_user_dft.jpg';
	}
	$post_title = $story_item['post_title'];
	$post_date = $story_item['post_date'];
	$temp_array = explode(" ", $story_item['post_date']);
	$post_date = $temp_array[0];
	$post_link = "/member/user.php?user_id=".$post_author."&post_id=".$story_item['ID'];
	$content .=  "<li>
						  <div class='story_wrap'>	
							<a href='".$post_link."'>
							  <img class='cover' src='".$post_pic_url."' alt='故事封面' />
							</a>
							<a class='title_wrap' href='".$post_link."'>
							  <span class='title'>".$post_title."</span>
							</a>
						  </div>
						  <div class='story_meta'>
							<span>
							  <a class='meta_date'>".$post_date."</a>
							  <img src='".$user_profile_img."' alt='".$author_name."'/>
							  <a class='meta_author' href='member/user.php?user_id=".$post_author."'>".$author_name."</a>
							</span>
						  </div>
						</li>";
  }
}

$story_content = '';
//$result=$DB->query("SELECT * FROM ".$db_prefix."posts ORDER BY RAND() limit 4");
$query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range";
$count_result= $DB->query($query);
$item_count = $DB->num_rows($count_result);
if($item_count > 0)
{
    if($sequence != 0)
	{
	  if($item_count <= 4)
	  {
	    $sequence = 0;
	  }
	  else
	  {
	    $sequence = $sequence%$item_count;
	  }
	}
    $query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range order by post_digg_count desc limit $sequence, 4";
    $result= $DB->query($query);
    $fetch_count = $DB->num_rows($result);
    if($fetch_count == 4 || $sequence == 0)
    {
      show_content($story_content, $result);
    }
    else
    {
      $remain_count = 4 - $fetch_count;
      show_content($story_content, $result);
      $query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range order by post_digg_count desc limit 0, $remain_count";
      $remain_result= $DB->query($query);
      show_content($story_content, $remain_result);
    }
}
else if($item_count == 0)
{
    $story_content = "<p style='margin-left:18px;'>没有找到故事，试一试其他时间段吧</p>";
}
echo $story_content;
?>
