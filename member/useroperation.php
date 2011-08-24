<?php
include "../connect_db.php";
include "../include/functions.php";
include "userrelation.php";
 
$operation=$_POST['operation'];
$follow_uid = $_POST['uid'];

switch($operation)
  {
	case "follow":
                follow($follow_uid);
		break;
	case"unfollow":
                unfollow($follow_uid);
		break;
	default:
		break;
  }
//echo $operation;
?>
