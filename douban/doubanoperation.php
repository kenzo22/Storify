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
  //fetch all comments of this item
  $commentsReturn="";
  $comments="";
  if('book' == $operation)
  {
    $commentsReturn = $c->search_book_reviews($douban_per_id);
  }
  else if('movie' == $operation)
  {
    $commentsReturn = $c->search_movie_reviews($douban_per_id);
  }
  else if('music' == $operation)
  {
    $commentsReturn = $c->search_music_reviews($douban_per_id);
  }
  
  foreach( $commentsReturn['entry'] as $commentItem )
  {
    $comments.=$commentItem['summary']['$t']."<br /><br />";
  }
  //$comments="";
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
  $doubanContent .= "<li class='douban_drag douban' id='".$douban_per_id."'><div class='douban_wrapper'><div class=douban_title>".$item['title']['$t']."</div><div class=douban_author>".$author."</div>
  <div class='douban_img'><img src='".$item['link'][2]['@href']."' /></div><div class='douban_comments'>".$comments."</div></div></li>";
}
$doubanContent .="<div class='loadmore'><a>更多</a></div>";
echo $doubanContent;

?>
