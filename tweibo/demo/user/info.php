<br>User/info获取自己的资料<br><br>
 <b>function <font  color="red">user_info($format='json') </font></b> <br><br>
获取用户收听的人最新n条微博信息。<br><br>
代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new TWeiboClient(MB_AKEY,MB_SKEY,$_SESSION['last_tkey']['oauth_token'],$_SESSION['last_tkey']['oauth_token_secret']);
$ms  = $c->user_info();
print_r($ms);
? >
</textarea><br><br>
<hr/>
返回的数组：
<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( '../../config.php' );
include_once( '../../txwboauth.php' );
$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
include_once( '../class.krumo.php' );
$ms  = $c->user_info();
krumo($ms);

?>