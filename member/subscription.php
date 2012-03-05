<?php
$html_title = "我的订阅";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include $_SERVER['DOCUMENT_ROOT']."/member/userrelation.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

$login_flag = islogin();
if(!$login_flag)
{
  header("location: /accounts/login"); 
  exit;
}

if(isset($_GET['user_id']))
{
  $user_id = intval($_GET['user_id']);
  if($user_id != $_SESSION['uid'])
  {
    header("location: /"); 
    exit;
  }
  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$user_id."'");
  if(!$userresult)
  {
    go("/","您要查看的用户不存在",2);
    exit;
  }
  
  $username = $userresult['username']; 
  $user_profile_img = $userresult['photo'];
  if($user_profile_img == '')
  {
	$user_profile_img = '/img/douban_user_dft.jpg';
  }
  $tbl_name="story_posts";
  $content="";
  ?>
  
  <div id='sub_container' class='inner'>
    <div id='sublist_wrapper'>
	  <div id='sublist_title'>我的订阅</div>
	<?php
	if(isset($_GET['sort']))
    {
	  $sort_type = $_GET['sort'];
	  $padding = "&sort=".$sort_type;
	  $content .= "<div class='sort_type'><a href='/user/".$user_id."/subscription'>最新</a><a class='now' href='/user/".$user_id."/subscription/sort=popular'>最流行</a></div><ul class='sto_cover_list'>";
	}
    else
    {
	  $sort_type='';
	  $padding = '';
	  $content .= "<div class='sort_type'><a class='now' href='/user/".$user_id."/subscription'>最新</a><a href='/user/".$user_id."/subscription/sort=popular'>最流行</a></div><ul class='sto_cover_list'>";
    }
	
	$adjacents = 3;
	$query="select COUNT(*) as num from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published'";
    $total_pages = mysql_fetch_array(mysql_query($query));
    $total_pages = $total_pages[num];
	
	$targetpage = "/user/".$user_id."/subscription"; 
	$limit = 24; 								//how many items to show per page
	$page = $_GET['page'];
	
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0

	/* Get data. */
	if(0 == strcmp($sort_type, 'popular'))
	{
	  //$sql = "SELECT * FROM $tbl_name where post_author=1 order by popular_count desc LIMIT $start, $limit";
	  $sql = "select story_posts.* from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published' order by popular_count desc LIMIT $start, $limit";
	}
	else
	{
	  //$sql = "SELECT * FROM $tbl_name where post_author=1 order by post_modified desc LIMIT $start, $limit";
	  $sql = "select story_posts.* from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published' order by post_modified desc LIMIT $start, $limit";
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
	$content .= printStory($result)."</ul>".$pagination."</div></div>";
	echo $content;
}

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
</body>
</html>