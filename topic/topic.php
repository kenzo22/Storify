<?php
include "../global.php"; 

if(isset($_GET['topic']))
{
  $topic = $_GET['topic'];
}
$tagresult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."tag where name='".$topic."'");
$tag_id = $tagresult['id'];
$result=$DB->query("SELECT * FROM ".$db_prefix."tag_story where tag_id='".$tag_id."'");
$content = "<div class='inner' style='padding-top:50px;'><h2>#".$topic."# - 最热门</h2><ul id='tagstory_ul'>";
while ($story_item = mysql_fetch_array($result))
{
  //$story_id = $story_item['story_id'];
  //need to change to fetch the 10 most popular story in this topic category
  $storyresult = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."posts where id='".$story_item[story_id]."' limit 10");
  $post_author = $storyresult['post_author'];
  $userresult = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user where id='".$post_author."'");
  $post_title = $storyresult['post_title'];
  $post_summary = $storyresult['post_summary'];
  $temp_array = explode(" ", $storyresult['post_date']);
  $post_date = $temp_array[0];
  $content .="<li class='tagstory_li'><div class='wrapper'><h3>".$post_title."</h3><div>".$post_summary."</div><div><span>".$userresult[username]."</span><span>".$post_date."</span></div></div></li>";
}
$content .="</ul></div>";
echo $content;
echo $tag_id;
?>


<?php
 include "../include/footer.htm";
?>