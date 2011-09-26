<?php
include "../global.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include_once "userrelation.php";
?>
<link type="text/css" href="../css/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="../css/jquery.ui.button.css" rel="stylesheet" />

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
	  $content = "<div id='story_container'><div class='digg_wrap'><div id='".$post_id."_digg_count' style='margin-top:10px;'>".$story_digg_count."</div><a id='".$post_id."_act_digg' class='act_digg'><img src='../img/ding.ico' /></a></div><div id='publish_container' class='showborder'>";
	}
	else
	{
	  if(0 == strcmp($story_status, 'Published'))
	  {
	    $content = "<div id='story_container'>
					  <div class='published-steps'>
						<div class='tabs'>
						  <button class='post-tab'>
							<div class='icon'></div>
							<h2>发布到您的网站上</h2>
							<span>嵌入故事，如此的简单</span>
						  </button>
						  <button class='notify-tab'>
							<div class='icon'></div>
							<h2>通告</h2>
							<span>喝水不忘挖井人</span>
						  </button>
						  <button class='share-tab'>
							<div class='icon'></div>
							<h2>分享</h2>
							<span>好故事当然要分享</span>
						  </button>
						</div>
						<div class='steps'>
						  <div class='post-content'>
						  </div>
						  <div id='weibo_card_area' class='notify-content'>
						  </div>
						  <div class='share-content'>
						    <div id='jiathis_style_32x32'>
							  <a class='jiathis_button_qzone'></a><a class='jiathis_button_tsina'></a>
							  <a class='jiathis_button_tqq'></a>
							  <a class='jiathis_button_renren'></a><a class='jiathis_button_kaixin001'></a>
							  <a href='http://www.jiathis.com/share' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a>
							  <a class='jiathis_counter_style'></a>
							</div>
						  </div>
						</div>
						<div class='spacer'></div>
					  </div>";
		$content .= "<div class='digg_wrap'><div id='".$post_id."_digg_count' style='margin-top:10px;'>".$story_digg_count."</div><a id='".$post_id."_act_digg' class='act_digg'><img src='../img/ding.ico' /></a></div><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>已发布</span><span class='float_r'><a href='#'><img src='../img/guangbo.ico' title='通告' style='width:16px; height:16px;'/>
			  </a>&nbsp<a href='/member/user.php?post_id=".$post_id."&action=remove'><img src='../img/delete.gif' title='删除' style='width:16px; height:16px;'/></a>&nbsp<a href='/member/user.php?post_id=".$post_id."&action=edit'><img src='../img/edit.png' title='编辑' style='width:16px; height:16px;'/></a></span></div>";
	  }
	  else
	  {
	    $content = "<div id='story_container'><div id='publish_container' class='showborder'>
			  <div id='story_action'><span>草稿</span><span class='float_r'><a href='/member/user.php?post_id=".$post_id."&action=remove'><img src='../img/delete.gif' title='删除' style='width:16px; height:16px;'/>
			  </a>&nbsp<a href='/member/user.php?post_id=".$post_id."&action=edit'><img src='../img/edit.png' title='编辑' style='width:16px; height:16px;'/></a>&nbsp&nbsp<a href='/member/user.php?post_id=".$post_id."&action=publish'><img src='../img/publish.ico' title='发布' style='width:16px; height:16px;'/></a></span></div>";
	  }	
	}

    // get tags for this story
    $tag_query = "select name from story_tag,story_tag_story where story_tag.id=tag_id and story_id=".$post_id;
    $tag_names = $DB->query($tag_query);
    if($DB->num_rows($tag_names) > 0){
        while($tag_name_row = $DB->fetch_array($tag_names)){
            $tags .= $tag_name_row['name']." ";
        }
    }

	$content .="<div id='story_header' style='margin:0; padding:0;'><div style='float:right; padding: 10px 10px 0 0'><img src='".$story_pic."' style='width:60px; height:60px;' /></div><div style='padding-left:20px;'><h2>".$story_title."</h2></div>
			  <div style='padding-left:20px;'>".$userresult['username']."</div>
			  <div style='padding-left:20px; '>".$story_summary."</div>
              <div style='padding-left:20px; border-bottom:1px solid #C9C9C9;'>".$tags."</div>
			  </div><ul id='weibo_ul' style='padding:0;'>";
	
	foreach($story_content_array as $key=>$val)
	{
	  switch($val['type'])
	  {
	    case "weibo":{
		$weibo_per_id = $val['content'];
		$single_weibo  = $c->show_status($weibo_per_id );
		
		if ($single_weibo === false || $single_weibo === null){
		echo "<br/><br/><br/><br/><br/>Error occured";
		//return false;
		}
		if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
            // skip deleted weibo
            $content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>此微博已被删除</span></div>";
			//$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>errorcode:".$single_weibo['error_code']."error".$single_weibo['error']."</span></div>";
            continue;
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
            
            // show emotions in text
            $single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");

			$createTime = dateFormat($single_weibo['created_at']);
			$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>".$single_weibo['text'];
    		if (isset($single_weibo['retweeted_status'])){
                
                // show emotions in text
                $single_weibo['retweeted_status']['text']=subs_emotions($single_weibo['retweeted_status']['text'],"weibo");

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
			<img border='0' style='position:relative; top:2px' src='../img/sina16.png'/><a>".$createTime."</a></span></div></span> </div></div></li>";
		}
		break;}
		 
		case "tweibo":{
		$tweibo_per_id = $val['content'];
		$tweibo_id_array[] = $tweibo_per_id;
		$content .="<li class='weibo_drop tencent' id='$tweibo_per_id' style='border:none;'></li>"; 
		break;}
		 
		case "douban":{
		$douban_save_per_id = $val['content']['item_id'];
		if($val['content']['item_type'] == 'event')
		{
		  $doubanElement = $d->get_event($douban_save_per_id);
		  $eventImg = getItemPic($doubanElement['link']);
		  $eventLink = getItemLink($doubanElement['link']);
		  $eventInitiator_url = getAuthorLink($doubanElement['author']['link']);
		  $eventInitiator_name = $doubanElement['author']['name']['$t'];
		  $eventInitiator_pic = getAuthorPic($doubanElement['author']['link']);
		  
		  $content .=
		 "<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
		    <div class='douban_wrapper'>
			  <div class='event_summary'>".$doubanElement['summary'][0]['$t']."</div>
			  <div style='margin-top:10px; overflow:auto;'>
			    <a href='".$doubanElement['link'][1]['@href']."' target='_blank'>
				  <img class='item_img' src='".$eventImg."' style='float:left;' />
				</a>
				<div class='item_meta' style='margin-left:220px;'>
				  <div class='event_title'>活动：<a href='".$eventLink."' target='_blank'>".$doubanElement['title']['$t']."</a></div>
				  <div class='event_initiator'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
				  <div class='start_time'>".$doubanElement['gd:when']['startTime']."</div>
				  <div class='end_time'>".$doubanElement['gd:when']['endTime']."</div>
				  <div class='event_city'>".$doubanElement['db:location']['$t']."</div>
				  <div class='event_location'>".$doubanElement['gd:where']['@valueString']."</div>
				</div>
			  </div>
			  <div id='douban_signature' style='overflow:auto;'>
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
					  <img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='../img/logo_douban.png'/>
					</span>
				  </div>
				</span> 
			  </div>
			</div>
	      </li>";
		}
		else
		{
		  if($val['content']['item_type'] == 'bookReviews' || $val['content']['item_type'] == 'movieReviews' || $val['content']['item_type'] == 'musicReviews')
		  {
		    $douban_item_meta;
		    $douban_item_date;
		    $douban_item_author;
		    $doubanElement = $d->get_comment($douban_save_per_id);
			
			$comment_author_link = getAuthorLink($doubanElement['author']['link']);
			$comment_author_pic = getAuthorPic($doubanElement['author']['link']);
			$itemPic = getItemPic($doubanElement['db:subject']['link']);
			
		    $douban_per_url = getItemLink($doubanElement['db:subject']['link']);
		    $url_array  = explode("/", $douban_per_url);
		    $douban_item_per_id = $url_array[4];
		    if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_meta = $d->get_book($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_meta = $d->get_movie($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_meta = $d->get_music($douban_item_per_id);
			}
			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$author = getAuthors($douban_item_meta['author']);
			
			if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
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
				  <a href='".$douban_per_url."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				<div id='douban_signature' style='overflow:auto;'>
				  <span style='float:right;'>
					<a href='".$comment_author_link."' target='_blank'>
					  <img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$comment_author_pic."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
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
						<img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='../img/logo_douban.png'/>
						<a>".$time_array[0]."</a>
					  </span>
					</div>
				  </span> 
				</div>
			  </div>
			</li>";
		  }
		  else if($val['content']['item_type'] == 'book' || $val['content']['item_type'] == 'movie' || $val['content']['item_type'] == 'music')
		  {
		    if($val['content']['item_type'] == 'book')
			{
			  $douban_item_meta = $d->get_book($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_meta = $d->get_movie($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_meta = $d->get_music($douban_save_per_id);
			}
			
			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$itemPic = getItemPic($douban_item_meta['link']);
			$itemLink = getItemLink($douban_item_meta['link']);
			$author = getAuthors($douban_item_meta['author']);
			
			if($val['content']['item_type'] == 'book')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
			}
			$content .=
			"<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
			  <div class='douban_wrapper'>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				<div class='douban_signature' style='text-align:right; overflow:auto;'>
				  <img border='0' style='width:16px; height:16px;' src='../img/logo_douban.png'/>
				</div>
			  </div>
			</li>";
		  }
		}
		break;}
		 
		case "comment":{
		$comment_text = $val['content'];
		$content .="<li class='textElement'><div class='commentBox'>".$comment_text."</div></li>";	
		break;}
		 
		case "video":{
		$video_url = $val['content'];
		$content .="<li class='video_element'><div><a class='videoTitle' target='_blank' href='".$video_url."'></a></div></li>";
		break;}
		 
		case "photo":{
		$photo_meta_data = $val['content'];
		$photo_title = $photo_meta_data['title'];
		$photo_author = $photo_meta_data['author'];
		$photo_per_url = $photo_meta_data['url'];
		$content .="<li class='photo_element'><div style='margin:0px auto; text-align:center; border: 5px solid #FFFFFF; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4); max-width: 260px;'><img src='"
				.$photo_per_url."'/><div class='pic_title' style='line-height:1.5;'>".$photo_title."</div><div class='pic_author' style='line-height:1.5;'>".$photo_author."</div></div></li>";	 
		break;}
		 
		default:
		break;
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
		  <div class='avatar'><a href='/member/user.php?user_id=".$story_author."'><img style='' width='80px' height='80px' src='".$user_profile_img."'></a></div>";
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

	$content .="<div class='user_info'><a href='/member/user.php?user_id=".$story_author."'><P>".$userresult['username']."</P></a><P>".$userresult['intro']."</P></div>
		  <div class='usersfollowers'>
		    <span style='vertical-align:top'>粉丝</span><span style='vertical-align:top' class='count'>".sizeof($follower_list)."</span>
		    <ul class='follower_list'>";
    $usr_img;
	foreach($follower_list as $fower){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fower;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
        $content .="<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
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
        $content .="<li id='following_id_".$item['id']."'><a class='follow_mini_icon' href='/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
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
				  var temp = first_item_val - 1;
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
						$('#weibo_ul li:gt('+temp+')').each(function()
						  {
							if($(this).hasClass('sina'))
							{
							  var id_val = $(this).attr('id');
							  WB2.anyWhere(function(W){
							  W.widget.hoverCard({
								id: id_val,
								search: true
								}); 
							  });
							}
						  });
					}
					});
				});
				
			  $('#weibo_ul li.sina').each(function()
			  {
				var id_val = $(this).attr('id');
				WB2.anyWhere(function(W){
				W.widget.hoverCard({
					id: id_val,
					search: true
					}); 
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
	  go('/member/user.php?user_id='.$_SESSION['uid']);
	}
	else if(0 == strcmp($story_action, 'edit'))
	{
	  go('/member/index.php?post_id='.$story_id);
	}
	else if(0 == strcmp($story_action, 'publish'))
	{
	  $result=$DB->query("update ".$db_prefix."posts set post_status='published'  WHERE ID='".$story_id."'");
	  go('/member/user.php?post_id='.$story_id);
	}
	
	{
	  throw new Exception('Undefined story action.');
	}
}

else if(isset($_GET['user_id']))
{
  $tbl_name="story_posts";
  // How many adjacent pages should be shown on each side?
  $adjacents = 3;
  $user_id = $_GET['user_id'];
  $story_content = "<div id='userstory_container' class='inner'><div class='userstory_list'><ul>";
  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$user_id."'");
  $user_profile_img = $userresult['photo'];
  
    $query = "SELECT COUNT(*) as num FROM $tbl_name where post_author='".$user_id."'";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	$targetpage = "user.php?user_id=".$user_id; 
	$limit = 12; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM $tbl_name where post_author='".$user_id."'LIMIT $start, $limit";
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
			$pagination.= "<a href=\"$targetpage&page=$prev\">« 前页</a>";
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
					$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&page=$next\">后页 »</a>";
		else
			$pagination.= "<span class=\"disabled\">后页 »</span>";
		$pagination.= "</div>\n";		
	}
  
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
    $story_content .= "<li><div class='story_wrap'><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='/member/user.php?post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='editable'>
  <div class='status'>
    <div class='".$post_status."'>
	  <div class='icon'></div>
	  <span>".$post_status."</span>
	</div>
  </div>";
  if(islogin() && $user_id == $_SESSION['uid'])
  {
    $story_content .="
    <div class='actions'>
      <a id='".$post_id."' class='icon delete' title='删除' href='#'><img src='../img/delete.gif' style='width:16px; height:16px;'/></a>
	  <a class='icon edit' title='编辑' href='/member/index.php?post_id=".$post_id."'><img src='../img/edit.png' style='width:16px; height:16px;'/></a>
    </div>";
  }
   $story_content .="<div class='clear'></div></div></div>
	<div class='story_meta'><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;'>".$userresult['username']."</a><a style='margin-left:65px; vertical-align:top;'>".$post_date."</a></span></div></li>";
  }

  $story_content .="</ul></div>".$pagination."</div>";
  echo $story_content;
}
?>

<script type="text/javascript">

Array.prototype.getUnique = function()
{
  var o = {};
  var i, e;
  for (i=0; e=this[i]; i++) {o[e]=1};
  var a=new Array();
  for (e in o)
  {a.push (e)};
  return a;
} 

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
	$('.video_element').each(function()
	{
	  var videoUrl = $(this).find('.videoTitle').attr('href');
	  append_video_content(videoUrl);
	});
	
	$('.delete').click(function(e){
	  e.preventDefault();
	  
	  var r=confirm("确定删除这个故事吗?");
	  if (r==true)
	  {
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
	  }
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
	
	$('.published-steps .notify-tab').toggle(function(){
	    var item_user_name;
		var sina_user_array = [];
	    var tencent_user_array = [];
		var sina_array_length = sina_user_array.length;
		var tencent_array_length = tencent_user_array.length;
		$('#weibo_ul li.sina, #weibo_ul li.tencent').each(function(index)
		{
		  if($(this).hasClass('sina'))
		  {
		    item_user_name = $(this).find('.weibo_from').text();
		    sina_user_array[sina_array_length] = item_user_name;
		    sina_array_length++;
		  }
		  else
		  {
		    item_user_name = $(this).find('.weibo_from').text();
		    tencent_user_array[tencent_array_length] = item_user_name;
		    tencent_array_length++;
		  }
		});
		sina_user_array = sina_user_array.getUnique();
		tencent_user_array = tencent_user_array.getUnique();
		var sina_u_length = sina_user_array.length;
		var tencent_u_length = tencent_user_array.length;
		var x, y;
		var notifyContent="<h2>告诉作者你引用了他们的内容</h2>";
		if(sina_u_length > 0)
		{
		  notifyContent +="<div class='sina_user'><img border='0' src='../img/sina16.png' style='float:left; position:relative; top:2px' />";
		  for (x=0; x<sina_u_length; x++)
		  {
		    var username = sina_user_array[x];
		    notifyContent += "<div class='notify-user'><input type='checkbox' value='mashable' name='to[]' checked='checked'><span>@"+username+"</span></div>";
		  }
		  notifyContent +="</div>"
		}
		
		if(tencent_u_length > 0)
		{
		  notifyContent +="<div class='tencent_user' style='clear:both;'><img border='0' src='../img/tencent16.png' style='float:left; position:relative; top:4px' />";
		  for (y=0; y<tencent_u_length; y++)
		  {
		    var username = tencent_user_array[y];
		    notifyContent += "<div class='notify-user'><input type='checkbox' value='mashable' name='to[]' checked='checked'><span>@"+username+"</span></div>";
		  }
		  notifyContent +="</div>"
		}
		
		notifyContent +="<textarea class='notify-tweet' maxlength='120' name='tweet'>我刚刚引用了你的微博, 来看一看吧: </textarea><div class='tweet_control'>还可以输入<span>60</span>字<input class='tweet_btn' style='margin-left:15px; cursor:pointer;' type='submit' value='发布'></div>";
		
		$('.notify-content').html(notifyContent);
	    $('.steps .notify-content').css('display', 'block');
	    $('.published-steps .spacer').css('display', 'none');
		WB2.anyWhere(function(W){
				W.widget.hoverCard({
					id: 'weibo_card_area',
					search: true
					}); 
				});
	},
	function(){
	  $('.steps .notify-content').css('display', 'none');
	  $('.published-steps .spacer').css('display', 'block');
	});
	
	$('.published-steps .share-tab').toggle(function(){
	  $('.steps .share-content').css('display', 'block');
	  $('.published-steps .spacer').css('display', 'none');
	},
	function(){
	  $('.steps .share-content').css('display', 'none');
	  $('.published-steps .spacer').css('display', 'block');
	});
	
	$('.tweet_btn').live('click', function(e){
	  e.preventDefault();
	  var weibo_content_val = '';
	  var tweibo_content_val = '';
	  $('.sina_user .notify-user span').each(function()
	  {
	    weibo_content_val += $(this).text()+' ';
	  });
	  $('.tencent_user .notify-user span').each(function()
	  {
	    tweibo_content_val += $(this).text()+' ';
	  });
	  
	  if(tweibo_content_val != '')
	  {
	      tweibo_content_val += $('.notify-tweet').val();
		  var postUrl;
		  var postData;
		  postUrl = '../tweibo/posttweibo.php';
		  postData = {weibo_content: tweibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	  
	  if(weibo_content_val != '')
	  {
	      weibo_content_val += $('.notify-tweet').val();
		  var postUrl;
		  var postData;
		  postUrl = '../weibo/postweibo.php';
		  postData = {weibo_content: weibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	});
});
	
</script>

<script type='text/javascript' src='../js/jquery-ui-1.8.12.custom.min.js'></script>
<script type="text/javascript" src="../js/jquery.embedly.min.js"></script>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>

<?php
include "../include/footer.htm";
?>
