$(function(){
$('#email_reg').bind('focus', function(){
$('#email_tip').text('用来登录口立方， 接收到激活邮件才能完成注册').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#email_tip').text('Email不能为空').css('color', 'red');
}
else
{
  if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
  {
    $('#email_tip').text('Email格式不正确').css('color', 'red');
  }
  else
  {
    var $email  = $(this).val();
    var url = '/accounts/register/check_email.php?email='+$email;
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
else
{
  var cArr = this.value.match(/[^\x00-\xff]/ig);   
  var name_length = this.value.length + (cArr == null ? 0 : cArr.length);
  if(name_length > 14)
  {
    $('#name_tip').text('名号长度不能超过14个英文或7个汉字').css('color', 'red');
  }
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
  //var icode_val = $('#code_reg').val();
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
  if(tip_flag || email_val == '' || pwd_val == '' || pwd_cfm_val == '' || name_val == '')
  {
    e.preventDefault();
	$('.err_notify').remove();
	$('#register_title').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
  }
  else
  {
    $('#register_form').submit();
  }
})

$('#email_reg, #pwd_reg, #pwd_confirm, #name_reg, #agree_term').bind('keyup', function(e)
{
  var code = e.keyCode || e.which; 
  if(code == 13)
  {
      var email_val = $('#email_reg').val();
	  var pwd_val = $('#pwd_reg').val();
	  var pwd_cfm_val = $('#pwd_confirm').val();
	  var name_val = $('#name_reg').val();
	  //var icode_val = $('#code_reg').val();
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
	  if(tip_flag || email_val == '' || pwd_val == '' || pwd_cfm_val == '' || name_val == '')
	  {
		e.preventDefault();
		$('.err_notify').remove();
		$('#register_title').append('<div class=\"err_notify\">表单有误或未填写完整，请参考红色提示</div>');
	  }
	  else
	  {
		$('#register_form').submit();
	  }
  }
});

})
