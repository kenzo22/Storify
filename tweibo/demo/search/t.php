<br>Search/t 搜索微博<br><br>
 <b>function <font  color="red">search_t($keyword,$page=1,$pagesize=10,$format='json')</font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?php
@header('Content-Type:text/html;charset=utf-8'); 
if(isset($_POST['content']))
{
	@session_start();
	include_once( 'config.php' );
	include_once( 'txwboauth.php' );
	$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
	$ms=$c->search_t($_REQUEST['content']);
	
}
? >

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  搜索内容：<input type="text" name="content" id="content" />
  <input type="submit" name="button" id="button" value="搜索" />
</form>
</body>
</html>
</textarea><br><br>
<hr/>
<br>演示：<br>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  搜索内容：<input type="text" name="content" id="content" />
  <input type="submit" name="button" id="button" value="搜索" />
</form>



<?php

@header('Content-Type:text/html;charset=utf-8'); 
if(isset($_POST['content']))
{
	@session_start();
	include_once( '../../config.php' );
	include_once( '../../txwboauth.php' );
	$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
	$ms=$c->search_t($_REQUEST['content']);
   // print_r($r);
	
include_once( '../class.krumo.php' );
echo "提交后返回的数组：<br>";
krumo($ms);
}
?>
