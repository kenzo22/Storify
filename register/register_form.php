<?php
include dirname(__FILE__).'/'."../header.php";
?>
<script type="text/javascript">

$(function(){
$('#email_reg').bind('focus', function(){
$('#email_tip').text('用来登录StoryBing， 接收到激活邮件才能完成注册').css('color', '#666699').show();
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
})

/*$(document).ready(function() {
  $('#email_reg').blur(function() {
    var $email  = $(this).val();
    var url = 'check_email.php?email='+$email;
    $.get(url, function(data){ // 这里可以将post换成get
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
  });
});*/

</script>
<form method='post' action='register_new.php'> 
  <div class='div_center' > <span class='title'> 用户注册 </span> &nbsp; &nbsp; <span> (带*的为必填选项) <BR></span>  </div>  
  <div class='div_center' id='sign_up'> 
    <div><span class='field_name'> * EMAIL  </span> <span > <input type='text' name='email' id='email_reg' size='30' maxlength='100'> </span> <span class='form_tip' id='email_tip'></span></div> 
	<div><span class='field_name'> * 密 码 </span> <span > <input type='password' name='passwd' id='pwd_reg' size='16' maxlength='16'> </span> <span class='form_tip' id='pwd_tip'></span> </div> 
	<div><span class='field_name'> * 名 号 </span> <span > <input type='text' name='username' id='name_reg' size='16' maxlength='16'></span> <span class='form_tip' id='name_tip'> </span> </div> 
	<div><span class='field_name'> * 邀请码  </span> <span > <input type='text' name='invite_code' id='code_reg' id='signup_invite_code'></span> <span class='form_tip' id='code_tip'></span> <span><a href='../about/?faq#invitecode'> 如何获取? </a> </span> </div> 
	<div><input type="checkbox" id="agree_term"/><label for="agree_term">同意并接受注册协议</label></div>
	<div>
	  <span > 
        <input type='submit' class='bn_submit' id='btn_submit_signup' value='好了,注册'></input>  
	    <input type='hidden' name='act' value='signup'> 
      </span> 
    </div> 
  </div> 
</form> 
<?php
include "../include/footer.htm";	 
?>