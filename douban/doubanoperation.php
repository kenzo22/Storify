<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );
  
/*$operation=$_GET['operation'];
$page = $_GET['page'];

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$weibo;
$keywords;
if('my_weibo' == $operation)
{
  $weibo  = $c->user_timeline($page, 20, null);
}
else if('my_follow' == $operation)
{
  $weibo  = $c->friends_timeline($page, 20);
}
else if('weibo_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $weibo  = $c->search_weibo($page, 20, $keywords);
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $weibo  = $c->user_timeline($page, 20, $keywords);
}

foreach( $weibo as $item )
{
  $createTime = dateFormat($item['created_at']);
  $weibo_per_id = number_format($item['id'], 0, '', '');
  $weiboContent .= "<li class='weibo_drag sina' id='".$weibo_per_id."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
  src='".$item['user']['profile_image_url']."' alt='".$item['user']['screen_name']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://weibo.com/".$item['user']['id']."' target='_blank' 
  style = 'display:block;'><span class='weibo_from'>".$item['user']['screen_name']."</span></a><span class='weibo_text'>".$item['text']."</span><div><span class='create_time'>".$createTime."</span>
  <span style='float:right;'><a>[转发]</a></span></div></div></div></li>";
}
$weiboContent .="<div class='loadmore'><a>更多</a></div>";
echo $weiboContent;*/

?>
