<?php
include "../connect_db.php";
include "../include/functions.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );
include '../include/secureGlobals.php';
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$timestamp = $_GET['timestamp'];
$itemsPerPage = 20;
$load_more_flag = true;
$fav_flag = false;

$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
$tweibo;
$keywords;
$lastTimestamp;
if('my_weibo' == $operation)
{
  $tweibo  = $c->broadcast_timeline($page, $timestamp, $itemsPerPage);
}
else if('my_follow' == $operation)
{
  $tweibo  = $c->home_timeline($page, $timestamp, $itemsPerPage);
}
else if('my_fav' == $operation)
{
  $fav_flag = true;
  $tweibo  = $c->fav_list_t($page, $timestamp, $itemsPerPage);
}
else if('weibo_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $is_original = $_GET['original'];
  $is_retweet = $_GET['retweet'];
  $havepic = $_GET['havepic'];
  $tweibo  = $c->search_t($keywords, $page, $itemsPerPage, 'json');
  if($tweibo['data'] == NULL)
  {
    echo "<div class='imply_color center'>对不起，没有找到相关的微博</div>";
    exit;
  }
}
else if('list_user' == $operation)
{
  $keywords = $_GET['keywords'];
  $tweibo  = $c->search_user($keywords, $page, $itemsPerPage);
  if($tweibo == NULL)
  {
    echo "<div class='imply_color center'>没有更多用户了</div>";
    exit;
  }
  if($tweibo['data'] == NULL  && $tweibo['msg'] == 'have no user')
  {
    echo "<div class='imply_color center'>此腾讯微博用户不存在</div>";
    exit;
  }
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $tweibo  = $c->user_timeline($keywords, $page, $timestamp, $itemsPerPage);
  if($tweibo['data'] == NULL)
  {
    echo "<div class='imply_color center'>对不起，没有找到相关的微博</div>";
    exit;
  }
}

$info = $tweibo['data']['info'];
$total_num = $tweibo['data']['totalnum'];
if('list_user' == $operation)
{
  $weiboContent = "";
  foreach( $info as $item )
  {
     //$weiboContent .= "<li class='weibo_drag'>".$item['name']."<li>";
	 if($item['head'] == '')
	 {
	   $profileImgUrl = '/img/douban_user_dft.jpg';
	 }
	 else
	 { 
	   $profileImgUrl = $item['head']."/100";
	 }	 
	 $weiboContent .="<li id='".$item['name']."' class='weibo_drag tuser'>
					    <div class='user_wrapper'>
						  <a href='#' target='_blank'><img src='".$profileImgUrl."' alt='' /></a>
						  <div class='person_meta'>
						    <div>".$item['nick']."  (@".$item['name'].")</div>
							<div>".$item['location']."</div>
							<div>听众:".$item['fansnum']."</div>
							<div>收听:".$item['idolnum']."</div>
							<div class='view_weibo'><a class='list_tweibo' href='#'>查看微博</a></div>
						  </div>
						</div>
					  <li>";
  }
  if($itemsPerPage*$page<$total_num)
  {
    $weiboContent .="<a class='loadmore tuser'><span>更多</span></a>";
  }
}
else
{
  $weiboContent = "";
  if('weibo_search' == $operation)
  {
    if($itemsPerPage*$page >= $total_num)
	  $load_more_flag = false;
  }
  else
  {
    if( $tweibo['data']['hasnext'] == 1)
	{
	  $load_more_flag = false;
	}
	//address the tencent my weibo bug
	if(('my_weibo' == $operation && $itemsPerPage >= $total_num) || 'my_fav' == $operation)
	{
	  $load_more_flag = false;
	}			
  }
  foreach( $info as $item )
  {
    $lastTimestamp = $item['timestamp'];
    $profileImgUrl = $item['head']."/50";
    $time = getdate($item['timestamp']);
    $create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];

    //show nick name
    $item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);
 
    // show face gif 
    $item['text'] = subs_emotions($item['text'],"tweibo");

    $weiboContent .= "<li class='weibo_drag tencent' id='t_".$item['id']."'><div class='story_wrapper'><img class='profile_img' src='".$profileImgUrl."' alt='".$item['nick']."' border=0 /><div class='weibo_content'><div><a class='user_page' href='http://t.qq.com/".$item['name']."' target='_blank'><span class='weibo_from'>".$item['nick']."</span></a></div>";
    
    if(isset($item['source']))
	{
        // nick name
        $item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
    
        // emotion substution
        $item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");
    
        if($item['source']['text'] == null)
            $item['source']['text'] = "此微博已被原作者删除。";
        $weiboContent .="<span class='weibo_text is_repost'>".$item['text']."||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span>";

        if(isset($item['source']['image'])){
            foreach($item['source']['image'] as $re_img_url){
                $weiboContent .="<div class='weibo_retweet_img'><img src='".$re_img_url."/120' alt='' /></div>";
            }
        }
    }
	else
	{
        $weiboContent .= "<span class='weibo_text'>".$item['text']."</span>";
    }
	if(isset($item['image']))
	{
		foreach($item['image'] as $img_url){
			$weiboContent .="<div class='weibo_img'><img src='".$img_url."/120' alt='' /></div>";
		}
    }
    if($fav_flag)
	{
	  $weiboContent .= "</div><div><span class='float_r'><a class='del_fav remove_flag' href='#'>[取消收藏]</a></span>";
	}
	else
	{
	  $weiboContent .= "</div><div><span class='float_r'><a class='add_fav' href='#'>[收藏]</a></span>";
	}
    $weiboContent .= "<span class='create_time'>".$create_time."</span></div></div></li>";
  }
  if($load_more_flag)
  {
    $weiboContent .="<a class='loadmore'>更多<span id='".$lastTimestamp."'></span></a>";
  }
}
echo $weiboContent;
?>
