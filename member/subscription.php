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
    <div id='sublist_title'>我的订阅</div>
    <div id='sublist_wrapper'>
	<?php
	if(isset($_GET['sort']))
    {
	  $sort_type = $_GET['sort'];
	  $padding = "&sort=".$sort_type;
	  $content .= "<div class='sort_type'><a href='/user/".$user_id."/subscription'>最新</a><a class='now' href='/user/".$user_id."/subscription/sort=popular'>最流行</a></div><div class='clear'></div><ul class='sto_cover_list'>";
	}
    else
    {
	  $sort_type='';
	  $padding = '';
	  $content .= "<div class='sort_type'><a class='now' href='/user/".$user_id."/subscription'>最新</a><a href='/user/".$user_id."/subscription/sort=popular'>最流行</a></div><div class='clear'></div><ul class='sto_cover_list'>";
    }
	
	$query="select COUNT(*) as num from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published'";
    $total_num = mysql_fetch_array(mysql_query($query));
    $total_num = $total_num[num];
	
	$limit = 16; 							

	if(0 == strcmp($sort_type, 'popular'))
	{
	  $sql = "select story_posts.* from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published' order by popular_count desc LIMIT 0, $limit";
	}
	else
	{
	  $sql = "select story_posts.* from ".$db_prefix."follow, story_posts where user_id=".$user_id." and follow_id = post_author and post_status = 'Published' order by post_modified desc LIMIT 0, $limit";
	}

	$result = mysql_query($sql);
	$content .= printStory($result);
	if($total_num > $limit)
	{
	  $content .= "</ul><div class='more_content'><a id='sub_".$limit."' class='load_more' href='#'>更多</a></div></div></div>";
	}
	else
	{
	  $content .= "</ul></div></div>";
	}
	echo $content;
}

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
<script type="text/javascript">
$(function()
{	  
  $('.load_more').live('click', function(e){
	e.preventDefault();
    var sort_val,postData,
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
	postData = {from: "sub",first_item: first_item_val, sort: sort_val};
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