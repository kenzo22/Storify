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
		$user_profile_img = $userresult['photo'];
		$content="<ul class='user_console showborder'>
				    <li class='person_li' style='display:block;'><a class='person_a person_a_display' href='/member/user.php?user_id=".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a' href='/member/user.php?user_id=".$userresult['id']."'>我的主页</a></li>
					<li class='person_li'><a class='person_a' href='/member/user_setting.php'>设置</a></li>
					<li class='person_li'><a class='person_a' href='/login/login.php?logout'>退出</a></li>
		          </ul>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/img/logo.png' border='0'></a></span>
	  <span id='user_action'><a href='/index.php'>首页</a> | <a href='/member/user.php?user_id=".$userresult['id']."'>我的故事</a> | <a href='/member'>创建故事</a>
	  </span>".$content."</div></div><BR>";
    }
	else
	{
	  $content = "<span style='margin: 0; position:absolute; right:0; top:0;'><a class='login_top' href='/login/login.php?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a></span>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/img/logo.png' border='0'></a></span>
	  <span id='user_action'><a href='/index.php'>首页</a></span>".$content."</div></div><BR>";
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
