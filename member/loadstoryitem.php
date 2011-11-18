<?php
include "../connect_db.php";
include "../include/functions.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include '../include/secureGlobals.php';

$date_t = date("Y-m-d H:i:s");

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
$t = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
$d = new DoubanClient( DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']  );
$post_id = $_POST['post_id'];
$first_item = intval($_POST['first_item']);
$result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
if(!$result)
{
  throw new Exception('Could not execute query.');
}
$items_perpage = 10;
$story_content=$result['post_content'];
$temp_array = json_decode($story_content, true);
$story_content_array = array_slice($temp_array['content'], $first_item, $items_perpage, true);
$tweibo_id_array = array();

$content = '';
foreach($story_content_array as $key=>$val)
{
  switch($val['type'])
  {
	case "weibo":{
	$weibo_meta_data = $val['content'];
	$weibo_per_id = $weibo_meta_data['id'];
	$single_weibo  = $c->show_status($weibo_per_id );
	
	if ($single_weibo === false || $single_weibo === null){
	echo "<br/><br/><br/><br/><br/>Error occured";
	//return false;
	}
	if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
		// skip deleted weibo
		$content .="<li class='weibo_drop sina' id='w_".$weibo_per_id."' style='border:none;'><div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被删除</span></div>";
		//$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>errorcode:".$single_weibo['error_code']."error".$single_weibo['error']."</span></div>";
		continue;
	}
	if (isset($single_weibo['id']) && isset($single_weibo['text'])){
		
		// show emotions in text
		$single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");

		$single_weibo['text'] = subs_url($single_weibo['text'],'weibo');

		$createTime = dateFormatTrans(dateFormat($single_weibo['created_at']),$date_t);
		$content .="<li class='weibo_drop sina' id='w_".$weibo_per_id."' style='border:none;'>";
		if (isset($single_weibo['retweeted_status'])){
			
			$content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div>
			<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
			// show emotions in text
			$single_weibo['retweeted_status']['text']=subs_emotions($single_weibo['retweeted_status']['text'],"weibo");

			$single_weibo['retweeted_status']['text']=subs_url($single_weibo['retweeted_status']['text']);

			$content .="//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
			if(isset($single_weibo['retweeted_status']['bmiddle_pic'])){
				$content .= "</span><div class='weibo_retweet_img' style='text-align:center;'><img src='".$single_weibo['retweeted_status']['bmiddle_pic']."' width='280px;' /></div>";
			}
			else
			{
			  $content .= "</span>";
			}
		}
		else{
		  $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div><div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text']."</span>";
		}
		if (isset($single_weibo['bmiddle_pic']))
		{
		  $content .= "<div class='weibo_img' style='text-align:center;'><img src='".$single_weibo['bmiddle_pic']."' width='280px;' /></div>";
		}
		$content .= "</div>";
		$content .= "<div class='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img_drop' src='"
		.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span class='signature_text'><div style='float:right; height:16px;'>
		<span><a class='weibo_from_drop' href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'>".$createTime."</div></span> </div></div></li>";
	}
	break;}
	 
	case "tweibo":{
	$tweibo_meta_data = $val['content'];
	$tweibo_per_id = $tweibo_meta_data['id'];
	$tweibo_id_array[] = $tweibo_per_id;
	$content .="<li id='t_".$tweibo_per_id."'></li>"; 
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
	 "<li class='douban_drop douban' id='d_".$douban_save_per_id."' style='border:none;'>
		<div class='douban_wrapper'>
		  <div class='content_wrapper'>
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
		  </div>
		  <div id='douban_signature'>
			<span style='float:right;'>
			  <a href='".$eventInitiator_url."' target='_blank'>
				<img class='profile_img_drop' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' border=0 />
			  </a>
			</span>
			<span class='signature_text'>
			  <div style='float:right; height:16px;'>
				<span >
				  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
				</span>
			  </div>
			  <div class='douban_date_drop'></div>
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
		"<li class='douban_drop douban' id='d_".$douban_save_per_id."' style='border:none;'>
		  <div class='douban_wrapper'>
		    <div class='content_wrapper'>
			<div>
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
				<div class=item_rating>".$doubanElement['author']['name']['$t']."评分:".$comment_rating."</div>
				<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
			  </div>
			</div>
			</div>
			<div id='douban_signature'>
			  <span style='float:right;'>
				<a href='".$comment_author_link."' target='_blank'>
				  <img class='profile_img_drop' src='".$comment_author_pic."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
				</a>
			  </span>
			  <span class='signature_text'>
				<div style='float:right; height:16px;'>
				  <span >
					<a class='douban_from' href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>".$doubanElement['author']['name']['$t']."</a>
				  </span>
				</div>
				<div class='douban_date_drop'>".$time_array[0]."</div>
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
		"<li class='douban_drop douban' id='d_".$douban_save_per_id."' style='border:none;'>
		  <div class='douban_wrapper'>
		    <div class='content_wrapper'>
			<div class='item_info' style='overflow:auto;'>
			  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
			  <div class='item_meta' style='margin-left:100px;'>
				<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
				<div class='item_author'>".$douban_item_author."</div>
				<div class='item_date'>".$douban_item_date."</div>
				<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
			  </div>
			</div>
			</div>
			<div class='douban_sig_logo'></div>
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
	$video_meta = $val['content'];
	$video_title = $video_meta['title'];
	$video_src = $video_meta['src'];
	$video_url = $video_meta['url'];
	$content .="<li class='video_element'><div><a class='videoTitle' target='_blank' href='".$video_url."'>".$video_title."</a></div><div class='embed'>
	<embed src='".$video_src."' quality='high' width='420' height='340' align='middle' allowscriptaccess='always' allowfullscreen='true' mode='transparent' type='application/x-shockwave-flash' wmode='opaque'></embed></div></li>";
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
  $tweibo  = $t->t_list($tweibo_ids);
  $info = $tweibo['data']['info'];
  $tweiboContent = "";
  foreach( $info as $item )
  {
	$time = getdate($item['timestamp']);
	$create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
	$create_time = dateFormatTrans($create_time, $date_t);
	$profileImgUrl = $item['head']."/50";
	
	$item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);
	$item['text'] = subs_emotions($item['text'],"tweibo");

	$tweiboContent .="<li id='t_".$item['id']."'>";

	if(isset($item['source'])){
		$tweiboContent .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
		<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text'];
		$item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
		$item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");

		if($item['source']['text'] == null)
			$item['source']['text'] = "此微博已被原作者删除。";
		$tweiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span></div>";
		if(isset($item['source']['image'])){
			foreach($item['source']['image'] as $re_img_url){
				$tweiboContent .="<div class='weibo_retweet_img_drop'><img src='".$re_img_url."/240' /></div>";
			}
		}
	}else{
		$tweiboContent .= "<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
		<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text']."</span></div>";
		if(isset($item['image'])){
			foreach($item['image'] as $img_url){
				$tweiboContent .="<div class='weibo_img_drop'><img src='".$img_url."/240' /></div>";
			}
		}
	}
	$tweiboContent .= "<div class='story_signature'><span style='float:right;'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' src='"
	.$profileImgUrl."' alt='".$item['nick']."' border=0 /></a></span><span class='signature_text'><div style='float:right; height:16px; '>
	<span ><a class='weibo_from_drop' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'>".$create_time."</div></span></div></div></li>tweibo_sep";
  }
  $tweibo_array = explode("tweibo_sep", $tweiboContent);
  $tweibo_array_len = count($tweibo_array);
  $tweibo_array_asoc = array();
  for($i=0; $i<$tweibo_array_len-1; $i++)
  {
	$temp_t = $tweibo_array[$i];
	$first_q = strpos($temp_t, "'");
	$second_q = strpos(substr($temp_t, $first_q+1), "'");
	$t_per_id = substr($temp_t, $first_q+1, $second_q);
	$first_t = strpos($temp_t, ">");
	$second_t = strpos($temp_t, "</li>");
	$tweibo_array_asoc[$t_per_id] = substr($temp_t, $first_t+1, $second_t-$first_t-1);
  }
  foreach($tweibo_array_asoc as $tkey=>$tval)
  {
	$content = str_replace("<li id='$tkey'>","<li class='weibo_drop tencent' id='$tkey' style='border:none;'>".$tval, $content);
  }
}

if((count($temp_array['content'])-$first_item) > $items_perpage)
{
  $next_item_id = $first_item + $items_perpage;
  $content .="<div id='more' style='text-align:center;'><a id='".$next_item_id."_post_".$post_id."' class='load_more' href='#'>更多</a></div>";
}
echo $content;
?>
