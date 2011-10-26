<?php
include "../global.php";
?>
<form method='post' action='register_new.php' id='register_form'> 
  <div class='inner' style='padding-top:50px;' id='register_title'><span class='title'>新用户注册</span></div>  
  <div class='inner' id='sign_up'> 
    <div><span class='field_name'>邮 箱</span> <span > <input type='text' name='email' id='email_reg' size='30' maxlength='100'> </span> <span class='form_tip' id='email_tip'></span></div> 
	<div><span class='field_name'>密 码</span> <span > <input type='password' name='passwd' id='pwd_reg' size='30' maxlength='16'> </span> <span class='form_tip' id='pwd_tip'></span> </div>
	<div><span class='field_name'>确认密码</span> <span > <input type='password' name='pwd_confirm' id='pwd_confirm' size='30' maxlength='16'> </span> <span class='form_tip' id='pwd_confirm_tip'></span> </div> 
	<div><span class='field_name'>名 号</span> <span > <input type='text' name='username' id='name_reg' size='30' maxlength='16'></span> <span class='form_tip' id='name_tip'> </span> </div> 
	<div><span class='field_name'>邀请码</span> <span > <input type='text' name='invite_code' id='code_reg' id='signup_invite_code' size='30' maxlength='16'></span> <span class='form_tip' id='code_tip' style='display:inline;'><a href='../about/?faq#invitecode'> 如何获取? </a></span></div> 
	<div style='padding-left:80px;'><input type='checkbox' id='agree_term' name='term' value='term'/><label for='agree_term'>我已经认真阅读并同意口立方的使用协议</label><span id='term_tip'></span></div>
	<div id='btn_submit_signup' style='padding-left:80px;'>
	  <a class='large blue awesome'>完成注册 &raquo;</a> 
    </div> 
	<div style='height:30px;'></div>
  </div>
</form> 
<?php
include "../include/footer.htm";	 
?>
<script type="text/javascript">
$(function(){
$('#email_reg').bind('focus', function(){
$('#email_tip').text('用来登录口立方， 接收到激活邮件才能完成注册').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#email_tip').text('Email不能为空').css('color', 'red');
}


else{
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('Email格式不正确').css('color', 'red');
}
else
{
  var $email  = $(this).val();
  var url = 'check_email.php?email='+$email;
  $.get(url, function(data){
  if(data =='1')
  {
	$('#email_tip').text('该邮箱已被注册').css('color', 'red').show();
  }
  else
  {
	$('#email_tip').text('该邮箱可以使用').css('color', '#666699').show();
  }
  return false;
  })
}
}
})

$('#pwd_reg').bind('focus', function(){
$('#pwd_tip').text('字母、数字或符号，最短四个字符，区分大小写').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
if(this.value!='' && this.value.length<4)
{
  $('#pwd_tip').text('密码长度不足四个字符').css('color', 'red');
}
})

$('#pwd_confirm').bind('focus', function(){
$('#pwd_confirm_tip').text('请您再次输入密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_confirm_tip').text('');
if(this.value!=$('#pwd_reg').val())
{
  $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
  this.value='';
}
})

$('#name_reg').bind('focus', function(){
$('#name_tip').text('中、英文均可，最长14个英文或7个汉字').css('color', '#666699').show();
}).bind('blur', function(){
$('#name_tip').text('');
if(this.value=='')
{
  $('#name_tip').text('名号不能为空').css('color', 'red');
}
if(this.value.length>14)
{
  $('#name_tip').text('名号长度不能超过14个英文或7个汉字').css('color', 'red');
}
})

$('#agree_term').click(function(e){
  if($('#agree_term').attr('checked'))
  {
    $('#term_tip').text('').css('color', '#666699');
  }
  else
  {
	$('#term_tip').text('请勾选同意注册协议').css('color', 'red').show();
  }
});

$('#btn_submit_signup a').click(function(e)
{
  var email_val = $('#email_reg').val();
  var pwd_val = $('#pwd_reg').val();
  var pwd_cfm_val = $('#pwd_confirm').val();
  var name_val = $('#name_reg').val();
  var icode_val = $('#code_reg').val();
  if(pwd_val != pwd_cfm_val)
  {
    $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
    $('#pwd_confirm').val('');
  }
  
  if(!$('#agree_term').attr('checked'))
  {
    $('#term_tip').text('请勾选同意注册协议').css('color', 'red').show();
  }
  
  var tip_flag = ($('#email_tip').css('color') == 'red') || ($('#pwd_tip').css('color') == 'red') || ($('#pwd_confirm_tip').css('color') == 'red') || ($('#name_tip').css('color') == 'red') || ($('#term_tip').css('color') == 'red');
  if(tip_flag || email_val == '' || pwd_val == '' || pwd_cfm_val == '' || name_val == '' || icode_val == '')
  {
    e.preventDefault();
	$('#register_title').append('<div style=\"color:red; margin-top:5px;\">表单有误或未填写完整，请参考红色提示</div>');
  }
  else
  {
    $('#register_form').submit();
  }
})

})
</script>
