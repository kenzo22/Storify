<?php
include "../connect_db.php";
include "../include/functions.php";
include_once "../include/weibo_functions.php";
session_start();
include_once( 'config.php' );
include_once( 'sinaweibo.php' );
include '../include/secureGlobals.php';
  
$operation=$_GET['operation'];
$page = $_GET['page'];
$itemsPerPage = 20;

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
$weibo;
$keywords;
$load_more_flag = true;
$weiboContent = "";

if('list_ht' == $operation)
{
  $ht_weekly = $c->trends_weekly();
  $ht_daily = $c->trends_daily();
  $ht_hourly = $c->trends_hourly();
  $ht_weekly = array_values($ht_weekly['trends']);
  $ht_daily = array_values($ht_daily['trends']);
  $ht_hourly = array_values($ht_hourly['trends']);
  $weiboContent.="<li class='ht_wrapper'><h3 class='clear'>一周热门话题</h3>";
  foreach( $ht_weekly[0] as $item1 )
  {
    $weiboContent.="<span><a class='list_t_weibo' href='#'>".$item1['name']."</a></span>";
  }
  $weiboContent .="</li><li class='ht_wrapper'><h3 class='clear'>24小时热门话题</h3>";
  foreach( $ht_daily[0] as $item2 )
  {
    $weiboContent.="<span><a class='list_t_weibo' href='#'>".$item2['name']."</a></span>";
  }
  $weiboContent .="</li><li class='ht_wrapper'><h3 class='clear'>1小时热门话题</h3>";
  foreach( $ht_hourly[0] as $item3 )
  {
    $weiboContent.="<span><a class='list_t_weibo' href='#'>".$item3['name']."</a></span>";
  }
  $weiboContent.="</li>";
  echo $weiboContent;
  exit;
}
else if('my_weibo' == $operation)
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
  //$weibo  = $c->search_weibo($page, $itemsPerPage, $keywords);
  $weibo  = $c->trends_timeline($page, $itemsPerPage, $keywords);
  if(count($weibo) == 0)
  {
    echo "<div class='imply_color center'>对不起，没有找到相关的微博</div>";
    exit;
  }
}
else if('user_search' == $operation)
{
  $keywords = $_GET['keywords'];
  $weibo  = $c->user_timeline($page, $itemsPerPage, $keywords);
  if(count($weibo) == 0)
  {
    echo "<div class='imply_color center'>此新浪微博用户不存在</div>";
    exit;
  }
  if( $weibo[0]['user']['statuses_count'] - $page*$itemsPerPage <= 0)
  $load_more_flag = false;
}

foreach( $weibo as $item )
{
    //show emotions
    $item['text'] = subs_emotions($item['text'],"weibo");

    $item['text'] = subs_url($item['text']);

  $createTime = dateFormat($item['created_at']);
  //$weibo_per_id = sprintf("%.0f", $item['id']);
  $weibo_per_id = number_format($item['id'], 0, '', '');
  $weiboContent .= "<li class='weibo_drag sina' id='w_".$weibo_per_id."'><div class='story_wrapper'><img class='profile_img' src='".$item['user']['profile_image_url']."' alt='".$item['user']['screen_name']."' border=0 /><div class='weibo_content'><div><a class='user_page' href='http://weibo.com/".$item['user']['id']."' target='_blank'><span class='weibo_from'>".$item['user']['screen_name']."</span></a></div>";
    
    if (isset($item['retweeted_status']))
	{
        // show emotions in text
        $item['retweeted_status']['text'] = subs_emotions($item['retweeted_status']['text'],"weibo");

        $item['retweeted_status']['text'] = subs_url($item['retweeted_status']['text']);

        $createTime = dateFormat($item['created_at']);

		$weiboContent .= "<span class='weibo_text is_repost'>".$item['text']."//@".$item['retweeted_status']['user']['name'].":".$item['retweeted_status']['text'];
        if(isset($item['retweeted_status']['thumbnail_pic']))
		{
          $weiboContent .= "</span><div class='weibo_retweet_img'><img src='".$item['retweeted_status']['thumbnail_pic']."' alt='' /></div>";
        }
		else
		{
		  $weiboContent .= "</span>";
		}
    }
	else
	{
	  $weiboContent .= "<span class='weibo_text'>".$item['text']."</span>";
	}
	if (isset($item['thumbnail_pic']))
	{
	  $weiboContent .= "<div class='weibo_img'><img src='".$item['thumbnail_pic']."' alt='' /></div>";
	}
    $weiboContent .= "</div><span class='create_time'>".$createTime."</span></div></li>";
}
if($load_more_flag)
{
  $weiboContent .="<a class='loadmore'><span>更多</span></a>";
}
echo $weiboContent;

?>
