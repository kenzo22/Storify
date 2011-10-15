<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
include_once( 'config.php' );
include_once( 'yupoo.php' );
  
$operation=$_GET['operation'];
$keywords = $_GET['keywords'];
$page = $_GET['page'];
$itemsPerPage = 20;

$y = new YupooAPI( YB_AKEY , YB_SKEY);
$picData;
$picContent;

if('pic_search' == $operation)
{
  $picData  = $y->search_photo($keywords, $page);
  $totalPic = $picData['result']['total'];
  if($totalPic == 0)
  {
    echo "<div class='imply_color' style='text-align:center;'>对不起，没有找到相关的图片</div>";
    exit;
  }
}
else if('user_search' == $operation)
{
  $userdata = $y->get_userid_by_name($keywords);
  if($userdata['stat'] == 'fail')
  {
    echo "<div class='imply_color' style='text-align:center;'>没有这个用户，请注意是又拍用户名，不是昵称</div>";
	exit;
  }
  else
  {
    $userid = $userdata['user']['id'];
    $picData  = $y->search_user($userid, $page, $_SESSION['yupoo_token']);
    $totalPic = $picData['result']['total'];
	if($totalPic == 0)
	{
	  echo "<div class='imply_color' style='text-align:center;'>该用户还没有上传过图片</div>";
	  exit;
	}
  }
}
else if('col_search' == $operation)
{
  $userdata = $y->get_userid_by_name($keywords);
  if($userdata['stat'] == 'fail')
  {
    echo "<div class='imply_color' style='text-align:center;'>没有这个用户，请注意是又拍用户名，不是昵称</div>";
	exit;
  }
  else
  {
    $userid = $userdata['user']['id'];
    $picData  = $y->get_user_collection($userid, $page);
    $totalPic = $picData['result']['total'];
	if($totalPic == 0)
	{
	  echo "<div class='imply_color' style='text-align:center;'>该用户没有收藏过图片</div>";
	  exit;
	}
  }
}
else if('rec_search' == $operation)
{
  if($keywords == '可指定日期如2010-6,默认搜索全部')
  {
    $picData  = $y->get_yupoo_recommend($page);
  }
  else
  {
    $picData  = $y->get_yupoo_recommend_date($page, $keywords);
  }
  if($picData['stat'] == 'fail')
  {
    echo "<div class='imply_color' style='text-align:center;'>指定日期格式有误，请参照如下格式2010-6或2010-6-6</div>";
	exit;
  }
  $totalPic = $picData['result']['total'];
}
$picArray = $picData['result']['photos'];
foreach($picArray as $item)
{
  $photoInfo = $y->get_photo_info($item['id']);
  $photoMeta = $photoInfo['photo'];
  $photoDescription = $photoMeta['description'];
  $temp_array = explode("-", $photoMeta['id']);
  $picurl = "http://pic.yupoo.com/".$item['bucket']."/".$item['key']."/square";
  $picContent .= "<li class='pic_Drag'><div style='margin-bottom:10px; clear:both;'><img src='".$picurl."' style='float:left; margin-right:5px; border: 1px solid #E9E9E9; padding:3px;'/><div style='line-height:1.5;'><a class='pic_title' target='_blank' href='http://www.yupoo.com/photos/".$item['ownername']."/".$temp_array[1]."'>".$item['title']."</a></div>
  <div style='line-height:1.5;'><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/".$item['ownername']."'>".$photoMeta['owner']['nickname']."</a></div><div class='pic_description' style='line-height:1.5;'>".$photoDescription."</div></div></li>";
}

//$picContent = "<div style='padding-left:30px;'>".count($picData[result][photos])."</div><div style='padding-left:30px;'>".$picData[result][photos][0][bucket]."</div>";

if($itemsPerPage*$page<$totalPic)
{
  $picContent .="<div class='loadmore'><a>更多图片</a></div>";
}
echo $picContent;

?>
