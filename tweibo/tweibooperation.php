<?php
include "../connect_db.php";
include "../include/functions.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$timestamp = $_GET['timestamp'];
$itemsPerPage = 20;
$load_more_flag = true;

preg_match("/(.*)\/storify/",getcwd(),$abs_path_matches);
$story_img_path="/storify/img/";

if(isset($_GET['weibo_ids']))
{
  $tweibo_ids = $_GET['weibo_ids'];
}

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
else if('list_weibo' == $operation)
{
  $tweibo  = $c->t_list($tweibo_ids);
}

else if('weibo_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $tweibo  = $c->search_t($keywords, $page, $itemsPerPage);
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $userResult = $c->search_user($keywords);
  $tweibo  = $c->user_timeline($userResult['data']['info'][0]['name'], $page, $timestamp, $itemsPerPage);
}

$info = $tweibo['data']['info'];
if(isset($_GET['weibo_ids']))
{
  $weiboContent = "<div id='data_wrapper'>";
  foreach( $info as $item )
  {
    $time = getdate($item['timestamp']);
    $create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
    $profileImgUrl = $item['head']."/50";
    
    //show nick name
    $item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);

    // show face gif 
    $item['text'] = subs_emotions($item['text'],"tweibo");

    $weiboContent .="<li class='weibo_drop tencent' id='".$item['id']."' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text_drop'>".$item['text'];

    if(isset($item['source'])){
        //nick name
        $item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
        
        // emotion substution
        $item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");
        if($item['source']['text'] == null)
            $item['source']['text'] = "此微博已被原作者删除。";
        $weiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span></div>";
        if(isset($item['source']['image'])){
            foreach($item['source']['image'] as $re_img_url){
                $weiboContent .="<div class='weibo_retweet_img'><img src='".$re_img_url."/240' /></div>";
            }
        }
    }else{
        $weiboContent .= "</span></div>";
        if(isset($item['image'])){
            foreach($item['image'] as $img_url){
                $weiboContent .="<div class='weibo_img'><img src='".$img_url."/240' /></div>";
            }
        }
    }
    $weiboContent .= "<div id='story_signature'><span style='float:right;'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
    .$profileImgUrl."' alt='".$item['nick']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'>
    <span ><a class='weibo_from' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span>
    <img border='0' style='position:relative; top:2px' src='/img/tencent16.png'/><a>".$create_time."</a></span></div></span> </div></div></li>";
  }
  $weiboContent .= "</div>";
}
else
{
  if( $tweibo['data']['hasnext'] == 1)
  $load_more_flag = false;
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

    $weiboContent .= "<li class='weibo_drag tencent' id='".$item['id']."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
    src='".$profileImgUrl."' alt='".$item['nick']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://t.qq.com/".$item['name']."' target='_blank' 
    style = 'display:block;'><span class='weibo_from'>".$item['nick']."</span></a><span class='weibo_text'>".$item['text'];
    
    if(isset($item['source'])){
        
        // nick name
        $item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
    
        // emotion substution
        $item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");
        if($item['source']['text'] == null)
            $item['source']['text'] = "此微博已被原作者删除。";
        $weiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span>";

        if(isset($item['source']['image'])){
            foreach($item['source']['image'] as $re_img_url){
                $weiboContent .="<div class='weibo_retweet_img'><img src='".$re_img_url."/120' /></div>";
            }
        }
    }else{
        $weiboContent .= "</span>";
        if(isset($item['image'])){
            foreach($item['image'] as $img_url){
                $weiboContent .="<div class='weibo_img'><img src='".$img_url."/120' /></div>";
            }
        }
    }
    $weiboContent .= "</div><span class='create_time'>".$create_time."</span>
    <span style='float:right;'><a>[转发]</a></span></div></li>";

  }
  if($load_more_flag)
  {
    $weiboContent .="<div class='loadmore'><a>更多</a><span id='".$lastTimestamp."'></span></div>";
  }
}
echo $weiboContent;
?>
