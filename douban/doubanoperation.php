<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );
  
$operation=$_GET['operation'];
//$page = $_GET['page'];

$c = new DoubanClient( DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']  );
$doubanReturn;
$keywords;
if('book' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_book($keywords);
}
else if('movie' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_movie($keywords);
}
else if('music' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_music($keywords);
}
else if('event' == $operation)
{
  $keywords = $_GET['keywords'];
  $doubanReturn  = $c->search_event($keywords);
}

foreach( $doubanReturn['entry'] as $item )
{
  $temp_array = explode("/", $item['id']['$t']);
  $length = count($temp_array);
  $douban_per_id = $temp_array[$length-1];
  $douban_per_url = $item['link'][1]['@href'];
  $author_count = count($item['author']);
  $author="";
  if($author_count == 1)
  {
    $author = $item['author'][0]['name']['$t'];
  }
  else if($author_count > 1)
  {
    for($i=0; $i<$author_count; $i++)
	{
	  $author .= $item['author'][$i]['name']['$t']." ";
	}
  }
  
  //fetch all comments of this item
  $commentsReturn="";
  $item_owner="";
  $item_date="";
  $j=0;
  for($j=0;$j<count($item['db:attribute']); $j++)
  {
	if($item['db:attribute'][$j]['@name'] == 'pubdate')
	break;
  }
  if('book' == $operation)
  {
    $item_owner = "作者：".$author;
	$item_date = "出版年：".$item['db:attribute'][$j]['$t'];
	$commentsReturn = $c->search_book_reviews($douban_per_id);
  }
  else if('movie' == $operation)
  {
    $item_owner = "导演：".$author;
	$item_date = "上映日期：".$item['db:attribute'][$j]['$t'];
	$commentsReturn = $c->search_movie_reviews($douban_per_id);
  }
  else if('music' == $operation)
  {
    $item_owner = "表演者：".$author;
	$item_date = "发行时间：".$item['db:attribute'][$j]['$t'];
	$commentsReturn = $c->search_music_reviews($douban_per_id);
  }
  
  foreach( $commentsReturn['entry'] as $commentItem )
  {
	$comment_temp_array = explode("/", $commentItem['id']['$t']);
    $comment_per_id = $comment_temp_array[count($comment_temp_array)-1];
	$fulltext_url = $commentItem['link'][1]['@href'];
	$comments_title = $commentItem['title']['$t'];
	$comments_summary = $commentItem['summary']['$t'];
	$comment_author = $commentItem['author']['name']['$t'];
	$time_array = explode("T", $commentItem['updated']['$t']);
	$doubanContent .= "<li class='douban_drag douban' id='".$comment_per_id."'><div class='douban_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
  src='".$commentItem['author']['link'][2]['@href']."' title='".$comment_author."' alt='".$comment_author."' border=0 />
  <div style='margin-left:36px;'><a href='".$commentItem['author']['link'][1]['@href']."' target='_blank' class='douban_from'
  style = 'display:block;'><span>".$comment_author."</span></a>
  <div class='douban_comments'><div class=item_rating>".$commentItem['gd:rating']['@value']."</div><div class='comment_title' style='font-weight:bold;'>".$comments_title."</div>
  <div class='comment_summary'>".$comments_summary."</div><div style='text-align:right;'><a class='comment_full_url' href='".$fulltext_url."' target='_blank'>查看评论全文</a></div>
  <div class='comment_date' style='text-align:right;'>".$time_array[0]."</div></div><div class='item_info'><a href='".$douban_per_url."' target='_blank'>
  <img class='item_img' src='".$item['link'][2]['@href']."' style='float:left;' /></a><div class='item_meta' style='margin-left:100px;'><div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$item['title']['$t']."</a></div>
  <div class='item_author'>".$item_owner."</div><div class='item_date'>".$item_date."</div><div class='average_rating'>评分：".$item['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$item['gd:rating']['@numRaters']."人参与投票</div>
  </div></div></div></div></li>";
  }
  //$doubanContent .="<div class='loadmore_comments' style='text-align:center;'><a>查看更多该条目的评论</a></div>";
}
$doubanContent .="<div class='loadmore'><a>更多</a></div>";
echo $doubanContent;

?>
