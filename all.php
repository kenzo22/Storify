<?php
$html_title = "全部热点";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

if(isset($_GET['sort']))
{
  $sort_type = "popular_count";
  $sort_content = "<div class='sort_type'><a href='/all'>最新</a><a class='now' href='/all/sort=popular'>最热</a></div>";
}
else
{
  $sort_type = "post_modified";
  $sort_content = "<div class='sort_type'><a class='now' href='/all'>最新</a><a href='/all/sort=popular'>最热</a></div>";
}

$content = "<div id='all_container' class='inner'><div id='alllist_title'>全部热点</div>".$sort_content."<div id='alllist_wrapper'><ul class='sto_cover_list'>";

$limit = 16;

$sql = "select story_posts.* from story_posts where post_status = 'Published' order by ".$sort_type." desc LIMIT 0, $limit";
$query = "select COUNT(*) as num from story_posts where post_status = 'Published'";

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