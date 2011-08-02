<br>Statuses/broadcast_timeline 我发表时间线<br><br>
 <b>function <font  color="red">broadcast_timeline($pageflag=0,$pagetime=0,$reqnum=20,$format='json')</font></b> <br><br>
获取用户本人发表的最新n条微博。。<br><br>
代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new TWeiboClient(MB_AKEY,MB_SKEY,$_SESSION['last_tkey']['oauth_token'],$_SESSION['last_tkey']['oauth_token_secret']);
$ms  = $c->broadcast_timeline();
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
$ms  =  $c->broadcast_timeline();
krumo($ms);

?>