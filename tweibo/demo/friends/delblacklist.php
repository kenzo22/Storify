<br>friends/delblacklist 从黑名单释放某用户<br><br>
 <b>function <font  color="red">f_del_black($name='',$format='json')  </font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->f_del_black("liyi2099");
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
$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
include_once( '../class.krumo.php' );
$ms  = $c->f_del_black("liyi2099");
krumo($ms);

?>