<?php
$html_title = "口立方";
include "../global.php"; 
include '../include/secureGlobals.php';

if(isset($_GET['topic']))
{
  $topic = $_GET['topic'];
}

$date_t = date("Y-m-d H:i:s");

$tagresult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."tag where name='".$topic."'");
$tag_id = $tagresult['id'];
$query="select ".$db_prefix."posts.* from ".$db_prefix."tag_story,".$db_prefix."posts where tag_id=".$tag_id." and story_id=".$db_prefix."posts.id and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS order by ".$db_prefix."posts.post_digg_count desc limit 10";
$result=$DB->query($query);


$content = "<div class='inner'><h2>#".$topic."# - 最热门</h2><ul id='tagstory_ul'>";
while ($story_item = mysql_fetch_array($result))
{
  $story_id = $story_item['ID'];
  //need to change to fetch the 10 most popular story in this topic category
  $post_author = $story_item['post_author'];
  $userresult = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user where id='".$post_author."'");
  $post_title = $story_item['post_title'];
  $post_summary = $story_item['post_summary'];
  $post_pic_url = $story_item['post_pic_url'];
  $post_date = dateFormatTrans($story_item['post_date'],$date_t);
  $content .=   "<li class='tagstory_li'>
                    <div class='wrapper'>
                        <a style='float:left; display:inline;' href='/member/user.php?user_id=".$post_author."&post_id=".$story_id."'>
                          <img src='".$post_pic_url."' />
                        </a>
						<div class='text' style='margin-left:100px;'>
						  <a href='/member/user.php?user_id=".$post_author."&post_id=".$story_id."'class='title'>".$post_title."</a>
						  <div>
                            <span class='update_at'>".$post_date."</span>&nbspby&nbsp<a href='/member/user.php?user_id=".$post_author."' muse_scanned='true'>".$userresult['username']."</a> 
                          </div>
                          <div class='summary'>".$post_summary."<a href='/member/user.php?user_id=".$post_author."&post_id=".$story_id."'>[read more]</a> </div>
                        </div> 
                    </div>
                </li>";
}
$content .="</ul></div>";
echo $content;
echo "<script language='javascript' >
		  $(function(){
		    document.title = '#'+'$topic'+'#'+' - 口立方';
		  });
		</script>";

 include "../include/footer.htm";
?>

