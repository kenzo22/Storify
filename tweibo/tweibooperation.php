<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$timestamp = $_GET['timestamp'];

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
  $tweibo  = $c->broadcast_timeline($page, $timestamp, 20);
}
else if('my_follow' == $operation)
{
  $tweibo  = $c->home_timeline($page, $timestamp, 20);
}
else if('list_weibo' == $operation)
{
  $tweibo  = $c->t_list($tweibo_ids);
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
if(isset($_GET['weibo_ids']))
{
  $weiboContent = "<div id='data_wrapper'>";
  foreach( $info as $item )
  {
    $time = getdate($item['timestamp']);
    $create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
    $profileImgUrl = $item['head']."/50";
    
    $weiboContent .="<li class='weibo_drop tencent' id='".$item['id']."' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text_drop'>".$item['text']."</span></div>
    <div id='story_signature'><span style='float:right;'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
    .$profileImgUrl."' alt='".$item['nick']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'>
    <span ><a class='weibo_from_drop' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span>
    <img border='0' style='position:relative; top:2px' src='/Storify/img/tencent16.png'/><a>".$create_time."</a></span></div></span> </div></div></li>";
  }
  $weiboContent .= "</div>";
}
else
{
  foreach( $info as $item )
  {
    $lastTimestamp = $item['timestamp'];
    $profileImgUrl = $item['head']."/50";
    $time = getdate($item['timestamp']);
    $create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];

    //show nick name
    preg_match_all("/@([^@:\s]+)/",$item['text'],$name_matches,PREG_SET_ORDER);
    if($name_matches){
        foreach ($name_matches as $ele){
            if(array_key_exists($ele[1],$tweibo['data']['user'])){
                $item['text'] = str_replace($ele[0],$tweibo['data']['user'][$ele[1]]."(".$ele[0].")",$item['text']);
            }
        }
    }
   
    // show face gif 
    preg_match_all("/\/([^\s]+)/",$item['text'],$face_matches,PREG_SET_ORDER);
    if($face_matches){
        foreach($face_matches as $element){
            // Chinese in utf-8 may contain 2,3,4 bytes. so try it.
            for($i=1; $i<=strlen($element[1]); $i++){
                $story_file =  $story_img_path."tweibo/".substr($element[1],0,$i).".gif";
                $local_file = $abs_path_matches[1].$story_file;
                if(is_readable($local_file)){
                    $img_replace = "<img src='".$story_file."'>";
                    $item['text'] = str_replace(substr($element[0],0,$i+1),$img_replace,$item['text']);
                    break;
                }
            }
        }
    }

    $weiboContent .= "<li class='weibo_drag tencent' id='".$item['id']."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
    src='".$profileImgUrl."' alt='".$item['nick']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://t.qq.com/".$item['name']."' target='_blank' 
    style = 'display:block;'><span class='weibo_from'>".$item['nick']."</span></a><span class='weibo_text'>".$item['text'];
    
    if(isset($item['source'])){
        $weiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span>";
        if(isset($item['source']['image'])){
            foreach($item['source']['image'] as $re_img_url){
                $weiboContent .="<div class='tweibo_retweet_img'><img src='".$re_img_url."/240' /></div>";
            }
        }
    }else{
        $weiboContent .= "</span>";
        if(isset($item['image'])){
            foreach($item['image'] as $img_url){
                $weiboContent .="<div class='weibo_img'><img src='".$img_url."/240' /></div>";
            }
        }
    }
    $weiboContent .= "<div><span class='create_time'>".$create_time."</span>
    <span style='float:right;'><a>[转发]</a></span></div></div></div></li>";

  }
  $weiboContent .="<div class='loadmore'><a>更多</a><span id='".$lastTimestamp."'></span></div>";
}
echo $weiboContent;
?>
