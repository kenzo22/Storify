<?php
@header('Content-Type:text/html;charset=utf-8'); 
?>
<br>fav/delht 取消一条话题的收藏<br><br>
 <b>function <font  color="red">fav_del_ht($id,$format='json')</font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->fav_del_ht("xxxxxxxxxxxx");
print_r($ms);
? >
</textarea><br><br>
<hr/>
