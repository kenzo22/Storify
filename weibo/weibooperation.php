<?php
include "../connect_db.php";
include "../include/functions.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$itemsPerPage = 20;

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
$weibo;
$keywords;
$load_more_flag = true;

if('my_weibo' == $operation)
{
  $weibo  = $c->user_timeline($page, $itemsPerPage, null);
  if( $weibo[0]['user']['statuses_count'] - $page*$itemsPerPage <= 0)
  $load_more_flag = false;
}
else if('my_follow' == $operation)
{
  $weibo  = $c->friends_timeline($page, $itemsPerPage);
}
else if('weibo_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $weibo  = $c->search_weibo($page, $itemsPerPage, $keywords);
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $weibo  = $c->user_timeline($page, $itemsPerPage, $keywords);
  if( $weibo[0]['user']['statuses_count'] - $page*$itemsPerPage <= 0)
  $load_more_flag = false;
}

$weiboContent = "";
foreach( $weibo as $item )
{
    //show emotions
    $item['text'] = subs_emotions($item['text'],"weibo");

    $item['text'] = subs_url($item['text']);

  $createTime = dateFormat($item['created_at']);
  //$weibo_per_id = sprintf("%.0f", $item['id']);
  $weibo_per_id = number_format($item['id'], 0, '', '');
  $weiboContent .= "<li class='weibo_drag sina' id='".$weibo_per_id."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
  src='".$item['user']['profile_image_url']."' alt='".$item['user']['screen_name']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://weibo.com/".$item['user']['id']."' target='_blank' 
  style = 'display:block;'><span class='weibo_from'>".$item['user']['screen_name']."</span></a><span class='weibo_text'>".$item['text'];
    
    if (isset($item['retweeted_status'])){
        // show emotions in text
        $item['retweeted_status']['text'] = subs_emotions($item['retweeted_status']['text'],"weibo");

        $item['retweeted_status']['text'] = subs_url($item['retweeted_status']['text']);

        $createTime = dateFormat($item['created_at']);

		$weiboContent .= "//@".$item['retweeted_status']['user']['name'].":".$item['retweeted_status']['text'];
        if(isset($item['retweeted_status']['thumbnail_pic'])){
            $weiboContent .= "</span><div class='weibo_retweet_img'><img src='".$item['retweeted_status']['thumbnail_pic']."' /></div>";
        }
		else
		{
		  $weiboContent .= "</span>";
		}
    }
	else
	{
	  $weiboContent .= "</span>";
	  if (isset($item['thumbnail_pic']))
        $weiboContent .= "<div class='weibo_img'><img src='".$item['thumbnail_pic']."' /></div>";
	}
    $weiboContent .= "</div><span class='create_time'>".$createTime."</span>
  <span style='float:right;'><a>[转发]</a></span></div></li>";
}
if($load_more_flag)
{
  $weiboContent .="<div class='loadmore'><a>更多</a></div>";
}
echo $weiboContent;

?>
