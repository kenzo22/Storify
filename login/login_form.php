<?php
$html_title = "用户登录 - 口立方";
require "../global.php";
require  "../include/header.php";

$content ="<div class='inner'>
			 <div id='login_form_wrapper'>
			   <div id='login_form_f'>
			     <div class='form_title'>用户登录</div>
			     <form method='post' id='login_form' action='login.php'>";
if($_GET['next'] == 'error_flag')
{
  $content .="<div><span class='color_alert'>您的Email和密码不符，请再试一次</span></div>";
}
else if($_GET['next'] == 'inactivate')
{
  $content .="<div><span class='color_alert'>您的口立方帐号还没有激活，请查收口立方发出的激活邮件</span></div>";
}
else if(!empty($_GET['next']))
{
  $content .="<input type='hidden' value='".$_GET['next']."' name='redirect_info' id='redirect_info' />";
}
$content .="<div class='form_div'><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='30' /><span class='form_tip' id='email_tip'></span></div>
			<div class='form_div'><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='30' /><span class='form_tip' id='pwd_tip'></span></div>
			<div class='auto_login'><span><input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='/login/forget_form.php'>忘记密码了？</a></span></div>
			<div id='loginbtn'><a class='large blue awesome'>登 录 &raquo;</a></div>
		  </form>
		</div>
		<div id='login_form_r'>
		  <div>还没有口立方帐号?</div>
		  <a class='large green awesome register_awesome' href='/register/register_form.php'>马上注册 &raquo;</a>
		  <div><span>使用新浪微博帐号登录</span></div>
		  <div><a id='connectBtn' href='#'><span class='sina_icon'></span><span class='sina_name'>新浪微博</span></a></div>
		</div>
	  </div>
	<div class='footer_spacer'></div>
  </div>";

echo $content;
include "../include/footer.htm";	
?>
<script type='text/javascript' src='../js/login.js'></script>
</body>
</html>