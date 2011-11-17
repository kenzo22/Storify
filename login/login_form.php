<?php
$html_title = "用户登录 - 口立方";
require "../global.php";
require  "../include/header.php";

$content ="<form method='post' id='login_form' action='login.php'>
<div class='inner' style='padding-top:50px;'><span class='title'> 登录 Koulifang.com </span></div>
<div class='inner'>
<div class='float_l' style='margin-top:10px;' id='login'>";
if($_GET['next'] == 'error_flag')
{
  $content .="<div style='margin:0;'><span style='color:red;'>您的Email和密码不符，请再试一次</span></div>";
}
else if($_GET['next'] == 'inactivate')
{
  $content .="<div style='margin:0;'><span style='color:red;'>您的口立方帐号还没有激活，请查收口立方发出的激活邮件</span></div>";
}
else if(!empty($_GET['next']))
{
  $content .="<input type='hidden' value='".$_GET['next']."' name='redirect_info' id='redirect_info' />";
}
$content .="<div><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='30'></input><span class='form_tip' id='email_tip'></span></div>
  <div><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='30'></input><span class='form_tip' id='pwd_tip'></span></div><br />
  <span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/login/forget_form.php'/>忘记密码了？</a><span>
  <div id='loginbtn'>
	<a class='large blue awesome'>登 录 &raquo;</a>
  </div>
</div>
<div class='float_r'>
  <div style='margin-bottom:5px;'>还没有口立方帐号?</div>
  <a class='large green awesome register_awesome' href='/register/register_form.php'/>马上注册 &raquo;</a>
  <div style='margin-top:20px;'><span align='center'>使用新浪微博帐号登录</span></div>
  <div style='margin-top:5px;'><a id='connectBtn' href='#'><div class='sina_icon'></div><div class='sina_name'>新浪微博</div></a></div>
</div>
<div class='inner' style='height:250px;'></div>
</form>";

echo $content;
include "../include/footer.htm";	
?>
<script type='text/javascript' src='../js/login.js'></script>
</body>
</html>