<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 

$weibo_access_token =           array('3dded3c1a69e0e24609b04c3bc07d3ee', 'a5a036de79ad7bb7e71446366d9c69ab', '9a0db78eaffe82ee099f17c8937f29cf');
$weibo_access_token_secret =    array('4815f86a2f8dcbbca4a307535b1a82d8', 'ddd74ff5df9a06325822cefdec81e10e', '0175d039c755cc3b128c134f30b9af3c');

$tweibo_access_token =          array('1fce15f8b9d3449ea9a031adf9138f95', '4fc29d6f9721471fabfb38ce56298f48');
$tweibo_access_token_secret =   array('2a4a03d0dac0951f06d3e7b5b30a1ea0', '355354af7961e5bbc154238dca72a75a');


function islogin()
{
 global $_SESSION;
 global $DB;
 global $db_prefix;
 if(empty($_SESSION['uid']))
 {
   if($_COOKIE['email'] != '' && $_COOKIE['password'] != '')
   {
     $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
     if(!empty($userinfo['id']))
     {
       $_SESSION['uid']=intval($userinfo['id']);
       $_SESSION['username']=$userinfo['username'];
	   
	   $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	   if($userinfo['weibo_access_token'] == '')
	   {
		 $_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
		 $_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	   }
	   else
	   {
		 $_SESSION['last_wkey']['oauth_token']=$userinfo['weibo_access_token'];
		 $_SESSION['last_wkey']['oauth_token_secret']=$userinfo['weibo_access_token_secret'];
	   }
	   if($userinfo['tweibo_access_token'] == '')
	   {
		 $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
		 $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
	   }
	   else
	   {
		 $_SESSION['last_tkey']['oauth_token']=$userinfo['tweibo_access_token'];
		 $_SESSION['last_tkey']['oauth_token_secret']=$userinfo['tweibo_access_token_secret'];
	   }
	  
	   $_SESSION['last_dkey']['oauth_token']=$userinfo['douban_access_token'];
	   $_SESSION['last_dkey']['oauth_token_secret']=$userinfo['douban_access_token_secret'];
	   $_SESSION['yupoo_token'] = $userinfo['yupoo_token'];
	   
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
$result = $DB->fetch_one_array("SELECT * FROM story_user WHERE email='".$email."' AND passwd='".$passwd."'");
return $result;
}

function getUserPic($uid)
{
  global $DB;
  $userresult = $DB->fetch_one_array("SELECT photo FROM story_user where id='".$uid."'");
  if($userresult['photo'] == '')
  {
	$user_profile_img = '/img/douban_user_dft.jpg';
  }
  else
  {
	$user_profile_img =$userresult['photo'];
  }
  return $user_profile_img;
}

function getPublicToken()
{
    global $weibo_access_token, $tweibo_access_token, $weibo_access_token_secret, $tweibo_access_token_secret;
    global $_SESSION;
  if($_SESSION['last_wkey']['oauth_token'] == '')
  {
        $max = sizeof($weibo_access_token);
        $indx = rand(0,$max-1);
	$_SESSION['last_wkey']['oauth_token'] = $weibo_access_token[$indx];
	$_SESSION['last_wkey']['oauth_token_secret'] =  $weibo_access_token_secret[$indx];
  }

    if($_SESSION['last_tkey']['oauth_token'] == '')
    {
        $max = sizeof($tweibo_access_token);
        $indx = rand(0, $max-1);
        $_SESSION['last_tkey']['oauth_token'] =  $tweibo_access_token[$indx];
        $_SESSION['last_tkey']['oauth_token_secret'] = $tweibo_access_token_secret[$indx];
    }
}

?>
