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