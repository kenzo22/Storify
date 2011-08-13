<?php
include "../connect_db.php";
include "../include/functions.php";
session_start();
  
$operation=$_POST['operation'];
switch($operation)
  {
	case "follow":
		break;
	case"unfollow":
		break;
	default:
		break;
  }
//echo $operation;
?>