<?php
include "../global.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include_once "userrelation.php";
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
	$d = new DoubanClient( DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']  );
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
	$story_pic=$result['post_pic_url'];
	$story_status=$result['post_status'];
	$story_content=$result['post_content'];
	$story_digg_count=$result['post_digg_count'];
	//get the profile image of the story author
	$user_profile_img;
    if(substr($userresult['photo'], 0, 4) == 'http')
    {
	  if(substr($userresult['photo'], 11, 4) == 'sina')
	  {
		$pattern = "/(\d+)\/50\/(\d+)/";
		$user_profile_img = preg_replace($pattern,"$1/180/$2",$userresult['photo']);
	  }
	  else
	  {
	    $pattern = "/50$/";
		$user_profile_img = preg_replace($pattern,'100',$userresult['photo']);
	  }
    }
    else
    {
	  $user_profile_img =$userresult['photo'];
    }
	
	$temp_array = json_decode($story_content, true);
	$items_perpage = 10;
	$story_content_array = array_slice($temp_array['content'], 0, $items_perpage, true);
	$weibo_id_array = array();
	$tweibo_id_array = array();
	
	if(!islogin() || $story_author != $_SESSION['uid'])
	{
	  $content = "<div id='story_container'><div class='digg_wrap'><div id='".$post_id."_digg_count' style='margin-top:10px;'>".$story_digg_count."</div><a id='".$post_id."_act_digg' class='act_digg'>顶一下</a></div><div id='publish_container' class='showborder'>";
	}
	else
	{
	  if(0 == strcmp($story_status, 'Published'))
	  {
	    $content = "<div id='story_container'><div class='digg_wrap'><div id='".$post_id."_digg_count' style='margin-top:10px;'>".$story_digg_count."</div><a id='".$post_id."_act_digg' class='act_digg'>顶一下</a></div><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>已发布</span><span class='float_r'><a href='#'>通告
			  </a> | <a href='/storify/member/user.php?post_id=".$post_id."&action=remove'>删除</a> | <a href='/storify/member/user.php?post_id=".$post_id."&action=edit'>编辑</a></span></div>";
	  }
	  else
	  {
	    $content = "<div id='story_container'><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>草稿</span><span class='float_r'><a href='/storify/member/user.php?post_id=".$post_id."&action=remove'>删除
			  </a> | <a href='/storify/member/user.php?post_id=".$post_id."&action=edit'>编辑</a> | <a href='/storify/member/user.php?post_id=".$post_id."&action=publish'>发布</a></span></div>";
	  }	
	}
	$content .="<div id='story_header' style='margin:0; padding:0;'><div style='float:right; padding: 10px 10px 0 0'><img src='".$story_pic."' style='width:60px; height:60px;' /></div><div style='padding-left:20px;'><h2>".$story_title."</h2></div>
			  <div style='padding-left:20px;'>".$userresult['username']."</div>
			  <div style='padding-left:20px; border-bottom:1px solid #C9C9C9;'>".$story_summary."</div>
			  </div>
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
            // skip deleted weibo
            $content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>此微博已被删除</span></div>";
            continue;
			/*
            echo ('<br/><br/><br/><br/><br/>Error_code: '.$single_weibo['error_code'].';  Error: '.$single_weibo['error'] );
			echo  $_SESSION['last_key']['oauth_token'];
			echo $_SESSION['last_key']['oauth_token_secret'];
			return false;
            */
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
			$createTime = dateFormat($single_weibo['created_at']);
			$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>".$single_weibo['text'];
    		if (isset($single_weibo['retweeted_status'])){
                $content .="//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
                if(isset($single_weibo['retweeted_status']['bmiddle_pic'])){
                    $content .= "</span><div class='weibo_retweet_img' style='text-align:center;'><img src='".$single_weibo['retweeted_status']['bmiddle_pic']."' width='280px;' /></div>";
                }
				else
				{
				  $content .= "</span>";
				}
            }
            if (isset($single_weibo['bmiddle_pic']))
			{
			  $content .= "<div class='weibo_img' style='text-align:center;'><img src='".$single_weibo['bmiddle_pic']."' width='280px;' /></div>";
			}
            $content .= "</div>";
            $content .= "<div id='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
			.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'>
			<span ><a class='weibo_from' href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date'  style='text-align:right; height:16px;'><span>
			<img border='0' style='position:relative; top:2px' src='/storify/img/sina16.png'/><a>".$createTime."</a></span></div></span> </div></div></li>";
		}
	  }
	  else if($val['type'] === 'tweibo')
	  {
	    $tweibo_per_id = $val['content'];
		$tweibo_id_array[] = $tweibo_per_id;
		$content .="<li class='weibo_drop tencent' id='$tweibo_per_id' style='border:none;'></li>";
	  }
	  else if($val['type'] === 'douban')
	  {
	    $douban_save_per_id = $val['content']['item_id'];
		if($val['content']['item_type'] == 'event')
		{
		  $eventImgFlag = 0;
		  $userImgFlag = 0;
		  $doubanElement = $d->get_event($douban_save_per_id);
		  
		  for($i=0;$i<count($doubanElement['link']); $i++)
		  {
		    if($doubanElement['link'][$i]['@rel'] == 'image')
		    {
			  $eventImgFlag = 1;
			  break;
		    }
		  }
		  if($eventImgFlag == 1)
		  {
		    $eventImg = $doubanElement['link'][$i]['@href'];
		  }
		  else if($eventImgFlag == 0)
		  {
		    $eventImg = "../img/event_dft.jpg";
		  }
		  
		  for($j=0;$j<count($doubanElement['author']['link']); $j++)
		  {
		    if($doubanElement['author']['link'][$j]['@rel'] == 'alternate')
		    {
			  break;
		    }
		  }
		  $eventInitiator_url = $doubanElement['author']['link'][$j]['@href'];
		  $eventInitiator_name = $doubanElement['author']['name']['$t'];
		
		  for($k=0;$k<count($doubanElement['author']['link']); $k++)
		  {
		    if($doubanElement['author']['link'][$k]['@rel'] == 'icon')
		    {
			  $userImgFlag = 1;
			  break;
		    }
		  }
		  if($userImgFlag == 1)
		  {
		    $eventInitiator_pic = $doubanElement['author']['link'][$k]['@href'];
		  }
		  else if($userImgFlag == 0)
		  {
		    $eventInitiator_pic = "../img/douban_user_dft.jpg";
		  }
		  
		  $content .=
		 "<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
		    <div class='douban_wrapper'>
			  <div class='event_summary'>".$doubanElement['summary'][0]['$t']."</div>
			  <div style='margin-top:10px; overflow:auto;'>
			    <a href='".$doubanElement['link'][1]['@href']."' target='_blank'>
				  <img class='item_img' src='".$eventImg."' style='float:left;' />
				</a>
				<div class='item_meta' style='margin-left:220px;'>
				  <div class='event_title'>活动：<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>".$doubanElement['title']['$t']."</a></div>
				  <div class='event_initiator'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
				  <div class='start_time'>".$doubanElement['gd:when']['startTime']."</div>
				  <div class='end_time'>".$doubanElement['gd:when']['endTime']."</div>
				  <div class='event_city'>".$doubanElement['db:location']['$t']."</div>
				  <div class='event_location'>".$doubanElement['gd:where']['@valueString']."</div>
				</div>
			  </div>
			  <div id='douban_signature'>
			    <span style='float:right;'>
				  <a href='".$eventInitiator_url."' target='_blank'>
				    <img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' border=0 />
				  </a>
				</span>
				<span class='signature_text' style=' margin-right:5px; float:right;' >
				  <div style='text-align:right; height:16px;'>
				    <span >
					  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
					</span>
				  </div>
				  <div class='douban_date_drop'  style='text-align:right; height:16px;'>
				    <span>
					  <img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='/storify/img/logo_douban.png'/>
					</span>
				  </div>
				</span> 
			  </div>
			</div>
	      </li>";
		}
		else
		{
		  $douban_item_meta;
		  $douban_item_date;
		  $douban_item_author;
		  $doubanElement = $d->get_comment($douban_save_per_id);
		  $douban_per_url = $doubanElement['db:subject']['link'][1]['@href'];
		  $url_array  = explode("/", $douban_per_url);
		  $douban_item_per_id = $url_array[4];
		  if($val['content']['item_type'] == 'book')
			{
			  $douban_item_meta = $d->get_book($douban_item_per_id);
			  $douban_item_date = "出版年：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_meta = $d->get_movie($douban_item_per_id);
			  $douban_item_date = "上映日期：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_meta = $d->get_music($douban_item_per_id);
			  $douban_item_date = "发行时间：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			for($j=0;$j<count($douban_item_meta['db:attribute']); $j++)
			{
			  if($douban_item_meta['db:attribute'][$j]['@name'] == 'pubdate')
			  break;
			}
			
			$author_count = count($douban_item_meta['author']);
			$author="";
			if($author_count == 1)
			{
			  $author = $douban_item_meta['author'][0]['name']['$t'];
			}
			else if($author_count > 1)
			{
			  for($i=0; $i<$author_count; $i++)
			  {
				$author .= $douban_item_meta['author'][$i]['name']['$t']." ";
			  }
			}
			
			if($val['content']['item_type'] == 'book')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$douban_item_meta['db:attribute'][$j]['$t'];
			}
			$comment_rating = 2*$doubanElement['gd:rating']['@value'];
			$time_array = explode("T", $doubanElement['updated']['$t']);
			$content .=
			"<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
			  <div class='douban_wrapper'>
				<div>
				  <div class=item_rating>".$doubanElement['author']['name']['$t']."评分:".$comment_rating."</div>
				  <div class='comment_title' style='font-weight:bold;'>".$doubanElement['title']['$t']."</div>
				  <div class='comment_summary'>".$doubanElement['summary']['$t']."</div>
				  <div style='text-align:right;'>
					<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>查看评论全文</a>
				  </div>
				</div>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$douban_per_url."' target='_blank'><img class='item_img' src='".$doubanElement['db:subject']['link'][2]['@href']."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				<div id='douban_signature'>
				  <span style='float:right;'>
					<a href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>
					  <img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$doubanElement['author']['link'][2]['@href']."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
					</a>
				  </span>
				  <span class='signature_text' style=' margin-right:5px; float:right;' >
					<div style='text-align:right; height:16px;'>
					  <span >
						<a class='douban_from' href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>".$doubanElement['author']['name']['$t']."</a>
					  </span>
					</div>
					<div class='douban_date_drop'  style='text-align:right; height:16px;'>
					  <span> 
						<img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='/storify/img/logo_douban.png'/>
						<a>".$time_array[0]."</a>
					  </span>
					</div>
				  </span> 
				</div>
			  </div>
			</li>";
		}
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
		  <div class='avatar'><a><img style='' width='80px' height='80px' src='".$user_profile_img."'></a></div>";
	if(islogin() && $story_author != $_SESSION['uid'])
	{
	  $login_user_id = $_SESSION['uid'];
	  
	  $query="select * from ".$db_prefix."follow where user_id=".$_SESSION[uid]." and follow_id=".$story_author;
      $relationresult=$DB->query($query);
      $num=$DB->num_rows($relationresult);
	  if($num > 0)
	  {
	    $content .="<a href='#' class='follow_btn'>取消关注</a><a href='#' class='follow_btn' style='display:none;'>关注</a>";
	  }
	  else
	  {
	    $content .="<a href='#' class='follow_btn'>关注</a><a href='#' class='follow_btn' style='display:none;'>取消关注</a>";
	  }
	  
	}
    // get the following and follower info
    $following_list = getFollowing($story_author);
    $follower_list=getFollower($story_author);

	$content .="<div class='user_info'><P>".$userresult['username']."</P><P>".$userresult['intro']."</P></div>
		  <div class='usersfollowers'>
		    <span style='vertical-align:top'>粉丝</span><span style='vertical-align:top' class='count'>".sizeof($follower_list)."</span>
		    <ul class='follower_list'>";
    $usr_img;
	foreach($follower_list as $fower){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fower;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
        $content .="<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/storify/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
    }
    $content .= "</ul>
                </div>
		  <div class='usersfollowing'>
		    <span style='vertical-align:top'>关注</span><span style='vertical-align:top' class='count'>".sizeof($following_list)."</span>
			<ul class='following_list'>";
    foreach($following_list as $fowing){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fowing;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
        $content .="<li id='following_id_".$item['id']."'><a class='follow_mini_icon' href='/storify/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
    }
    $content .= "
			</ul>
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
						  if(operation_val == 'follow')
						  {
						    var temp = $('.usersfollowers .count').text();
							$('.usersfollowers .count').text(parseInt(temp)+1);
							$('.follower_list').append(data);
						  }
						  else
						  {
							var user_id='$login_user_id';
							$(\"#follower_id_\"+user_id).remove();
							var temp = $('.usersfollowers .count').text();
							$('.usersfollowers .count').text(parseInt(temp)-1);
						  }
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
        $query="select tag_id from ".$db_prefix."tag_story where story_id=".$story_id;
        $results=$DB->query($query);
        
        $query="delete from ".$db_prefix."tag_story where story_id=".$story_id;
        $DB->query($query);
        
        // delete tag if no story is bined
        while($item=$DB->fetch_array($results)){
            $query="select * from ".$db_prefix."tag_story where tag_id=".$item['tag_id'];
            $res=$DB->query($query);
            if($DB->num_rows($res) == 0){
                $query="delete from ".$db_prefix."tag where id=".$item['tag_id'];
                $DB->query($query);
            }
        }
        
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

else if(isset($_GET['user_id']))
{
  $user_id = $_GET['user_id'];
  $story_content = "<div id='userstory_container' class='inner'><div class='userstory_list'><ul>";
  $result=$DB->query("SELECT * FROM ".$db_prefix."posts where post_author='".$user_id."'");
  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$user_id."'");
  $user_profile_img = $userresult['photo'];
  while ($story_item = mysql_fetch_array($result))
  {
    //printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
	$post_id = $story_item['ID'];
	$post_title = $story_item['post_title'];
	$post_pic_url = $story_item['post_pic_url'];
	$post_status = $story_item['post_status'];
	$post_date = $story_item['post_date'];
	$temp_array = explode(" ", $story_item['post_date']);
	$post_date = $temp_array[0];
    $story_content .= "<li><div class='story_wrap'><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='/storify/member/user.php?post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='editable'>
  <div class='status'>
    <div class='".$post_status."'>
	  <div class='icon'></div>
	  <span>".$post_status."</span>
	</div>
  </div>
  <div class='actions'>
    <a id='".$post_id."' class='icon delete' title='delete' href='#'>delete</a>
	<a class='icon edit' title='Edit' href='".$rooturl."/member/index.php?post_id=".$post_id."'>edit</a>
  </div>
  <div class='clear'></div>
</div></div>
	<div class='story_meta'><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;'>".$userresult['username']."</a><a style='margin-left:65px; vertical-align:top;'>".$post_date."</a></span></div></li>";
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
	
	$('.delete').click(function(e){
	  e.preventDefault();
	  var post_id_val = $(this).attr('id');
	  var getData = {post_id: post_id_val};
	  $.get('removestory.php', getData,
	  function(data, textStatus)
	  {
		if(textStatus == 'success')
		{
          $('#'+post_id_val).closest('li').remove();
		}
	  });
	});
	
	$('.act_digg').click(function(e)
	{
	  e.preventDefault();
	  var temp_array = $(this).attr('id').split('_');
	  var post_id_val = temp_array[0]; 
	  var getData = {post_id: post_id_val};
	  $.get('diggoperation.php', getData,
	  function(data, textStatus)
	  {
		if(textStatus == 'success')
		{
		  if(data == 0)
		  {
		    alert('您已经投票过了');
		  }
		  else
		  {
			var temp = $('#'+post_id_val+'_digg_count').text();
		    $('#'+post_id_val+'_digg_count').text(1+parseInt(temp));
		  }
		}
	  });
	});
});
	
</script>

<?php
include "../include/footer.htm";
?>
