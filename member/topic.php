<?php
$html_title = "口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php"; 
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

if(isset($_GET['topic_id']))
{
  $topic_id = $_GET['topic_id'];
}

$date_t = date("Y-m-d H:i:s");

$tagresult = $DB->fetch_one_array("SELECT name FROM ".$db_prefix."tag where id='".$topic_id."'");
$tag_name = $tagresult['name'];
//$tag_id = $tagresult['id'];
$query="select ".$db_prefix."posts.* from ".$db_prefix."tag_story,".$db_prefix."posts where tag_id=".$topic_id." and story_id=".$db_prefix."posts.id and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS order by ".$db_prefix."posts.post_digg_count desc limit 10";
$result=$DB->query($query);

$content = "<div class='inner'><div class='page_title'>#".$tag_name."# - 最热门</div><ul id='tagstory_ul'>";
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
  $post_link = "/user/".$post_author."/".$story_id;
  $post_link = htmlspecialchars($post_link);
  if($post_pic_url == '')
  {
    $post_pic_url = getUserPic($post_author);
  }
  $content .=   "<li class='tagstory_li'>
                    <div class='wrapper'>
                        <a class='pic_meta' href='/user/".$post_author."/".$story_id."'>
                          <img src='".$post_pic_url."' alt='故事封面'/>
                        </a>
						<div class='text meta'>
						  <a href='".$post_link."' class='title'>".$post_title."</a>
						  <div>
                            <span class='update_at'>".$post_date."</span><span style='padding:0 5px;'>by</span><a href='/user/".$post_author."'>".$userresult['username']."</a> 
                          </div>
                          <div class='summary'>".$post_summary."<a href='".$post_link."'>[更多]</a></div>
                        </div> 
                    </div>
                </li>";
}
$content .="</ul></div>";
echo $content;
echo "<script type='text/javascript' >
		  $(function(){
		    document.title = '#'+'$tag_name'+'#'+' - 口立方';
		  });
		</script>";

 include "../include/footer.htm";
?>
</body>
</html>

