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
                         (null, '".$weibo_scree_name."', '', '', '', '', '".$weibo_user_id."', '', '', 0, '', '', 0, '', '', '', '".$register_time."', 1)");
}

$userresult=$DB->fetch_one_array("SELECT id,username, photo FROM ".$db_prefix."user where weibo_user_id='".$weibo_user_id."'");
if(!$userresult)
{
  throw new Exception('Could not execute query.');
}
if(!empty($result))
{
  $_SESSION['uid']=intval($userresult['id']);
  $_SESSION['username']=$userresult['username'];
  
  $user_profile_img;
  $user_profile_img = $userresult['photo'];
  $content="<ul class='user_console showborder'>
			  <li class='person_li' style='display:block;'><a class='person_a person_a_display' href='/member/user.php?user_id=".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
			  <li class='person_li'><a class='person_a' href='/member/user.php?user_id=".$userresult['id']."'>我的主页</a></li>
			  <li class='person_li'><a class='person_a' href='/member/user_setting.php'>设置</a></li>
			  <li class='person_li'><a class='person_a' href='/login/login.php?logout'>退出</a></li>
		    </ul>";
  echo "<div class='top_nav'>
		  <span id='logo'>
		    <a title='StoryBingLogo' accesskey='h' href='/'>
			  <img src='img/logo.png' border='0'>
			</a>
		  </span>
		  <span id='user_action'>
		    <a href='/index.php'>首页</a> | <a href='/member/user.php?user_id=".$userresult['id']."'>我的故事</a> | <a href='/member'>创建故事</a>
		  </span>".$content."
		</div>";
}
?>