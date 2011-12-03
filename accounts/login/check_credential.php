<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 
$email=$_POST['email'];
$pwd=sha1(trim($_POST["pwd"]));
try
{
$result = $DB->query("select * from ".$db_prefix."user where email='".$email."' AND passwd='".$pwd."'");
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
catch (Exception $e) {
     echo $e->getMessage();
     exit;
  }
?>