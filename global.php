<?php
   include dirname(__FILE__).'/'."config/global_config.php";    //读入配置文件
   //require_once dirname(__FILE__).'/'."user_auth_fns.php";
   require_once dirname(__FILE__).'/'."connect_db.php";  
   //require dirname(__FILE__).'/'."connect_db.php";  
   //连接数据库

   require_once "include/functions.php";
   //require_once dirname(__FILE__).'/'."user_auth_fns.php";
   require_once "include/user_auth_fns.php";
   //require  "class/session.php";

   include  "include/header.htm";  //读入头文件
   
   //unset($debug); //不允许调试
   session_start();
   $debug=1;
    
   if (!empty($_SERVER[HTTP_REFERER])) $url=htmlspecialchars($_SERVER[HTTP_REFERER]); 
   
   if (get_magic_quotes_gpc()) {  //magic_quotes_gpc开了会加"\" 先去掉
        $_GET = stripslashes_array($_GET);
        $_POST = stripslashes_array($_POST);
        $_COOKIE = stripslashes_array($_COOKIE); 
        $GLOBALS = stripslashes_array($GLOBALS);
   } 
   set_magic_quotes_runtime(0); //关闭magic_quotes_gpc

  
    //if(!empty($_SESSION['username']))
	//if(isloggedin())
	if(islogin())
    { 
		/*$content="<span class='user_console'>欢迎，<a href='".$rooturl."/login/forget_passwd.php'><b>".$_SESSION['username']."</b> </a>
						<a href='".$rooturl."/login/forget_passwd.php'></a> 
						<a href='".$rooturl."/login/login.php?logout'>&nbsp;&nbsp;[退出]</a></span>";*/
		$content="<ul class='user_console showborder'>
				    <li class='person_li' style='display:block;'><a class='person_a person_a_display' href='/storify/member/user.php'><img id='person_img' src='/storify/img/person.png'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a' href='/storify/member/user.php'>我的主页</a></li>
					<li class='person_li'><a class='person_a' href='/storify/member/user_setting.php'>设置</a></li>
					<li class='person_li'><a class='person_a' href='".$rooturl."/login/login.php?logout'>退出</a></li>
		          </ul>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/storify/img/logo.png' border='0'></a></span>
	  <span id='user_action'><a href='".$rooturl."/index.php'>首页</a> | <a href='".$rooturl."/member/user.php'>我的故事</a> | <a href='".$rooturl."/member'>创建故事</a>
	  </span>".$content."</div></div><BR>";
    }
	else
	{
	  $content = "<span style='margin: 0; position:absolute; right:0; top:0;'><a class='login_top' href='".$rooturl."/login/login.php'>登录</a></span>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/storify/img/logo.png' border='0'></a></span>
	  <span id='user_action'><a href='".$rooturl."/index.php'>首页</a></span>".$content."</div></div><BR>";
	}
?>
<script>
$(function() {
$('.person_li').mouseover(function(){
$('.person_li').css('display', 'block');
});
$('.user_console').mouseout(function(){
$('.person_li').slice(1, 4).css('display', 'none');
});
});
</script>