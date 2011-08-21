<?php
require_once "../connect_db.php";
session_start();

$weibo_user_id=$_POST['weibo_user_id'];
$weibo_scree_name=$_POST['weibo_scree_name'];

$result = $DB->query("select * from ".$db_prefix."user where weibo_user_id='".$weibo_user_id."'");
if(!$result)
{
  throw new Exception('Could not execute query.');
}
if ($DB->num_rows($result) == 0)
{
  $register_time=date("Y-m-d H:i:s");
  $DB->query("insert into ".$db_prefix."user values
                         (null, '".$weibo_scree_name."', '', '', '', '', '".$weibo_user_id."', '', '', 0, '', '', '', '".$register_time."', 1)");
}

$result=$DB->fetch_one_array("SELECT id,username FROM ".$db_prefix."user where weibo_user_id='".$weibo_user_id."'");
if(!$result)
{
  throw new Exception('Could not execute query.');
}
if(!empty($result))
{
  $_SESSION['uid']=intval($result['id']);
  $_SESSION['username']=$result['username'];
}
/*else
{
  $register_time=date("Y-m-d H:i:s");
  $DB->query("insert into ".$db_prefix."user values
                         (null, '".$weibo_scree_name."', '', '', '".$weibo_user_id."', '".$register_time."', 1)");
}*/

//echo 'hello'.$weibo_user_id;
?>