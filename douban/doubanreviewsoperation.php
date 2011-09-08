<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );
  
$operation=$_GET['operation'];
$subjectID=$_GET['subjectID'];
$startIndex = $_GET['startIndex'];
$numResults = $_GET['numResults'];

$c = new DoubanClient(DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']);

$doubanCommentsReturn;
$doubanItemMeta;
$douban_item_date;
$douban_item_author;
$item_type;
$doubanContent= "";

if('bookReviews' == $operation)
{
  $item_type = 'bookReviews';
  $doubanCommentsReturn  = $c->search_book_reviews($subjectID, $startIndex, $numResults);
  $doubanItemMeta = $c->get_book($subjectID);
}
else if('movieReviews' == $operation)
{
  $item_type = 'movieReviews';
  $doubanCommentsReturn  = $c->search_movie_reviews($subjectID, $startIndex, $numResults);
  $doubanItemMeta = $c->get_movie($subjectID);
}
else if('musicReviews' == $operation)
{
  $item_type = 'musicReviews';
  $doubanCommentsReturn  = $c->search_music_reviews($subjectID, $startIndex, $numResults);
  $doubanItemMeta = $c->get_music($subjectID);
}

for($j=0;$j<count($doubanItemMeta['db:attribute']); $j++)
{
  if($doubanItemMeta['db:attribute'][$j]['@name'] == 'pubdate')
  break;
}

for($k=0;$k<count($doubanItemMeta['link']); $k++)
{
  if($doubanItemMeta['link'][$k]['@rel'] == 'image')
  break;
}

for($t=0;$t<count($doubanItemMeta['link']); $t++)
{
  if($doubanItemMeta['link'][$t]['@rel'] == 'alternate')
  break;
}

$author_count = count($doubanItemMeta['author']);
$author="";
if($author_count == 1)
{
  $author = $doubanItemMeta['author'][0]['name']['$t'];
}
else if($author_count > 1)
{
  for($i=0; $i<$author_count; $i++)
  {
	$author .= $doubanItemMeta['author'][$i]['name']['$t']." ";
  }
}

if('bookReviews' == $operation)
{
  $item_owner = "作者：".$author;
  $item_date = "出版年：".$item['db:attribute'][$j]['$t'];
  $load_more_text = "更多书评";
}
else if('movieReviews' == $operation)
{
  $item_owner = "导演：".$author;
  $item_date = "上映日期：".$item['db:attribute'][$j]['$t'];
  $load_more_text = "更多影评";
}
else if('musicReviews' == $operation)
{
  $item_owner = "表演者：".$author;
  $item_date = "发行时间：".$item['db:attribute'][$j]['$t'];
  $load_more_text = "更多乐评";
}

$totalCommentsNum = $doubanCommentsReturn['opensearch:totalResults']['$t'];
foreach( $doubanCommentsReturn['entry'] as $commentItem )
{
  $comment_temp_array = explode("/", $commentItem['id']['$t']);
  $comment_per_id = $comment_temp_array[count($comment_temp_array)-1];
  $fulltext_url = $commentItem['link'][1]['@href'];
  $comments_title = $commentItem['title']['$t'];
  $comments_summary = $commentItem['summary']['$t'];
  $comment_author = $commentItem['author']['name']['$t'];
  $comment_rating = 2*$commentItem['gd:rating']['@value'];
  $time_array = explode("T", $commentItem['updated']['$t']);

  $doubanContent.=
		"<li class='douban_drag douban ".$item_type."' id='".$comment_per_id."'>
		  <div class='douban_wrapper'>
			<img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='".$commentItem['author']['link'][2]['@href']."' title='".$comment_author."' alt='".$comment_author."' border=0 />
			<div style='margin-left:36px;'>
			  <a href='".$commentItem['author']['link'][1]['@href']."' target='_blank' class='douban_from' style = 'display:block;'>
				<span>".$comment_author."</span>
			  </a>
			  <div class='douban_comments'>
				<div class=item_rating>评分:".$comment_rating."</div>
				<div class='comment_title' style='font-weight:bold;'>".$comments_title."</div>
				<div class='comment_summary'>".$comments_summary."</div>
				<div style='text-align:right;'>
				  <a class='comment_full_url' href='".$fulltext_url."' target='_blank'>查看评论全文</a>
				</div>
				<div class='comment_date' style='text-align:right;'>".$time_array[0]."</div>
			  </div>
			  <div class='item_info'>
				<a href='".$doubanItemMeta['link'][$t]['@href']."' target='_blank'><img class='item_img' src='".$doubanItemMeta['link'][$k]['@href']."' style='float:left;' /></a>
				<div class='item_meta' style='margin-left:100px;'>
				  <div><a class='item_title' href='".$doubanItemMeta['link'][$t]['@href']."' target='_blank'>".$doubanItemMeta['title']['$t']."</a></div>
				  <div class='item_author'>".$item_owner."</div>
				  <div class='item_date'>".$item_date."</div>
				  <div class='average_rating'>豆瓣评分：".$doubanItemMeta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$doubanItemMeta['gd:rating']['@numRaters']."人参与投票</div>
				</div>
			  </div>
			</div>
		  </div>
		</li>";
}

if($startIndex+$numResults < $totalCommentsNum)
{
  $doubanContent .="<div id='".$subjectID."' class='loadmore ".$item_type."'><a>".$load_more_text."</a></div>";
}

echo $doubanContent;
?>