<?php
include dirname(__FILE__).'/'."../header.php";
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
})
</script>

<form method='post' action='login.php'>
  <div class='div_center' ><span class='title'> 登录 koulifang.com </span></div>
  <div class='div_center'>
    <div class='float_l' style='margin-top:20px;' id='login'>
	  <div><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='20' value='' onclick='this.value=""'></input><span class='form_tip' id='email_tip'></span></div>
	  <div><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='20' onclick='this.value=""'></input><span class='form_tip' id='pwd_tip'></span></div><br />
	  <span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/storify/login/forget_form.php'/>忘记密码了？</a><span>
	  <div><span style='color:red;'>请输入你在口立方的注册密码</span></div>
	  <div>
        <input type='submit' value='登录'/>
	  </div>	  
	</div>
  </div>
  <div class='div_center' style='height:50px;'></div>
</form> 
<?php
include "../include/footer.htm";	 
?>