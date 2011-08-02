<br>t/add_pic 发表一条带图片的微博<br><br>
 <b>function <font  color="red"> t_add_pic($content='',$pic_data='',$jing='',$wei='',$format='json') </font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?php
@header('Content-Type:text/html;charset=utf-8'); 
if(isset($_POST['content']))
{
	@session_start();
	include_once( 'config.php' );
	include_once( 'txwboauth.php' );
	$pic=file_get_contents($_FILES['pic']['tmp_name']);
	$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
	$ms=$c->t_add_pic($_REQUEST['content'],$pic);
	
}
? >

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  内容：<input type="text" name="content" id="content" />
    图片：<input type="file" name="pic" id="pic" />
  <input type="submit" name="button" id="button" value="提交" />
</form>
</body>
</html>
</textarea><br><br>
<hr/>
<br>演示发布微博：<br>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  内容：<input type="text" name="content" id="content" />
    图片：<input type="file" name="pic" id="pic" />
  <input type="submit" name="button" id="button" value="提交" />
</form>



<?php

@header('Content-Type:text/html;charset=utf-8'); 
if(isset($_POST['content']))
{
	@session_start();
	include_once( '../../config.php' );
	include_once( '../../txwboauth.php' );
	$pic=file_get_contents($_FILES['pic']['tmp_name']);
	$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
	$ms=$c->t_add_pic($_REQUEST['content'],$pic);
   // print_r($r);
	
include_once( '../class.krumo.php' );
echo "提交后返回的数组：<br>";
echo $c->get_ip();
krumo($ms);
}
?>

