<?php
require_once dirname(__FILE__).'/'."connect_db.php";  
include "include/functions.php";
require "include/user_auth_fns.php";
require "include/editorheader.php";
 
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
  $content="<div id='actions' style='display:block; position:absolute; top:4px; right:0;'>
				<span><a id='draftBtn' href='./' >保存草稿</a></span>
				<span><a id='previewBtn' href='./' >预览</a></span>
				<span><a id='publishBtn' class='large blue awesome' href='./' >发布 &raquo;</a></span>
			  </div>";
  $userresult=$DB->fetch_one_array("SELECT id, photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>".$content."</div></div><BR>";
}
else
{
  $content="<div id='actions' style='display:block; position:absolute; top:4px; right:0;'>
				<span><a id='draftBtn' class='disable' href='./' >保存草稿</a></span>
				<span><a id='previewBtn' class='disable' href='./' >预览</a></span>
				<span><a id='publishBtn' class='large blue awesome disable' href='./' >发布 &raquo;</a></span>
			  </div>";
  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
  ".$content."</div></div><BR>";
}
?>
<script type="text/javascript">
function addBookmark() 
{
    var title='口立方';
    var url='http://www.koulifang.com';
    if(window.sidebar)
	{
      window.sidebar.addPanel(title, url, "");
    }
	else if(document.all) 
	{
      window.external.AddFavorite(url, title);
    } 
	else
	{
      alert('请按 Ctrl + D 为你的浏览器添加书签！');
    }
}
</script>
