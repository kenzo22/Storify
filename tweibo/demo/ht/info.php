<?php
@header('Content-Type:text/html;charset=utf-8'); 
?>
<br>ht/info 根据话题ID获取话题相关信息<br><br>
 <b>function <font  color="red">ht_info($ids,$format='json')</font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->ht_info("5981813276245206975,12809960488402123500");
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
$ms  = $c->ht_info("5981813276245206975,12809960488402123500");
krumo($ms);

?>