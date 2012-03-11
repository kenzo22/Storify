<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php print $html_title; ?></title>
	<link type='text/css' href="/css/layout.css" rel='stylesheet' />
	<link type="image/ico" rel="shortcut icon"  href="/img/favicon.ico" />
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-27514721-1']);
	  _gaq.push(['_trackPageview']);
	</script>	
  </head>
  <body>
<?php
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
		$content="<ul class='user_console'>
				    <li class='person_li display'><a class='person_a person_a_display' href='/user/".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a home_icon' href='/user/".$userresult['id']."/subscription'><img class='console_img' src='/img/home.png'/><span>我的订阅</span></a></li>
					<li class='person_li'><a class='person_a setting_icon' href='/accounts/setting'><img class='console_img' src='/img/setting.png'/><span>设置</span></a></li>
					<li class='person_li'><a class='person_a quit_icon' href='/accounts/logout'><img class='console_img' src='/img/quit.png'/><span>退出<span></a></li>
		          </ul>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
	  <span id='top_menu_a'><a class='edit_story_btn' href='/create'>开始创作</a></span>".$content."</div></div>";
    }
	else
	{
	  getPublicToken();
	  $content = "<span id='top_menu_b'><a class='register_top' href='/accounts/register'>注册</a><a class='login_top' href='/accounts/login?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='edit_story_btn' href='/create'>开始创作</a></span>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>".$content."</div></div>";
	}
?>


