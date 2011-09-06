<?php
include "../config/global_config.php";
include "../connect_db.php";
include "../include/functions.php";
include "userrelation.php";
 
$operation=$_POST['operation'];
$follow_uid = $_POST['uid'];

$query="select id, username, photo from ".$db_prefix."user where id=".$_SESSION['uid'];
$result=$DB->query($query);
$item=$DB->fetch_array($result);
$usr_img = $item['photo'];

$content;
switch($operation)
  {
	case "follow":
        follow($follow_uid);
		$content = "<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/storify/member/user.php?user_id=".$item['id']."'><img src='".$usr_img."' title='".$item['username']."'></a></li>";
		break;
	case"unfollow":
        unfollow($follow_uid);
		break;
	default:
		break;
  }
echo $content;
?>
