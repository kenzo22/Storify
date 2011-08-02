<br>friends/check 检测是否我听众或我收听的人<br><br>
 <b>function <font  color="red">f_check($names='',$flag=0,$format='json')   </font></b> <br><br>
 $flag 0:粉丝  1：偶像<br> $names = "name1,name2,name3......." 最多30个<br><br> 
代码示例：<br>
<textarea name="" rows="20" cols="130">
< ?
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once('config.php');
include_once('txwboauth.php');
$c = new WeiboClient(WB_AKEY,WB_SKEY,$_SESSION['last_key']['oauth_token'],$_SESSION['last_key']['oauth_token_secret']);
$ms  = $c->f_check("cgisky,liyi2099");
print_r($ms);
$ms2  = $c->f_check("cgisky,liyi2099",1);
print_r($ms2);
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
$ms  = $c->f_check("cgisky,liyi2099");
krumo($ms);
$ms2  = $c->f_check("cgisky,liyi2099",1);
krumo($ms2);
?>