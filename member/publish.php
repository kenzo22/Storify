<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/functions.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/user_auth_fns.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureCommon.php';
include $_SERVER['DOCUMENT_ROOT'].'/class/class.lib_filter.php';
session_start();

if(!islogin())
{
  exit(1);
}

$action=secureQ($_POST['action']);
$story_id=secureQ($_POST['story_id']);
$story_title=secureQ($_POST['story_title']);
$story_summary=secureQ($_POST['story_summary']);
$story_pic=secureQ($_POST['story_pic']);

if($story_pic == '/img/story_dft.jpg')
{
  $story_pic = '';
}
$story_content_filter=$filter->go($_POST['story_content']);
$story_content = secureForDB($story_content_filter);

$pulish_time=date("Y-m-d H:i:s");
$post_id = $story_id;

if($action == 'Publish')
    $post_status = 'Published';
else if($action == 'Preview' || $action == 'Draft')
    $post_status = 'Draft';
else{
    exit(2);
}

mb_regex_encoding("utf-8");

if(0 == $story_id)
{
  $embed_name_l = 12;
  $embed_name=produce_random_strdig($embed_name_l);
  $DB->query("insert into ".$db_prefix."posts values
					 (null, '".$_SESSION['uid']."', '".$pulish_time."', '".$pulish_time."', '".$embed_name."', '".$story_title."', '".$story_summary."', '".$story_pic."','".$story_content."', '".$post_status."', '".$pulish_time."', '".$pulish_time."', 0, 0)");
  $result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."' AND post_date='".$pulish_time."'" );
  $post_id = intval($result['ID']);
}
else
{
  $result=$DB->query("update ".$db_prefix."posts set post_title='".$story_title."', post_summary='".$story_summary."', post_pic_url='".$story_pic."',post_content='".$story_content."', post_status='".$post_status."'  WHERE ID='".$post_id."'");   
}
if($action == 'Publish' || $action == 'Preview')
{
  $redirect_url = "/user/".$_SESSION['uid']."/".$post_id;
}   
else if($action == 'Draft')
{
  $redirect_url = "/user/".$_SESSION['uid'];
}
echo $redirect_url;
?>
