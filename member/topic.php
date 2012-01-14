<?php
$html_title = "口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php"; 
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

if(isset($_GET['topic_id']))
{
  $topic_id = intval($_GET['topic_id']);
}

$date_t = date("Y-m-d H:i:s");
$tagresult = $DB->fetch_one_array("SELECT name FROM ".$db_prefix."tag where id='".$topic_id."'");
$tag_name = $tagresult['name'];

$adjacents = 3;
$query="select COUNT(*) as num from ".$db_prefix."tag_story,".$db_prefix."posts where post_status = 'Published' and tag_id=".$topic_id." and story_id=".$db_prefix."posts.id";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages[num];

$targetpage = "/topic/".$topic_id; 
$limit = 6; 								//how many items to show per page
$page = $_GET['page'];

$content = "<div id='tagstory_container' class='inner'>
			  <div class='page_title'>#".$tag_name."# - 最热门</div>";

if(isset($_GET['sort']))
{
  $sort_type = $_GET['sort'];
  $padding = "&sort=".$sort_type;
  $content .= "<div id='sort_type'><a href='/topic/".$topic_id."'>发布时间排序</a><a class='now' href='/topic/".$topic_id."/sort=popular'>关注度排序</a></div>
			   <ul id='tagstory_ul'>";
}
else
{
  $sort_type='';
  $padding = '';
  $content .= "<div id='sort_type'><a class='now' href='/topic/".$topic_id."'>发布时间排序</a><a href='/topic/".$topic_id."/sort=popular'>关注度排序</a></div>
			   <ul id='tagstory_ul'>";
}


if($page) 
	$start = ($page - 1) * $limit; 			//first item to display on this page
else
	$start = 0;								//if no page var is given, set start to 0

/* Get data. */
if(0 == strcmp($sort_type, 'popular'))
{
  $sql="select ".$db_prefix."posts.* from ".$db_prefix."tag_story,".$db_prefix."posts where post_status = 'Published' and tag_id=".$topic_id." and story_id=".$db_prefix."posts.id order by ".$db_prefix."posts.popular_count desc limit $start, $limit";
}
else
{
  $sql="select ".$db_prefix."posts.* from ".$db_prefix."tag_story,".$db_prefix."posts where post_status = 'Published' and tag_id=".$topic_id." and story_id=".$db_prefix."posts.id order by ".$db_prefix."posts.post_date desc limit $start, $limit";
}

$result = mysql_query($sql);

/* Setup page vars for display. */
if ($page == 0) $page = 1;					//if no page var is given, default to 1.
$prev = $page - 1;							
$next = $page + 1;							
$lastpage = ceil($total_pages/$limit);	
$lpm1 = $lastpage - 1;						//last page minus 1

$pagination = "";
if($lastpage > 1)
{	
	$pagination .= "<div class=\"pagination\">";
	//previous button
	if ($page > 1) 
		$pagination.= "<a href=\"$targetpage/page=$prev$padding\">« 前页</a>";
	else
		$pagination.= "<span class=\"disabled\">« 前页</span>";	
	
	//pages	
	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
	{	
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
				$pagination.= "<span class=\"current\">$counter</span>";
			else
				$pagination.= "<a href=\"$targetpage/page=$counter$padding\">$counter</a>";					
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
	{
		//close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage/page=$counter$padding\">$counter</a>";					
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage/page=$lpm1$padding\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage/page=$lastpage$padding\">$lastpage</a>";		
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<a href=\"$targetpage/page=1$padding\">1</a>";
			$pagination.= "<a href=\"$targetpage/page=2$padding\">2</a>";
			$pagination.= "...";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage/page=$counter$padding\">$counter</a>";					
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage/page=$lpm1$padding\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage/page=$lastpage$padding\">$lastpage</a>";		
		}
		//close to end; only hide early pages
		else
		{
			$pagination.= "<a href=\"$targetpage/page=1$padding\">1</a>";
			$pagination.= "<a href=\"$targetpage/page=2$padding\">2</a>";
			$pagination.= "...";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage/page=$counter$padding\">$counter</a>";					
			}
		}
	}
	
	//next button
	if ($page < $counter - 1) 
		$pagination.= "<a href=\"$targetpage/page=$next$padding\">后页 »</a>";
	else
		$pagination.= "<span class=\"disabled\">后页 »</span>";
	$pagination.= "</div>\n";		
}

while ($story_item = mysql_fetch_array($result))
{
  $story_id = $story_item['ID'];
  $view_count = 0;
  $digg_count = $story_item['post_digg_count'];
  $query="select COUNT(*) as num from ".$db_prefix."comments where comment_post_id =".$story_id;
  $reply_result = mysql_fetch_array(mysql_query($query));
  $reply_count = $reply_result[num];
  
  $view_query = "select view_count from ".$db_prefix."pageview where story_id=".$story_id;
  $viewResult = $DB->query($view_query);
  if($DB->num_rows($viewResult) > 0)
  {
	while($count_result_row = $DB->fetch_array($viewResult)){
		$view_count += $count_result_row['view_count'];
	}
  }
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
						  <div class='count_wrapper'><span>评论 (".$reply_count."次) </span> | <span>顶 (".$digg_count."次) </span> | <span>浏览 (".$view_count."次) </span></div>
                        </div> 
                    </div>
                </li>";
}
$content .="</ul>".$pagination."</div>";
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

