<?php
require_once "../connect_db.php";
include '../include/secureGlobals.php';
session_start();

$post_id=$_GET['post_id'];
$query="select tag_id from ".$db_prefix."tag_story where story_id=".$post_id;
$results=$DB->query($query);

$query="delete from ".$db_prefix."tag_story where story_id=".$post_id;
$DB->query($query);

// delete tag if no story is bined
while($item=$DB->fetch_array($results)){
	$query="select * from ".$db_prefix."tag_story where tag_id=".$item['tag_id'];
	$res=$DB->query($query);
	if($DB->num_rows($res) == 0){
		$query="delete from ".$db_prefix."tag where id=".$item['tag_id'];
		$DB->query($query);
	}
}

$result=$DB->query("DELETE FROM ".$db_prefix."posts where ID='".$post_id."'");
echo $_SESSION['uid'];
?>
