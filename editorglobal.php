<?php
   include dirname(__FILE__).'/'."config/global_config.php"; 
   require_once dirname(__FILE__).'/'."connect_db.php";  

   require_once "include/functions.php";
   require_once "include/user_auth_fns.php";

   include  "include/editorheader.htm"; 
   session_start();
   $debug=1;
    
   if (!empty($_SERVER[HTTP_REFERER])) $url=htmlspecialchars($_SERVER[HTTP_REFERER]); 
   
   if (get_magic_quotes_gpc()) {  //magic_quotes_gpc开了会加"\" 先去掉
        $_GET = stripslashes_array($_GET);
        $_POST = stripslashes_array($_POST);
        $_COOKIE = stripslashes_array($_COOKIE); 
        $GLOBALS = stripslashes_array($GLOBALS);
   } 
   set_magic_quotes_runtime(0);
	if(islogin())
    { 
		$userresult=$DB->fetch_one_array("SELECT id, photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
		$content="<div id='actions' style='position:absolute; top:0; right:0;'>
					<span><a id='draftBtn' href='./' >保存草稿</a></span> | 
					<span><a id='previewBtn' href='./' >预览</a></span> |
					<span><a id='publishBtn' href='./' >发布</a></span>
				  </div>";
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='img/logo.png' border='0'></a></span>
	  <span id='user_action'><a href='/index.php'>主页</a> | <a href='/member/user.php?user_id=".$userresult['id']."'>我的故事</a> | <a href='/member'>创建故事</a>
	  </span>".$content."</div></div><BR>";
    }
	else
	{
	  echo "<div id='global_bar'><div></div></div><div id='top_bar'><div class='top_nav'><span id='logo'><a title='StoryBingLogo' accesskey='h' href='/'><img src='img/logo.png' border='0'></a></span></div></div><BR>";
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
