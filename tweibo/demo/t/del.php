<br>t/del 删除一条微博<br><br>
 <b>function <font  color="red">t_del($id,$format='json')</font></b> <br><br>

代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->t_del(25028116496092);
print_r($ms);
? >
</textarea><br><br>
<hr/>
返回的数组：
<?php
@header('Content-Type:text/html;charset=utf-8'); 
include_once( '../class.krumo.php' );
$ms  =array();
$ms[ret]=0;
$ms[msg]="ok";
$ms[data][tweetid]=25028116496092;
krumo($ms);

?>