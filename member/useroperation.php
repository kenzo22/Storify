<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
  
$operation=$_POST['operation'];
$uid = $_POST['uid'];
switch($operation)
  {
	case "follow":
	  //echo $uid;
	  echo 'follow';
		break;
	case"unfollow":
	  //echo 'unfollow';
		break;
	default:
		break;
  }
//echo $operation;
?>