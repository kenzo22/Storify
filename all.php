<?php
$html_title = "我的订阅";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
if(isset($_GET['name']))
{
  $name = $_GET['name'];
}
else
{
  header("location: /"); 
  exit;
}

$sort_pos = strpos($_SERVER['REQUEST_URI'], "sort");
if($sort_pos)
{
  $sort_link = substr($_SERVER['REQUEST_URI'], 0, $sort_pos-1);
}
else
{
  $sort_link = $_SERVER['REQUEST_URI'];
}

if(isset($_GET['sort']))
{
  $sort_type = "popular_count";
  $sort_content = "<div class='sort_type'><a href='".$sort_link."'>最新</a><a class='now' href='".$sort_link."/sort=popular'>最热</a></div>";
}
else
{
  $sort_type = "post_modified";
  $sort_content = "<div class='sort_type'><a class='now' href='".$sort_link."'>最新</a><a href='".$sort_link."/sort=popular'>最热</a></div>";
}

$query_names = array
(
  array
  (
  "热点话题",
  "万象",
  "公益"
  ),
  array
  (
  "明星",
  "搞笑",
  "影视"
  ),
  array
  (
  "互联网",
  "创业",
  "数码"
  ),
  array
  (
  "足坛",
  "NBA",
  "综合"
  )
);

switch($name)
{
  case "shehui":{
    $main_select = 1;
	$q_name = "社会";
	$tab_content = "<li class='selected' rel='shehui'><a href='/shehui'>社会</a></li>
				    <li rel='yule'><a href='/yule'>娱乐</a></li>
				    <li rel='keji'><a href='/keji'>科技</a></li>
				    <li rel='tiyu'><a href='/tiyu'>体育</a></li>";
    break;
  }
  case "yule":{
    $main_select = 2;
	$q_name = "娱乐";
	$tab_content = "<li rel='shehui'><a href='/shehui'>社会</a></li>
				    <li class='selected' rel='yule'><a href='/yule'>娱乐</a></li>
				    <li rel='keji'><a href='/keji'>科技</a></li>
				    <li rel='tiyu'><a href='/tiyu'>体育</a></li>";
    break;
  }
  case "keji":{
    $main_select = 3;
	$q_name = "科技";
	$tab_content = "<li rel='shehui'><a href='/shehui'>社会</a></li>
				    <li rel='yule'><a href='/yule'>娱乐</a></li>
				    <li class='selected' rel='keji'><a href='/keji'>科技</a></li>
				    <li rel='tiyu'><a href='/tiyu'>体育</a></li>";
    break;
  }
  case "tiyu":{
    $main_select = 4;
	$q_name = "体育";
	$tab_content = "<li rel='shehui'><a href='/shehui'>社会</a></li>
				    <li rel='yule'><a href='/yule'>娱乐</a></li>
				    <li rel='keji'><a href='/keji'>科技</a></li>
				    <li class='selected' rel='tiyu'><a href='/tiyu'>体育</a></li>";
    break;
  }
  default:
    break;
}

if(isset($_GET['sub']))
{
  $sub_select = $_GET['sub'];
  $sub_name = $query_names[$main_select-1][$sub_select-1];
  $needle = "<a href='/".$name."/".$sub_select."'>".$sub_name."</a>";
  $replace = "<a class='selected' href='/".$name."/".$sub_select."'>".$sub_name."</a>";
}
else
{
  $sub_select = 0;
  $needle = "<a href='/".$name."'>全部</a>";
  $replace = "<a class='selected' href='/".$name."'>全部</a>";
}

$content = "<div id='all_container' class='inner'>
			  <div id='menu_wrapper'>
				<ul id='maintab' class='basictab'>".$tab_content."</ul>";
$sub_content = "<div id='shehui' class='submenustyle'>
				  <a href='/shehui'>全部</a>
				  <a href='/shehui/1'>热点话题</a>
				  <a href='/shehui/2'>万象</a>
				  <a href='/shehui/3'>公益</a>
				</div>
				
				<div id='keji' class='submenustyle'>
				  <a href='/keji'>全部</a>
				  <a href='/keji/1'>互联网</a>
				  <a href='/keji/2'>创业</a>
				  <a href='/keji/3'>数码</a>
				</div>
				
				<div id='tiyu' class='submenustyle'>
				  <a href='/tiyu'>全部</a>
				  <a href='/tiyu/1'>足坛</a>
				  <a href='/tiyu/2'>NBA</a>
				  <a href='/tiyu/3'>综合</a>
				</div>
				
				<div id='yule' class='submenustyle'>
				  <a href='/yule'>全部</a>
				  <a href='/yule/1'>明星</a>
				  <a href='/yule/2'>搞笑</a>
				  <a href='/yule/3'>影视</a>
				</div>";
				
$sub_content = str_replace($needle,$replace,$sub_content);
	
$content.=$sub_content."</div>".$sort_content."<div id='alllist_wrapper'><ul class='sto_cover_list'>";

$limit = 16;

if($sub_select == 0)
{
  $sql = "select story_posts.* from story_category, story_posts where name='".$q_name."' and story_id=story_posts.ID and post_status = 'Published' order by ".$sort_type." desc LIMIT 0, $limit";
  $query = "select COUNT(*) as num from story_category, story_posts where name='".$q_name."' and story_id=story_posts.ID and post_status = 'Published'";
}
else
{
  $sql = "select story_posts.* from story_category, story_posts where name='".$q_name."' and subname='".$sub_name."' and story_id=story_posts.ID and post_status = 'Published' order by ".$sort_type." desc LIMIT 0, $limit";
  $query = "select COUNT(*) as num from story_category, story_posts where name='".$q_name."' and subname='".$sub_name."' and story_id=story_posts.ID and post_status = 'Published'";
}
$total_num = mysql_fetch_array(mysql_query($query));
$total_num = $total_num[num];

$result = mysql_query($sql);
$content.=printStory($result);

if($total_num > $limit)
{
  $content .= "</ul><div class='more_content'><a id='all_".$limit."' class='load_more' href='#'>更多</a></div></div></div>";
}
else
{
  $content .= "</ul></div></div>";
}

echo $content;

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
<script type="text/javascript" src="/js/all.js"></script>
</body>
</html>