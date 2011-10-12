<?php
include "../global.php";

$content ="<form method='post' id='login_form' action='login.php'>
<div class='inner' style='padding-top:50px;'><span class='title'> 登录 Koulifang.com </span></div>
<div class='inner'>
<div class='float_l' style='margin-top:20px;' id='login'>";
if(!isset($_GET['next']))
{
  $content .="<div style='margin:0;'><span style='color:red;'>您的Email和密码不符，请再试一次</span></div>";
}
else if($_GET['next'] == 'inactivate')
{
  $content .="<div style='margin:0;'><span style='color:red;'>您的口立方帐号还没有激活，请查收口立方发出的激活邮件</span></div>";
}
$content .="<div><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='30'></input><span class='form_tip' id='email_tip'></span></div>
  <div><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='30'></input><span class='form_tip' id='pwd_tip'></span></div><br />
  <span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/login/forget_form.php'/>忘记密码了？</a><span>
  <div id='loginbtn'>
	<a> 
        <span>登 录</span>  
    </a> 
  </div>
</div>
<div class='float_r' style='margin-top:40px;'>
  <span>还没有口立方帐号，<a href='/register/register_form.php'/>立即注册？</a></span>
  <div style='margin-top:10px;'><span align='center'>使用新浪微博帐号登录</span></div>
  <div style='margin-top:5px;'><a id='connectBtn' href='#' style='margin-top:17px;'><img src='/img/weibo.png' /></a></div></div>
</div>
<div class='inner' style='height:50px;'></div>
</form>";

echo $content;
?>

<script type="text/javascript">
$(function(){
$('#pwd_login').focus();
$('#email_login').bind('focus', function(){
$('#email_tip').text('请输入你的email地址').css('color', '#666699').show();
}).bind('blur', function(){
$('#email_tip').text('');
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('Email格式不正确').css('color', 'red');
}
if(this.value=='')
{
  $('#email_tip').text('Email不能为空').css('color', 'red');
}
})
$('#pwd_login').bind('focus', function(){
$('#pwd_tip').text('请输入你在口立方注册的密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
})

$('#loginbtn').click(function(e)
{
  var email_val = $('#email_login').val();
  var pwd_val = $('#pwd_login').val();
  var tip_flag = ($('#email_tip').css('color') == 'red') || ($('#pwd_tip').css('color') == 'red');
  if(tip_flag || email_val == '' || pwd_val == '')
  {
    e.preventDefault();
  }
  else
  {
    $('#login_form').submit();
  }
})

$('#connectBtn').live('click', function(e)
{
e.preventDefault();
$.post('sina_auth.php', {}, 		
function(data, textStatus)
{
  self.location=data;
});
});

});
</script>

<?php
include "../include/footer.htm";	 
?>