<?php
require_once "../connect_db.php";
require_once "../include/functions.php";
include_once "../include/weibo_functions.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );

header("content-type: text/javascript");

if(!isset($_GET['id']) || !isset($_GET['name']) || !isset($_GET['callback']))
{
  exit();	
}
else
{
  $date_t = date("Y-m-d H:i:s");
  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");

  $w_token = $token['weibo_access_token'];
  $w_token_secret  = $token['weibo_access_token_secret'];
  $t_token = $token['tweibo_access_token'];
  $t_token_secret = $token['tweibo_access_token_secret'];

  $c = new WeiboClient(WB_AKEY , WB_SKEY , $w_token , $w_token_secret);
  $t = new TWeiboClient(MB_AKEY , MB_SKEY , $t_token , $t_token_secret);
  $d = new DoubanClient(DB_AKEY , DB_SKEY, '', '');
  
  $user_id = $_GET['id'];
  $embed_name = $_GET['name'];
  $result = $DB->fetch_one_array("select * from ".$db_prefix."posts where post_author='".$user_id."' and embed_name='".$embed_name."' and post_status='Published'");
  if(!empty($result))
  {
    $post_id = $result['ID'];
    //update view count for external websites
    $refer_url = $_SERVER['HTTP_REFERER'];
    $temp_array = explode("/", $refer_url);
    $domain_name = $temp_array[3];
    $selResult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."pageview WHERE story_id='".$post_id."' AND domain_name='".$domain_name."'" );
    if(!empty($selResult))
    {
      $viewresult=$DB->query("update ".$db_prefix."pageview set view_count=view_count+1  WHERE story_id='".$post_id."' AND domain_name='".$domain_name."'" );
    }
    else
    {
      $viewresult=$DB->query("insert into ".$db_prefix."pageview values(null, '".$post_id."', '".$domain_name."', '".$refer_url."', 1)");
    }
	
	$userresult = $DB->fetch_one_array("select username, intro, photo from ".$db_prefix."user where id='".$result['post_author']."'");
    $story_embed = $result['embed_name'];
    $story_time = dateFormatTrans($result['post_date'],$date_t);
    $story_title=$result['post_title'];
    $story_summary=$result['post_summary'];
    $story_pic=$result['post_pic_url'];
    $story_content=$result['post_content'];
  }
  
  $obj->id = $user_id;
  $obj->title = $story_title;
  $obj->summary = $story_summary;
  $obj->pic = $story_pic;
  $obj->time = $story_time;
  $obj->embed = $story_embed;
 
  $obj->message = "Hello " . $obj->summary;

  echo $_GET['callback']. '(' . json_encode($obj) . ');';
}
?>