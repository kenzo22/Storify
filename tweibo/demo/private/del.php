<?php
@header('Content-Type:text/html;charset=utf-8'); 
?>
<br>private/del 发一条私信<br><br>
 <b>function <font  color="red">pm_del($id,$format='json')</font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="140">
< ?php
@header('Content-Type:text/html;charset=utf-8'); 
if(isset($_POST['content']))
{
	@session_start();
	include_once( 'config.php' );
	include_once( 'txwboauth.php' );
	$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
	$ms=$c->pm_del("xxxxxxxxxxxxxx");
	
}
? >

</textarea><br><br>
<hr/>

