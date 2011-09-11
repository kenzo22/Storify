<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
  
$operation=$_GET['operation'];
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

$cwd=getcwd();
preg_match("/(.*?)\/storify/",$cwd,$mat);
foreach( $weibo as $item )
{
    // show emotions in text
    preg_match_all("/\[(.*?)\]/",$item['text'],$matches,PREG_SET_ORDER);
    if($matches){
        foreach($matches as $lu){
            $local_file="/storify/img/weibo/".$lu[1].".gif";
            if(is_readable($mat[1].$local_file)){
                $replace="<img src='".$local_file."'>";
                $item['text']=str_replace($lu[0],$replace,$item['text']);
            }
        }
    }

  $createTime = dateFormat($item['created_at']);
  //$weibo_per_id = sprintf("%.0f", $item['id']);
  $weibo_per_id = number_format($item['id'], 0, '', '');
  $weiboContent .= "<li class='weibo_drag sina' id='".$weibo_per_id."'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' 
  src='".$item['user']['profile_image_url']."' alt='".$item['user']['screen_name']."' border=0 /><div class='weibo_content'><a class='user_page' href='http://weibo.com/".$item['user']['id']."' target='_blank' 
  style = 'display:block;'><span class='weibo_from'>".$item['user']['screen_name']."</span></a><span class='weibo_text'>".$item['text'];
    
    if (isset($item['retweeted_status'])){
        // show emotions in text
        preg_match_all("/\[(.*?)\]/",$item['retweeted_status']['text'],$matches,PREG_SET_ORDER);
        if($matches){
            foreach($matches as $lu){
                $local_file="/storify/img/weibo/".$lu[1].".gif";
                if(is_readable($mat[1].$local_file)){
                    $replace="<img src='".$local_file."'>";
                    $item['retweeted_status']['text']=str_replace($lu[0],$replace,$item['retweeted_status']['text']);
                }
            }
        }

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
    if (isset($item['thumbnail_pic']))
        $weiboContent .= "<div class='weibo_img'><img src='".$item['thumbnail_pic']."' /></div>";

    $weiboContent .= "</div><span class='create_time'>".$createTime."</span>
  <span style='float:right;'><a>[转发]</a></span></div></li>";
}
$weiboContent .="<div class='loadmore'><a>更多</a></div>";
echo $weiboContent;

?>
