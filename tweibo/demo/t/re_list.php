<br>t/re_list 获取单条微博的转播理由/点评列表<br><br>
 <b>function <font  color="red">t_re_list($flag,$rootid,$pageflag=0,$pagetime=0,$reqnum=20,$twitterid=0,$format='json')</font></b> <br><br>
$flag: 0 转播列表；1 点评列表； 2 转播与点评列表<br><br>
代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->t_re_list(2,"38029001663752");
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
$ms  = $c->t_re_list(2,"38029001663752");
krumo($ms);

?>