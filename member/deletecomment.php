<?php
require_once "../connect_db.php";
include '../include/secureGlobals.php';

$comment_id=$_GET['comment_id'];
$query="delete from ".$db_prefix."comments where comment_id=".$comment_id;
$DB->query($query);

?>