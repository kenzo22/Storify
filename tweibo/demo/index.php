<br><center>腾迅围脖API  PHP_SDK  流氓版</center><br><br>
 <b><font  color="red"><center>开源发布</center></font></b> <br><br>

欢迎关注 @cgisky    使用本SDK的页面应用 可否加上本网站连接 http://ooapp.net(目前还不能访问，即将开通)<br>
这个要求不是强制的，<br>如果你在你的应用添加了上面的友情连接，<br>请在腾迅微博私信或者 @cgisky 告诉我，<br>我将记录下来，本SDK更新后我将及时通知你！<br><br>


<hr/>
<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( '../config.php' );
include_once( '../txwboauth.php' );
$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
include_once( 'class.krumo.php' );
$ms  =  $c->getinfo();
$user = $ms[data];
echo "用户名：".$user[name]."<br>";
echo "昵  称：".$user[nick]."<br>";
echo "生  日：".$user[birth_year]."年".$user[birth_month]."月".$user[birth_day]."日<br>";
echo "听众数：".$user[fansnum]."<br>";
echo "收听数：".$user[idolnum]."<br><br><br>";

krumo($ms);

?>