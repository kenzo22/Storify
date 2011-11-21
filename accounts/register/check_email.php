<?php
require_once "../connect_db.php"; 
require_once('../include/class.phpmailer.php');

$email=$_GET['email'];

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