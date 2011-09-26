<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );
  
$operation=$_GET['operation'];
$startIndex = $_GET['startIndex'];
$numResults = $_GET['numResults'];

$c = new DoubanClient( DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']  );
$doubanReturn;
$eventReturn;
$eventFlag = 0;
$keywords;
if('book' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_book($keywords, $startIndex, $numResults);
}
else if('movie' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_movie($keywords, $startIndex, $numResults);
}
else if('music' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_music($keywords, $startIndex, $numResults);
}
else if('event' == $operation)
{
  $keywords = $_GET['keywords'];
  $eventReturn  = $c->search_event($keywords, $startIndex, $numResults);
  $eventFlag = 1;
}

if($eventFlag == 0)
{
	$totalResults = $doubanReturn['opensearch:totalResults']['$t'];
	foreach( $doubanReturn['entry'] as $item )
	{
	  $temp_array = explode("/", $item['id']['$t']);
	  $length = count($temp_array);
	  $douban_per_id = $temp_array[$length-1];
	  $douban_per_url = getItemLink($item['link']);
	  $url_array  = explode("/", $douban_per_url);
	  $item_type;
	  if($url_array[2] == 'book.douban.com')
	  {
		$item_type = 'book';
	  }
	  else if($url_array[2] == 'movie.douban.com')
	  {
		$item_type = 'movie';
	  }
	  else if($url_array[2] == 'music.douban.com')
	  {
		$item_type = 'music';
	  }

	  $author = getAuthors($item['author']);
	  $item_pic = getItemPic($item['link']);
	  $item_owner="";
	  $item_date="";
	  $item_review_text="";
	  $load_more_text="";
	  $pubDate = getPubDate($item['db:attribute']);
	  if('book' == $operation)
	  {
		$item_owner = "作者：".$author;
		$item_date = "出版年：".$pubDate;
		$item_review_text = "查看豆瓣书评";
		$load_more_text = "更多图书";
	  }
	  else if('movie' == $operation)
	  {
		$item_owner = "导演：".$author;
		$item_date = "上映日期：".$pubDate;
		$item_review_text = "查看豆瓣影评";
		$load_more_text = "更多电影";
	  }
	  else if('music' == $operation)
	  {
		$item_owner = "表演者：".$author;
		$item_date = "发行时间：".$pubDate;
		$item_review_text = "查看豆瓣乐评";
		$load_more_text = "更多音乐";
	  }
	 
	  $doubanContent .= 
		"<li class='douban_drag douban ".$item_type."' id='".$douban_per_id."'>
		  <div class='douban_wrapper'>
			<div class='item_info'>
			  <a href='".$douban_per_url."' target='_blank'><img class='item_img' src='".$item_pic."' style='float:left;' /></a>
			  <div class='item_meta' style='margin-left:100px;'>
				<div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$item['title']['$t']."</a></div>
				<div class='item_author'>".$item_owner."</div>
				<div class='item_date'>".$item_date."</div>
				<div class='average_rating'>豆瓣评分：".$item['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$item['gd:rating']['@numRaters']."人参与投票</div>
				<div style='text-align:right;'><a class='douban_review ".$item_type."' href='#'>".$item_review_text."</a></div>
			  </div>
			</div>
			<div class='douban_signature' style='text-align:right;'>
			  <img border='0' style='width:16px; height:16px;' src='/img/logo_douban.png'/>
			</div>
		  </div>
		</li>";
	  
	}
	if($startIndex+$numResults < $totalResults)
	{
	  $doubanContent .="<div class='loadmore ".$item_type."'><a>".$load_more_text."</a></div>";
	}
}
else if($eventFlag == 1)
{
  $totalEvents = $eventReturn['openSearch:totalResults']['$t'];
  foreach( $eventReturn['entry'] as $eventItem )
  {
	$eventImgFlag = 0;
	$userImgFlag = 0;
	$temp_array = explode("/", $eventItem['id']['$t']);
	$length = count($temp_array);
	$douban_per_id = $temp_array[$length-1];
	$eventLink = getItemLink($eventItem['link']);
	$eventTitle = $eventItem['title']['$t'];
	$eventSummary = $eventItem['summary'][0]['$t'];
	$eventContent = $eventItem['content']['$t'];
	$eventLocation = $eventItem['db:location']['$t'];
	$eventWhere = $eventItem['gd:where']['@valueString'];
	$eventStart = $eventItem['gd:when']['@startTime'];
	$eventEnd = $eventItem['gd:when']['@endTime'];
	$eventImg = getItemPic($eventItem['link']);
	$eventDetail = $c->get_event($douban_per_id);
	
	$eventInitiator_url = getAuthorLink($eventDetail['author']['link']);
	$eventInitiator_name = $eventDetail['author']['name']['$t'];
	$eventInitiator_pic = getAuthorPic($eventDetail['author']['link']);
	
	$doubanContent .=
	"<li class='douban_drag douban event' id='".$douban_per_id."'>
	  <div class='douban_wrapper'>
	    <img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='".$eventInitiator_pic."' title='".$eventInitiator_name."' alt='".$eventInitiator_name."' border=0 />
	    <div style='margin-left:36px;'>
		  <a href='".$eventInitiator_url."' target='_blank' class='douban_from' style = 'display:block;'>
		    <span>".$eventInitiator_name."</span>
		  </a>
		  <div class='event_meta'>
		    <div class='event_title'>活动：<a href='".$eventLink."' target='_blank'>".$eventTitle."</a></div>
			<div class='event_summary'>活动简介：".$eventSummary."</div>
			<div class='event_initiator'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
			<div class='start_time'>开始时间：".$eventStart."</div>
			<div class='end_time'>结束时间：".$eventEnd."</div>
			<div class='event_city'>城市：".$eventLocation."</div>
			<div class='event_location'>地点：".$eventWhere."</div>
		  </div>
		  <div class='event_img_wrapper'>
		    <a href='".$eventLink."' target='_blank'><img class='item_img' src='".$eventImg."' style='float:left;' /></a>
		  </div>
		</div>
	  </div>
	</li>";
  }
  if($startIndex+$numResults < $totalEvents)
  {
	$doubanContent .="<div class='loadmore event'><a>更多活动</a></div>";
  }
}

echo $doubanContent;

?>
