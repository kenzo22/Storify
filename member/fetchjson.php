<?php
require '../include/user_auth_fns.php';
require '../include/functions.php';
require '../include/weibo_functions.php';
require '../weibo/config.php';
require '../weibo/sinaweibo.php';
require '../tweibo/config.php';
require '../tweibo/txwboauth.php';
require '../douban/config.php';
require '../douban/doubanapi.php';
require '../include/secureGlobals.php';

header("content-type: text/javascript");

if(!isset($_GET['id']) || !isset($_GET['name']) || !isset($_GET['callback']))
{
  exit();
}
else
{
  $date_t = date("Y-m-d H:i:s");
  $weibo_access_token =           array('3dded3c1a69e0e24609b04c3bc07d3ee', 'a5a036de79ad7bb7e71446366d9c69ab', '9a0db78eaffe82ee099f17c8937f29cf');
  $weibo_access_token_secret =    array('4815f86a2f8dcbbca4a307535b1a82d8', 'ddd74ff5df9a06325822cefdec81e10e', '0175d039c755cc3b128c134f30b9af3c');
  $tweibo_access_token =          array('1fce15f8b9d3449ea9a031adf9138f95', '4fc29d6f9721471fabfb38ce56298f48');
  $tweibo_access_token_secret =   array('2a4a03d0dac0951f06d3e7b5b30a1ea0', '355354af7961e5bbc154238dca72a75a');
  
  $max = sizeof($weibo_access_token);
  $indx = rand(0,$max-1);
  $w_token = $weibo_access_token[$indx];
  $w_token_secret  = $weibo_access_token_secret[$indx];
  
  $tmax = sizeof($tweibo_access_token);
  $tindx = rand(0,$tmax-1);
  $t_token = $tweibo_access_token[$tindx];
  $t_token_secret = $tweibo_access_token_secret[$tindx];

  $c = new WeiboClient(WB_AKEY , WB_SKEY , $w_token , $w_token_secret);
  $t = new TWeiboClient(MB_AKEY , MB_SKEY , $t_token , $t_token_secret);
  $d = new DoubanClient(DB_AKEY , DB_SKEY, '', '');
  
  $user_id = intval($_GET['id']);
  $embed_name = $_GET['name'];
  $result = $DB->fetch_one_array("select * from ".$db_prefix."posts where post_author='".$user_id."' and embed_name='".$embed_name."' and post_status='Published'");
  if(!empty($result))
  {
    $post_id = $result['ID'];
    $refer_url = $_SERVER['HTTP_REFERER'];
    $temp_array = explode("/", $refer_url);
    $domain_name = $temp_array[2];
    $selResult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."pageview WHERE story_id='".$post_id."' AND domain_name='".$domain_name."'" );
    if(!empty($selResult))
    {
      $viewresult=$DB->query("update ".$db_prefix."pageview set view_count=view_count+1  WHERE story_id='".$post_id."' AND domain_name='".$domain_name."'" );
    }
    else
    {
      $viewresult=$DB->query("insert into ".$db_prefix."pageview values(null, '".$post_id."', '".$domain_name."', '".$refer_url."', 1)");
    }
	$score = getPopularScore($post_id);
	$DB->query("update ".$db_prefix."posts set popular_count='".$score."'  WHERE ID='".$post_id."'");
	
	$userresult = $DB->fetch_one_array("select username from ".$db_prefix."user where id='".$result['post_author']."'");
    $story_author = $userresult['username'];
	$story_embed = $result['embed_name'];
    $story_time = dateFormatTrans($result['post_date'],$date_t);
    $story_title=$result['post_title'];
    $story_summary=$result['post_summary'];
    $story_pic=$result['post_pic_url'];
    $story_content=$result['post_content'];
	$temp_array = json_decode($story_content, true);
	$story_content_array = $temp_array['content'];
	
	$tag_query = "select name from story_tag,story_tag_story where story_tag.id=tag_id and story_id=".$post_id;
	$tag_names = $DB->query($tag_query);
	if($DB->num_rows($tag_names) > 0)
	{
	  while($tag_name_row = $DB->fetch_array($tag_names))
	  {
		$tag_array[] = $tag_name_row['name'];
	  }
	}
	$content = "";
	$content_array[] = array();
			  
    foreach($story_content_array as $key=>$val)
    {
	  switch($val['type'])
	  {
	    case "weibo":{
		$weibo_meta_data = $val['content'];
		$weibo_per_id = $weibo_meta_data['id'];
		$single_weibo  = $c->show_status($weibo_per_id );
		$meta = array();
		$meta['type'] = 'weibo'; 
		$meta['per_id'] = $weibo_per_id;
		
		if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
			$meta['text'] = '';
            continue;
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
            $single_weibo['text'] = subs_url($single_weibo['text'],'weibo');
            $single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");
			
			$meta['text'] = $single_weibo['text'];

			$createTime = dateFormatTrans(dateFormat($single_weibo['created_at']),$date_t);
			
			$meta['time'] = $createTime;
    		if (isset($single_weibo['retweeted_status'])){
                $single_weibo['retweeted_status']['text']=subs_url($single_weibo['retweeted_status']['text']);
                $single_weibo['retweeted_status']['text']=subs_emotions($single_weibo['retweeted_status']['text'],"weibo");
				
				$meta['text'] .= "//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
				
                if(isset($single_weibo['retweeted_status']['bmiddle_pic'])){
					$meta['retweet_img'] = $single_weibo['retweeted_status']['bmiddle_pic'];
                }
				else
				{
				  $meta['retweet_img'] = '';
				}
            }
			else{
			  $meta['retweet_img'] = '';
			}
            if (isset($single_weibo['bmiddle_pic']))
			{
			  $meta['img'] = $single_weibo['bmiddle_pic'];
			}
			else
			{
			  $meta['img'] = '';
			}
			$meta['uid'] = $single_weibo['user']['id'];
			$meta['u_name'] = $single_weibo['user']['screen_name'];
			$meta['u_profile'] = $single_weibo['user']['profile_image_url'];
			$content_array[] = $meta;
		}
		break;}
		 
		case "tweibo":{
		$tweibo_meta_data = $val['content'];
		$tweibo_per_id = $tweibo_meta_data['id'];
		$tweibo_id_array[] = $tweibo_per_id;
		$meta = array();
		$meta['type'] = 'tweibo';
		$meta['per_id'] = $tweibo_per_id;
		$content_array[] = $meta;
		break;
		}
		
		case "upload_img":{
		$meta = array();
		$meta['type'] = 'upload_img';
		$meta['img_src'] = $val['content'];
        $content_array[] = $meta;
		break;}
		 
		case "douban":{
		$douban_save_per_id = $val['content']['item_id'];
		$meta = array();
		$meta['per_id'] = $douban_save_per_id;
		if($val['content']['item_type'] == 'event')
		{
		  $meta['type'] = 'douban_event';
		  $doubanElement = $d->get_event($douban_save_per_id);
		  $eventImg = getItemPic($doubanElement['link']);
		  $eventLink = getItemLink($doubanElement['link']);
		  $eventInitiator_url = getAuthorLink($doubanElement['author']['link']);
		  $eventInitiator_name = $doubanElement['author']['name']['$t'];
		  $eventInitiator_pic = getAuthorPic($doubanElement['author']['link']);
		  
		  $meta['event_title'] = $doubanElement['title']['$t'];
		  $meta['event_summary'] = $doubanElement['summary'][0]['$t'];
		  $meta['event_link'] = getItemLink($doubanElement['link']);
		  $meta['event_initiator_name'] = $doubanElement['author']['name']['$t'];
		  $meta['event_initiator_pic'] = getAuthorPic($doubanElement['author']['link']);
		  $meta['event_initiator_link'] = getAuthorLink($doubanElement['author']['link']);
		  $meta['event_pic'] = getItemPic($doubanElement['link']);
		  $meta['start_time'] = $doubanElement['gd:when']['startTime'];
		  $meta['end_time'] = $doubanElement['gd:when']['endTime'];
		  $meta['event_city'] = $doubanElement['db:location']['$t'];
		  $meta['event_location'] = $doubanElement['gd:where']['@valueString'];
		  $content_array[] = $meta;
		}
		else
		{
		  if($val['content']['item_type'] == 'bookReviews' || $val['content']['item_type'] == 'movieReviews' || $val['content']['item_type'] == 'musicReviews')
		  {
		    $meta['type'] = 'douban_review';
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
			$time_array = explode("T", $doubanElement['published']['$t']);
			
			$meta['comment_title'] = $doubanElement['title']['$t'];
			$meta['comment_summary'] = $doubanElement['summary']['$t'];
			$meta['comment_link'] = $doubanElement['link'][1]['@href'];
			$meta['comment_author'] = $doubanElement['author']['name']['$t'];
			$meta['comment_author_link'] = $comment_author_link;
			$meta['comment_author_pic'] = $comment_author_pic;
			$meta['comment_date'] = $time_array[0];
			$meta['item_author'] = $douban_item_author;
			$meta['item_date'] = $douban_item_date;
			$meta['item_link'] = getItemLink($douban_item_meta['link']);
			$meta['item_pic'] = getItemPic($douban_item_meta['link']);
			$meta['item_title'] = $douban_item_meta['title']['$t'];
			$meta['rating'] = $comment_rating;
			$meta['average_rating'] = $douban_item_meta['gd:rating']['@average'];
			$meta['num_raters'] = $douban_item_meta['gd:rating']['@numRaters'];
			$content_array[] = $meta;
		  }
		  else if($val['content']['item_type'] == 'book' || $val['content']['item_type'] == 'movie' || $val['content']['item_type'] == 'music')
		  {
		    $meta['type'] = 'douban_item';
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
			$meta['item_author'] = $douban_item_author;
			$meta['item_date'] = $douban_item_date;
			$meta['item_link'] = getItemLink($douban_item_meta['link']);
			$meta['item_pic'] = getItemPic($douban_item_meta['link']);
			$meta['item_title'] = $douban_item_meta['title']['$t'];
			$meta['average_rating'] = $douban_item_meta['gd:rating']['@average'];
			$meta['num_raters'] = $douban_item_meta['gd:rating']['@numRaters'];
			$content_array[] = $meta;
		  }
		}
		break;}
		 
		case "comment":{
		$comment_text = $val['content'];
		$meta = array();
		$meta['type'] = 'comment';
		$meta['text'] = $comment_text;
		$content_array[] = $meta;
		break;}
		 
		case "video":{
		$video_meta = $val['content'];
		$meta = array();
		$meta['type'] = 'video';
		$meta['title'] = $video_meta['title'];
		$meta['url'] = $video_meta['url'];
		$meta['src'] = $video_meta['src'];
		$content_array[] = $meta;
		break;}
		
		case "feed":{
		$meta = array();
		$meta['type'] = 'feed';
		$feed_meta = $val['content'];
		$meta['title'] = $feed_meta['title'];
        $meta['link'] = $feed_meta['link'];
		$meta['desc'] = $feed_meta['desc'];
		$meta['author'] = $feed_meta['author'];
		$meta['rtitle'] = $feed_meta['rtitle'];
		$meta['rlink'] = $feed_meta['rlink'];
		$content_array[] = $meta;
		break;}
		 
		case "photo":{
		$photo_meta_data = $val['content'];
		$photo_title = $photo_meta_data['title'];
		$photo_author = $photo_meta_data['author'];
		$photo_per_url = $photo_meta_data['url'];
		$photo_id = $photo_meta_data['id'];
		$author_nic = $photo_meta_data['nic'];
		$meta = array();
		$meta['type'] = 'photo';
		$meta['title'] = $photo_meta_data['title'];
		$meta['author'] = $photo_meta_data['author'];
		$meta['photo_url'] = $photo_meta_data['url'];
		$meta['photo_id'] = $photo_meta_data['id'];
		$meta['author_nic'] = $photo_meta_data['nic'];
		$meta['photo_link'] = "http://www.yupoo.com/photos/".$photo_author."/".$photo_id;
		$content_array[] = $meta;
		break;
		}
		 
		default:
		break;
	  }
	}
	if(count($tweibo_id_array) > 0)
	{
	  $tweibo_ids = implode(",", $tweibo_id_array);
	  $tweibo  = $t->t_list($tweibo_ids);
	  $info = $tweibo['data']['info'];
	  foreach( $info as $item )
	  {
		$time = getdate($item['timestamp']);
		$create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
		$create_time = dateFormatTrans($create_time, $date_t);
		$profileImgUrl = $item['head']."/50";
		
		$t_meta = array();
		$t_meta['type'] = 'tweibo';
		$t_meta['per_id'] = $item['id'];
		$t_meta['time'] = $create_time;
		$t_meta['u_profile'] = $profileImgUrl;
		
		$item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);
		$item['text'] = subs_emotions($item['text'],"tweibo");
		$t_meta['text'] = $item['text'];
		$t_meta['img'] = '';
		$t_meta['retweet_img'] = '';
		
		$t_meta['u_nick'] = $item['nick'];
		$t_meta['u_name'] = $item['name'];

		if(isset($item['source'])){
			$item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
			$item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");

			if($item['source']['text'] == null)
				$item['source']['text'] = "此微博已被原作者删除。";
			
			$t_meta['text'] .= "||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text'];
			
			if(isset($item['source']['image'])){
				foreach($item['source']['image'] as $re_img_url){
					$t_meta['retweet_img'] .= $re_img_url."/240 ";
				}
			}
		}else{
			if(isset($item['image'])){
				foreach($item['image'] as $img_url){
					$t_meta['img'] .= $img_url."/240 ";
				}
			}
		}
		$t_meta_array[] = $t_meta;
	  }
	  $content_array_length = count($content_array);
	  $t_meta_array_length = count($t_meta_array);
	  for($i=0; $i<$content_array_length; $i++)
	  {
	    if($content_array[$i]['type'] == 'tweibo')
		{
		  for($j=0; $j<$t_meta_array_length; $j++)
		  {
		    if($content_array[$i]['per_id'] == $t_meta_array[$j]['per_id'])
		    {
		      $content_array[$i] = array_merge($content_array[$i], $t_meta_array[$j]);
		    }
		  }
		}
	  }
	}
  }
  
  $obj->id = $user_id;
  $obj->author = $story_author;
  $obj->title = $story_title;
  $obj->summary = $story_summary;
  $obj->pic = $story_pic;
  $obj->time = $story_time;
  $obj->embed = $story_embed;
  $obj->tags = $tag_array;
  $obj->content_array = $content_array;

  echo $_GET['callback']. '(' . json_encode($obj) . ')';
}
?>
