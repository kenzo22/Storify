<?php
include_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/functions.php";
include $_SERVER['DOCUMENT_ROOT']."/member/userrelation.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
 
$operation=$_POST['operation'];
$follow_uid = intval($_POST['uid']);

$query="select id, username, photo from ".$db_prefix."user where id=".$_SESSION['uid'];
$result=$DB->query($query);
$item=$DB->fetch_array($result);
$usr_img = $item['photo'];

$content;
switch($operation)
  {
	case "follow":
        follow($follow_uid);
		$content = "<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/use/".$item['id']."'><img src='".$usr_img."' title='".$item['username']."'></a></li>";
		break;
	case"unfollow":
        unfollow($follow_uid);
		break;
	default:
		break;
  }
echo $content;
?>
