<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/user_auth_fns.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
session_start();

if(!islogin())
{
  exit;
}
$user_id=intval($_POST['uid']); 
$post_id=intval($_POST['pid']);

$query = "SELECT post_content, COUNT(*) as num FROM ".$db_prefix."posts where ID='".$post_id."' and post_author=".$user_id;
$results = mysql_fetch_array(mysql_query($query));
$count = $results['num'];
$json = json_decode($results['post_content'],true);

$cwd=getcwd();
if(preg_match("#(^/.*?\/storify)/#",$cwd,$abs_path_matches)){
    $path_prefix=$abs_path_matches[1];
}else{
    echo "工作目录出错。";
    exit;
}

foreach($json['content'] as $key=>$value)
{
    if($value['type'] == 'upload_img'){
        $local_file=$path_prefix.$value['content'];
        unlink($local_file);
    }
}

if($count!=0)
{
  $query="select tag_id from ".$db_prefix."tag_story where story_id=".$post_id;
  $results=$DB->query($query);

  $query="delete from ".$db_prefix."tag_story where story_id=".$post_id;
  $DB->query($query);

  $query="delete from ".$db_prefix."pageview where story_id=".$post_id;
  $DB->query($query);

  while($item=$DB->fetch_array($results))
  {
    $query="select * from ".$db_prefix."tag_story where tag_id=".$item['tag_id'];
    $res=$DB->query($query);
    if($DB->num_rows($res) == 0)
    {
      $query="delete from ".$db_prefix."tag where id=".$item['tag_id'];
      $DB->query($query);
    }
  }

  $result=$DB->query("DELETE FROM ".$db_prefix."posts where ID='".$post_id."'");
  echo $_SESSION['uid'];
}
?>
