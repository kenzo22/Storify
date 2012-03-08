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
  "美食",
  "旅游",
  "晒货",
  "搞笑",
  "影视",
  "音乐",
  "图书"
  ),
  array
  (
  "互联网",
  "创业",
  "移动互联网",
  "数码",
  "游戏"
  ),
  array
  (
  "国际足坛",
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
				  <a href='/keji/3'>移动互联网</a>
				  <a href='/keji/4'>数码</a>
				  <a href='/keji/5'>游戏</a>
				</div>
				
				<div id='tiyu' class='submenustyle'>
				  <a href='/tiyu'>全部</a>
				  <a href='/tiyu/1'>国际足坛</a>
				  <a href='/tiyu/2'>NBA</a>
				  <a href='/tiyu/3'>综合</a>
				</div>
				
				<div id='yule' class='submenustyle'>
				  <a href='/yule'>全部</a>
				  <a href='/yule/1'>明星</a>
				  <a href='/yule/2'>美食</a>
				  <a href='/yule/3'>旅游</a>
				  <a href='/yule/4'>晒货</a>
				  <a href='/yule/5'>搞笑</a>
				  <a href='/yule/6'>影视</a>
				  <a href='/yule/7'>音乐</a>
				  <a href='/yule/8'>图书</a>
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
<script type="text/javascript">
var mastertabvar=new Object()
mastertabvar.baseopacity=0
mastertabvar.browserdetect=""

function showsubmenu(masterid, id)
{
  if(typeof highlighting!="undefined")
	clearInterval(highlighting);
  submenuobject=document.getElementById(id);
  mastertabvar.browserdetect=submenuobject.filters? "ie" : typeof submenuobject.style.MozOpacity=="string"? "mozilla" : "";
  hidesubmenus(mastertabvar[masterid]);
  submenuobject.style.display="block";
  instantset(mastertabvar.baseopacity);
  highlighting=setInterval("gradualfade(submenuobject)",50);
}

function hidesubmenus(submenuarray)
{
  for(var i=0; i<submenuarray.length; i++)
    document.getElementById(submenuarray[i]).style.display="none";
}

function instantset(degree)
{
  if (mastertabvar.browserdetect=="mozilla")
    submenuobject.style.MozOpacity=degree/100;
  else if (mastertabvar.browserdetect=="ie")
	submenuobject.filters.alpha.opacity=degree;
}


function gradualfade(cur2)
{
  if(mastertabvar.browserdetect=="mozilla" && cur2.style.MozOpacity<1)
	cur2.style.MozOpacity=Math.min(parseFloat(cur2.style.MozOpacity)+0.1, 0.99);
  else if(mastertabvar.browserdetect=="ie" && cur2.filters.alpha.opacity<100)
	cur2.filters.alpha.opacity+=10;
  else if(typeof highlighting!="undefined") //fading animation over
	clearInterval(highlighting);
}

function initalizetab(tabid)
{
  mastertabvar[tabid]=new Array();
  var menuitems=document.getElementById(tabid).getElementsByTagName("li");
  for(var i=0; i<menuitems.length; i++)
  {
	if (menuitems[i].getAttribute("rel"))
	{
	  menuitems[i].setAttribute("rev", tabid); //associate this submenu with main tab
	  mastertabvar[tabid][mastertabvar[tabid].length]=menuitems[i].getAttribute("rel"); //store ids of submenus of tab menu
	  if(menuitems[i].className=="selected")
		showsubmenu(tabid, menuitems[i].getAttribute("rel"));
	  menuitems[i].getElementsByTagName("a")[0].onmouseover=function()
	  {
		showsubmenu(this.parentNode.getAttribute("rev"), this.parentNode.getAttribute("rel"));
	  }
	}
  }
}

initalizetab("maintab");

$(function()
{	   
  $('.load_more').live('click', function(e){
	e.preventDefault();
    var sort_val,name_val,subname_val,postData,
		more_id_val = $(this).attr('id'),
	    more_array = more_id_val.split('_'),
	    first_item_val = more_array[1];
	if($('.sort_type .now').text() == "最新")
	{
	  sort_val = "time";
	}
	else
	{
	  sort_val = "popular";
	}
	name_val = $('#maintab li.selected').text();
	subname_val = $('.submenustyle a.selected').text();
	postData = {from: "all", name: name_val, subname: subname_val, first_item: first_item_val, sort: sort_val};
	imgloading = $("<img src='/img/loading.gif' />");
	$.ajax({
			type: 'POST',
			url: '/member/loadmorestory.php',
			data: postData, 
			beforeSend:function() 
			{
			  $('.load_more').html(imgloading);
			},
			success: function(data){
				$('.more_content').remove();
				$('.sto_cover_list').append(data).after($('.more_content').remove());
			}
			});
  })
});
</script>
</body>
</html>