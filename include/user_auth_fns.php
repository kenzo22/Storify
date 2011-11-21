<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 

function islogin()
{
 global $_SESSION;
 if(empty($_SESSION['uid']))
 {
   if($_COOKIE['email'] != '' && $_COOKIE['password'] != '')
   {
     $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
     if(!empty($userinfo['id']))
     {
       $_SESSION['uid']=intval($userinfo['id']);
       $_SESSION['username']=$userinfo['username'];
	   return 1;
     }
   }
   return 0;
 }
 else
 {
   return 1;
 }
}

function getUserInfo($email, $password)
{
global $DB;
global $db_prefix;
$email=(trim($email));
$passwd=trim($password);
$result = $DB->fetch_one_array("SELECT id,username FROM ".$db_prefix."user WHERE email='".$email."' AND passwd='".$passwd."'");
return $result;
}

?>
