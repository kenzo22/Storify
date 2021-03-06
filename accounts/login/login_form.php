<?php
$html_title = "用户登录 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";

$content ="<div class='inner'>
			 <div id='login_form_wrapper'>
			   <div id='login_form_f'>
			     <div class='form_title'>用户登录</div>
			     <form method='post' id='login_form' action='/accounts/login/login'>";

if(islogin())
    header("location:/");

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
  $redirec_temp = urldecode($_GET['next']);
  $findme = "/user/";
  $pos = strpos($redirec_temp, $findme);
  if ($pos !== false)
  {
    $content .="<input type='hidden' value='".$redirec_temp."' name='redirect_info' id='redirect_info' />";
  }
}
$content .="<div class='form_div'><span class='form_label'>邮&nbsp;箱</span><input type='text' name='email' id='email_login' size='30' /><span class='form_tip' id='email_tip'></span></div>
			<div class='form_div'><span class='form_label'>密&nbsp;码</span><input type='password' name='passwd' id='pwd_login' size='30' /><span class='form_tip' id='pwd_tip'></span></div>
			<div class='auto_login'><span><input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='/accounts/forget_password'>忘记密码了？</a></span></div>
			<div id='loginbtn'><a class='large blue awesome'>登 录 &raquo;</a></div>
		  </form>
		</div>
		<div id='login_form_r'>
		  <div>还没有口立方帐号?</div>
		  <a class='large green awesome register_awesome' href='/accounts/register'>马上注册 &raquo;</a>
		  <div><span>使用新浪微博帐号登录</span></div>
		  <div><a id='connectBtn' href='#'><span class='sina_icon'></span><span class='sina_name'>新浪微博</span></a></div>
		</div>
	  </div>
	<div class='footer_spacer'></div>
  </div>";

echo $content;
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";	
?>
<script type='text/javascript' src='/js/login.js'></script>
</body>
</html>
