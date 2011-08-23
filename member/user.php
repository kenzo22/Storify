<?php
include "../global.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
?>
<link type="text/css" href="/storify/css/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="/storify/css/jquery.ui.button.css" rel="stylesheet" />
<script type='text/javascript' src='/storify/js/jquery-ui-1.8.12.custom.min.js'></script>
<script type="text/javascript" src="/storify/js/jquery.embedly.min.js"></script>

<?php
if(isset($_GET['post_id']) && !isset($_GET['action']))
{
	$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
	$t = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
	$post_id = $_GET['post_id'];
	$result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
	if(!$result)
	{
	  throw new Exception('Could not execute query.');
	}
	$story_author = $result['post_author'];
	$userresult = $DB->fetch_one_array("SELECT username, intro, photo FROM ".$db_prefix."user where id='".$story_author."'");
	$story_title=$result['post_title'];
	$story_summary=$result['post_summary'];
	$story_status=$result['post_status'];
	$story_content=$result['post_content'];
	$temp_array = json_decode($story_content, true);
	$items_perpage = 10;
	$story_content_array = array_slice($temp_array['content'], 0, $items_perpage, true);
	$weibo_id_array = array();
	$tweibo_id_array = array();
	
	if(!islogin() || $story_author != $_SESSION['uid'])
	{
	  $content = "<div id='story_container'><div id='publish_container' class='showborder'>";
	}
	else
	{
	  if(0 == strcmp($story_status, 'Published'))
	  {
	    $content = "<div id='story_container'><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>".$story_status."</span><span class='float_r'><a href='#'>通告
			  </a> | <a href='/Storify/member/user.php?post_id=".$post_id."&action=remove'>删除</a> | <a href='/Storify/member/user.php?post_id=".$post_id."&action=edit'>编辑</a></span></div>";
	  }
	  else
	  {
	    $content = "<div id='story_container'><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>".$story_status."</span><span class='float_r'><a href='/Storify/member/user.php?post_id=".$post_id."&action=remove'>删除
			  </a> | <a href='/Storify/member/user.php?post_id=".$post_id."&action=edit'>编辑</a> | <a href='/Storify/member/user.php?post_id=".$post_id."&action=publish'>发布</a></span></div>";
	  }	
	}
	$content .="<div style='padding-left:20px;'><h2>".$story_title."</h2></div>
			  <div style='padding-left:20px;'>".$_SESSION['username']."</div>
			  <div style='padding-left:20px; border-bottom:1px solid #C9C9C9;'>".$story_summary."</div>
			  <ul id='weibo_ul' style='padding:0;'>";
	
	foreach($story_content_array as $key=>$val)
	{
	  if($val['type'] === 'weibo')
	  {
	    $weibo_per_id = $val['content'];
		$single_weibo  = $c->show_status($weibo_per_id );
		
		if ($single_weibo === false || $single_weibo === null){
		echo "<br/><br/><br/><br/><br/>Error occured";
		//return false;
		}
		if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
			echo ('<br/><br/><br/><br/><br/>Error_code: '.$single_weibo['error_code'].';  Error: '.$single_weibo['error'] );
			return false;
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
			$createTime = dateFormat($single_weibo['created_at']);
			
			$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>".$single_weibo['text']."</span></div>
			<div id='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
			.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'>
			<span ><a class='weibo_from' href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date'  style='text-align:right; height:16px;'><span>
			<img border='0' style='position:relative; top:2px' src='/Storify/img/sina16.png'/><a>".$createTime."</a></span></div></span> </div></div></li>";
		}
	  }
	  else if($val['type'] === 'tweibo')
	  {
	    $tweibo_per_id = $val['content'];
		$tweibo_id_array[] = $tweibo_per_id;
		$content .="<li class='weibo_drop tencent' id='$tweibo_per_id' style='border:none;'></li>";
	  }
	  else if($val['type'] === 'comment')
	  {
	    $comment_text = $val['content'];
		$content .="<li class='textElement'><div class='commentBox'>".$comment_text."</div></li>";	
	  }
	  else if($val['type'] === 'video')
	  {
	    $video_url = $val['content'];
		$content .="<li class='video_element'><div><a class='videoTitle' target='_blank' href='".$video_url."'></a></div></li>";	
	  }
	  else if($val['type'] === 'photo')
	  {
	    $photo_meta_data = $val['content'];
		$photo_title = $photo_meta_data['title'];
		$photo_author = $photo_meta_data['author'];
		$photo_per_url = $photo_meta_data['url'];
		$content .="<li class='photo_element'><div style='margin:0px auto; text-align:center; border: 5px solid #FFFFFF; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4); max-width: 260px;'><img src='"
				.$photo_per_url."'/><div class='pic_title' style='line-height:1.5;'>".$photo_title."</div><div class='pic_author' style='line-height:1.5;'>".$photo_author."</div></div></li>";	
	  }
	}
	if(count($tweibo_id_array) > 0)
	{
	  $tweibo_ids = implode(",", $tweibo_id_array);
	  echo "<script language='javascript' >
			$(function()
			{			  
			  $.get('../tweibo/tweibooperation.php', {operation: 'list_weibo', weibo_ids: '$tweibo_ids'},
			  function(data, textStatus)
			  {
				if(textStatus == 'success')
				{
				  var count = $(data).find('li').length;
				  for(var j=0; j<count; j++)
				  {
				    var li = $('li:eq('+j+')', data);
				    var temp_id = li.attr('id');
				    $('#'+temp_id).append(li.contents());
				  }
				}
			  });
			});
			</script>";
	}
	
	if(count($temp_array['content']) > $items_perpage)
	{
	  $content .="</ul><div id='more' style='text-align:center;'><a id='".$items_perpage."' class='load_more' href='#'>更多</a></div>";
	}
	else
	{
	  $content .="</ul>";
	}
	$content .="<div style='display: block; padding:0 10px 0 5px; text-align:right;'>Powered by <a name='poweredby' target='_blank' href='http://koulifang.com'>口立方</a></div></div>
	<div id='userinfo_container' class='showborder'>
	  <div class='user_profiles'>
	    <div class='user_box'>
		  <div class='avatar'><a style='background-image: url(/storify/img/user/".$userresult['photo'].")' href='#'></a></div>";
	if(islogin() && $story_author != $_SESSION['uid'])
	{
	  $content .="<a href='#' class='follow_btn'>关注</a><a href='#' class='follow_btn' style='display:none;'>取消关注</a>";
	}
	$content .="<div class='user_info'><P>".$userresult['username']."</P><P>".$userresult['intro']."</P></div>
		  <div class='usersfollowers'>
		    <span>粉丝</span><span class='count'>10000</span>
		    <div class='kusers'>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			</div>
		  </div>		  
		  <div class='usersfollowing'>
		    <span>关注</span><span class='count'>100</span>
			<div class='kusers'>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			  <a class='follow_mini_icon' style='background-image: url(/storify/img/person.png)' href='#'></a>
			</div>
		  </div>
		</div>
	  </div>
	  <div class='story_stats'>
	  <p>被浏览了100次</p>
	  </div>
	</div>
	</div>";
	echo $content;
	echo "<script language='javascript' >
			$(function()
			{
			  $('.follow_btn').button().click(function(){
				  var userid = $story_author;
				  var operation_val = $(this).text();
				  if('关注' == operation_val)
				  {
				    operation_val = 'follow';
				  }
				  else
				  {
				    operation_val = 'unfollow';
				  }
				  var postdata = {operation: operation_val, uid: userid};
				  $.post('useroperation.php', postdata,
					  function(data, textStatus)
					  {
						if('success'==textStatus)
						{
						  $('.follow_btn').toggle();
						}
						console.log(data);						
					  });
				});
			  $('.load_more').live('click',function(e)
				{
				  e.preventDefault();
				  var post_id_val = $post_id;
				  var first_item_val = $(this).attr('id');
				  var postdata = {post_id: post_id_val, first_item: first_item_val};			  
				  
				  $.ajax({
					type: 'POST',
					url: 'loadstoryitem.php',
					data: postdata, 
					beforeSend:function() 
					{
					  var imgpath = '../img/loading.gif';
					  var imgloading = $(\"<img src='../img/loading.gif' />\");
					  $('.load_more').html(imgloading);
					},
					success: function(data){
						$('#more').remove();
						$('#weibo_ul').append(data);
						
					}
					});
				});
			});
			</script>";
}

else if(isset($_GET['post_id']) && isset($_GET['action']))
{
	$story_id = $_GET['post_id'];
	$story_action = $_GET['action'];
	if(0 == strcmp($story_action, 'remove'))
	{
	  $result=$DB->query("DELETE FROM ".$db_prefix."posts where ID='".$story_id."'");
	  go($rooturl.'/member/user.php');
	}
	else if(0 == strcmp($story_action, 'edit'))
	{
	  go($rooturl.'/member/index.php?post_id='.$story_id);
	}
	else if(0 == strcmp($story_action, 'publish'))
	{
	  $result=$DB->query("update ".$db_prefix."posts set post_status='Published'  WHERE ID='".$story_id."'");
	  go($rooturl.'/member/user.php?post_id='.$story_id);
	}
	else
	{
	  throw new Exception('Undefined story action.');
	}
}

else
{
  $story_content = "<div id='userstory_container' class='inner'><div class='userstory_list'><ul>";
  $result=$DB->query("SELECT * FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."'");
  //$userresult = $DB->fetch_one_array("SELECT photo FROM ".$db_prefix."user where id='".$story_author."'");
  while ($story_item = mysql_fetch_array($result))
  {
    //printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
	$post_title = $story_item['post_title'];
	$post_date = $story_item['post_date'];
	$temp_array = explode(" ", $story_item['post_date']);
	$post_date = $temp_array[0];
    $story_content .= "<li><a class='cover' href='/Storify/member/user.php?post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='story_meta' 
	><span><img border='0' style='position:relative; top:2px' src='/Storify/img/sina16.png'/><a style='margin-left:5px;'>".$_SESSION['username']."</a><a style='margin-left:65px;'>".$post_date."</a></span></div></li>";
  }

  $story_content .="</ul></div></div>";
  echo $story_content;
}
?>

<script type="text/javascript">

function append_video_content(url)
{
  $.embedly(url, {key: '4ac512dca79011e0aeec4040d3dc5c07', maxWidth: 420, wrapElement: 'div', method : 'afterParent'  }, function(oembed){				
	if (oembed != null)
	{
	  var videoTitle = oembed.title;
	  var videoContent = oembed.code;
	  $("a[href="+url+"]").text(videoTitle).parent().after(videoContent);
	}		  			
  });
}

$(function(){
	$('#user_action').css('display', 'inline');
	//$('#publish_container').css({'margin':'auto', 'width':'80%'});
	//$('.weibo_drop').css({'margin':'auto', 'width':'60%', 'margin-top':'10px', 'border':'none'});
	$('.video_element').each(function()
	{
	  var videoUrl = $(this).find('.videoTitle').attr('href');
	  append_video_content(videoUrl);
	});
});
	
</script>

<?php
include "../include/footer.htm";
?>