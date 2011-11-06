<?php
   include dirname(__FILE__).'/'."config/global_config.php";  
   require_once dirname(__FILE__).'/'."connect_db.php";  
   require_once "include/functions.php";
   require_once "include/user_auth_fns.php";
   include  "include/header.htm";  //读入头文件
   
   //unset($debug); //不允许调试
   session_start();
   $debug=1;
   
   $MAX_DAYS=30;
 
   if (!empty($_SERVER[HTTP_REFERER])) $url=htmlspecialchars($_SERVER[HTTP_REFERER]); 
   
   if (get_magic_quotes_gpc()) {  //magic_quotes_gpc开了会加"\" 先去掉
        $_GET = stripslashes_array($_GET);
        $_POST = stripslashes_array($_POST);
        $_COOKIE = stripslashes_array($_COOKIE); 
        $GLOBALS = stripslashes_array($GLOBALS);
   } 
   set_magic_quotes_runtime(0); //关闭magic_quotes_gpc

	if(islogin())
    { 
		$user_profile_img;
		$userresult=$DB->fetch_one_array("SELECT id, photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
		if($userresult['photo'] != '')
		{
		  $user_profile_img = $userresult['photo'];
		}
		else
		{
		  $user_profile_img = '/img/douban_user_dft.jpg';
		}
		$content="<ul class='user_console showborder'>
				    <li class='person_li display' style='display:block;'><a class='person_a person_a_display' href='/member/user.php?user_id=".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a home_icon' href='/member/user.php?user_id=".$userresult['id']."'><img class='console_img' src='/img/home.png'/><span>我的主页</span></a></li>
					<li class='person_li'><a class='person_a setting_icon' href='/member/user_setting.php'><img class='console_img' src='/img/setting.png'/><span>设置</span></a></li>
					<li class='person_li'><a class='person_a quit_icon' href='/login/login.php?logout'><img class='console_img' src='/img/quit.png'/><span>退出<span></a></li>
		          </ul>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/img/koulifangbeta.png' style='width:120px; height:39px; border:0;'></a></span>
	  <span style='position:absolute; right:130px; top:11px;'><a class='edit_story_btn' href='/member'>创建故事</a><a class='my_story_btn' href='/member/user.php?user_id=".$userresult['id']."'>我的故事</a></span>".$content."</div></div><BR>";
    }
	else
	{
	  //select a random item from the publictoken pool
	  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	  if($_SESSION['last_wkey']['oauth_token'] == '')
	  {
	    $_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
	    $_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	  }
	  $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
	  $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
  
	  $content = "<span style='margin: 0; position:absolute; right:0; top:11px;'><a class='edit_story_btn' href='/member'>创建故事</a><a class='login_top' href='/login/login_form.php?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='register_top' href='/register/register_form.php'>注册</a></span>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/img/koulifangbeta.png' style='width:120px; height:39px; border:0;'></a></span>".$content."</div></div><BR>";
	}
?>
<script>
$(function() {
$('.person_li').bind('mouseover', function(e){
e.preventDefault();
$('.person_li').css('display', 'block');
});
$('.user_console').bind('mouseout', function(){
$('.person_li').slice(1, 4).css('display', 'none');
});
});
</script>
