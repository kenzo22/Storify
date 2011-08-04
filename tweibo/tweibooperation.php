<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$timestamp = $_GET['timestamp'];

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
$tweibo;
$keywords;
$lastTimestamp;
if('my_weibo' == $operation)
{
  $tweibo  = $c->broadcast_timeline($page, $timestamp, 20);
}
else if('my_follow' == $operation)
{
  $tweibo  = $c->home_timeline($page, $timestamp, 20);
}
else if('weibo_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $tweibo  = $c->search_t($keywords);
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $tweibo  = $c->user_timeline($keywords, $page, $timestamp, 20);
}

$info = $tweibo[data][info];
foreach( $info as $item )
{
  $lastTimestamp = $item['timestamp'];
  $profileImgUrl = $item['head']."/50";
  $time = getdate($item['timestamp']);
  $create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
  $weiboContent .= "<li class='weibo_drag tencent' id='".$item['id']."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
  src='".$profileImgUrl."' alt='".$item['nick']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://t.qq.com/".$item['name']."' target='_blank' 
  style = 'display:block;'><span class='weibo_from'>".$item['nick']."</span></a><span class='weibo_text'>".$item['text']."</span><div><span class='create_time'>".$create_time."</span>
  <span style='float:right;'><a>[转发]</a></span></div></div></div></li>";
}
$weiboContent .="<div class='loadmore'><a>load more</a><span id='".$lastTimestamp."'></span></div>";
echo $weiboContent;

?>