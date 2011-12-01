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
  $imply_txt = "这本书还没有书评";
}
else if('movieReviews' == $operation)
{
  $item_type = 'movieReviews';
  $doubanCommentsReturn  = $c->search_movie_reviews($subjectID, $startIndex, $numResults);
  $doubanItemMeta = $c->get_movie($subjectID);
  $imply_txt = "这部电影还没有影评";
}
else if('musicReviews' == $operation)
{
  $item_type = 'musicReviews';
  $doubanCommentsReturn  = $c->search_music_reviews($subjectID, $startIndex, $numResults);
  $doubanItemMeta = $c->get_music($subjectID);
  $imply_txt = "这首歌还没有乐评";
}

$totalCommentsNum = $doubanCommentsReturn['opensearch:totalResults']['$t'];
if($totalCommentsNum == 0)
{
  echo "<div class='imply_color center'>".$imply_txt."</div>";
  exit;
}

$pubDate = getPubDate($doubanItemMeta['db:attribute']);
$itemPic = getItemPic($doubanItemMeta['link']);
$itemLink = getItemLink($doubanItemMeta['link']);
$author = getAuthors($doubanItemMeta['author']);
$item_title = $doubanItemMeta['title']['$t'];

if('bookReviews' == $operation)
{
  $item_owner = "作者：".$author;
  $item_date = "出版年：".$pubDate;
  $load_more_text = "更多书评";
}
else if('movieReviews' == $operation)
{
  $item_owner = "导演：".$author;
  $item_date = "上映日期：".$pubDate;
  $load_more_text = "更多影评";
}
else if('musicReviews' == $operation)
{
  $item_owner = "表演者：".$author;
  $item_date = "发行时间：".$pubDate;
  $load_more_text = "更多乐评";
}

foreach( $doubanCommentsReturn['entry'] as $commentItem )
{
  $comment_temp_array = explode("/", $commentItem['id']['$t']);
  $comment_per_id = $comment_temp_array[count($comment_temp_array)-1];
  $fulltext_url = $commentItem['link'][1]['@href'];
  $comments_title = $commentItem['title']['$t'];
  $comments_summary = $commentItem['summary']['$t'];
  $comment_author = $commentItem['author']['name']['$t'];
  $comment_author_link = getAuthorLink($commentItem['author']['link']);
  $comment_author_pic = getAuthorPic($commentItem['author']['link']);
  $comment_rating = 2*$commentItem['gd:rating']['@value'];
  $time_array = explode("T", $commentItem['published']['$t']);

  $doubanContent.=
		"<li class='douban_drag douban ".$item_type."' id='d_".$comment_per_id."'>
		  <div class='douban_wrapper'>
			<img class='profile_img' src='".$comment_author_pic."' title='".$comment_author."' alt='".$comment_author."' border=0 />
			<div class='douban_content'>
			  <a href='".$comment_author_link."' target='_blank' class='douban_from'>
				<span>".$comment_author."</span>
			  </a>
			  <div class='douban_comments'>
				<div class='comment_title'>".$comments_title."</div>
				<div class='comment_summary'>".$comments_summary."<a class='comment_full_url' href='".$fulltext_url."' target='_blank'>[查看评论全文]</a></div>
			  </div>
			  <div class='item_info'>
				<a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' alt='".$item_title."' /></a>
				<div class='item_meta'>
				  <div><a class='item_title' href='".$itemLink."' target='_blank'>".$item_title."</a></div>
				  <div class='item_author'>".$item_owner."</div>
				  <div class='item_date'>".$item_date."</div>
				  <span class=item_rating>".$comment_author."评分:".$comment_rating."</span>&nbsp&nbsp&nbsp&nbsp<span class='comment_date'>".$time_array[0]."</span>
				  <div class='average_rating'>豆瓣评分：".$doubanItemMeta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$doubanItemMeta['gd:rating']['@numRaters']."人参与投票</div>
				</div>
			  </div>
			</div>
		  </div>
		</li>";
}

if($startIndex+$numResults < $totalCommentsNum)
{
  $doubanContent .="<a id='".$subjectID."' class='loadmore ".$item_type."'><span>".$load_more_text."</span></a>";
}

echo $doubanContent;
?>