<?php
include "../global.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/weibooauth.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();
//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['SCRIPT_URI'].'/callback.php');
//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $_SERVER['REQUEST_URI'].'/callback.php');

$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , 'http://story.com/storify/weibo/callback.php');

$_SESSION['keys'] = $keys;
?>

<div class='div_center' >
<span>为了您更好的使用该服务，请您选择要添加的信息源</span>
<ul>
  <li id='sina_weibo'><a href="<?=$aurl?>">新浪微博</a></li>
  <li id='tencent_weibo'><a>腾讯微博</a></li>
  <li id='renren'><a>人人网</a></li>
</ul>
<ul id='source_info'></ul>
</div>


<?php
include "../include/footer.htm";
?>