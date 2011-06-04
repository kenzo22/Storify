<?php
    //无数据库连接的网站头 
    
    include "config/global_config.php";  //读入配置文件
    include "include/header.htm";
	require_once "include/functions.php";
	require_once "include/user_auth_fns.php";
	//echo "<div class='div_center_870' id='top'><div class='inner'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/storify/img/logo.png' border='0'></a></span></div></div></div><BR>";
	if(islogin())
    { 
		$content="<span class='user_console'>欢迎，<a href='".$rooturl."/login/forget_passwd.php'><b>".$_SESSION['username']."</b> </a>
						<a href='".$rooturl."/login/forget_passwd.php'></a> 
						<a href='".$rooturl."/login/login.php?logout'>&nbsp;&nbsp;[退出]</a></span>";
	  echo "<div class='div_center_870' id='top'><div class='inner'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/storify/img/logo.png' border='0'></a></span>".$content."</div></div></div><BR>";
    }
	else
	{
	  echo "<div class='div_center_870' id='top'><div class='inner'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='/storify/img/logo.png' border='0'></a></span></div></div></div><BR>";
	}
?>
