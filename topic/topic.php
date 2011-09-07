<?php
include "../global.php"; 

if(isset($_GET['topic']))
{
  $topic = $_GET['topic'];
}
$tagresult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."tag where name='".$topic."'");
$tag_id = $tagresult['id'];
$query="select ".$db_prefix."posts.* from ".$db_prefix."tag_story,".$db_prefix."posts where tag_id=".$tag_id." and story_id=".$db_prefix."posts.id and TO_DAYS(NOW())-TO_DAYS(post_modified) <=30 order by ".$db_prefix."posts.post_digg_count desc limit 10";
$result=$DB->query($query);

$content = "<div class='inner' style='padding-top:50px;'><h2>#".$topic."# - 最热门</h2><ul id='tagstory_ul'>";
while ($story_item = mysql_fetch_array($result))
{
  $story_id = $story_item['ID'];
  //need to change to fetch the 10 most popular story in this topic category
  $post_author = $story_item['post_author'];
  $userresult = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user where id='".$post_author."'");
  $post_title = $story_item['post_title'];
  $post_summary = $story_item['post_summary'];
  $post_pic_url = $story_item['post_pic_url'];
  $temp_array = explode(" ", $story_item['post_date']);
  $post_date = $temp_array[0];
  $content .=   "<li class='tagstory_li'>
                    <div class='wrapper'>
                        <div class='timestamp'>
                            <span class='update_at'>.".$post_date."</span>
                            <div class='author'>
                                <a href='".$rooturl."/member/user.php?user_id=".$post_author."' muse_scanned='true'>".$userresult['username']."</a> 
                            </div>
                        </div>
                        <a href='".$rooturl."/member/user.php?post_id=".$story_id."'>
                          <img src='".$post_pic_url."' style='width:60px; height:60px;' />
                        </a>
                        <div class='text'>
                            <a href='".$rooturl."/member/user.php?post_id=".$story_id."'class='title'>".$post_title."</a>
                            <p class='summary'>
                                ".$post_summary."
                                <a href='".$rooturl."/member/user.php?post_id=".$story_id."'>[read more]</a> 
                            </p>
                        </div>
                    </div>
                </li>";
}
$content .="</ul></div>";
echo $content;
?>

<?php
 include "../include/footer.htm";
?>
