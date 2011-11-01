<?php
require_once "../connect_db.php";
$flag=$_GET['flag'];
$sequence=$_GET['sequence'];

$now_time=time();

switch ($flag)
{
case 0:
  //$start_time =  date("Y-m-d H:i:s", $now_time-3*24*60*60);
  $time_range = 3;
  break;
case 1:
  //$start_time = date("Y-m-d H:i:s", $now_time-7*24*60*60);
  $time_range = 7;
  break;
case 2:
  //$start_time = date("Y-m-d H:i:s", $now_time-30*24*60*60);
  $time_range = 30;
  break;
case 3:
  //$start_time = date("Y-m-d H:i:s", $now_time-365*24*60*60);
  $time_range = 365;
  break;
default:
  //$start_time = date("Y-m-d H:i:s", $now_time-3*24*60*60);
  $time_range = 3;
  break;
}

$story_content = '';
//$result=$DB->query("SELECT * FROM ".$db_prefix."posts ORDER BY RAND() limit 4");
$query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range";
$count_result= $DB->query($query);
$item_count = $DB->num_rows($count_result);
if($item_count > 0 ){
    $sequence = $sequence%$item_count;
    $query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range order by post_digg_count desc limit $sequence, 4";
    $result= $DB->query($query);
    $fetch_count = $DB->num_rows($result);
    if($fetch_count == 4)
    {
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
    }
    else
    {
      $remain_count = 4 - $fetch_count;
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
      $query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=$time_range order by post_digg_count desc limit 0, $remain_count";
      $remain_result= $DB->query($query);
      while ($story_item = mysql_fetch_array($remain_result))
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
    }
}
else if($item_count == 0)
{
    $story_content = "<p>没有找到故事。</p>";
}
echo $story_content;
?>
