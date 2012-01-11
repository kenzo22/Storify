<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 
require_once $_SERVER['DOCUMENT_ROOT'].'/include/class.phpmailer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

$email=$_GET['email'];
if(!is_email($email)){
    echo "Email格式不正确，并且绕过前端验证了。"
}

try
{
  $result = $DB->query("select * from ".$db_prefix."user where email='".$email."'");
  if(!$result)
  {
    throw new Exception('Could not execute query.');
  }
  if ($DB->num_rows($result)>0)
  {
    echo '1';
  }
  else
  {
    echo '0';
  }
}
catch (Exception $e) 
{
  echo $e->getMessage();
  exit;
}
?>
