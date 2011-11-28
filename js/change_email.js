$(function(){
    $('#login_email').bind('blur', function(){
	$('#login_email_tip').text('');
	if(this.value=='')
	{
	  $('#login_email_tip').text('Email不能为空').css('color', 'red').show();
	}
	else if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
	{
	  $('#login_email_tip').text('Email格式不正确').css('color', 'red').show();
	}
	})

	$('#new_login_email').bind('blur', function(){
	$('#new_login_email_tip').text('');
	if(this.value=='')
	{
	  $('#new_login_email_tip').text('Email不能为空').css('color', 'red').show();
	}
	else
	{
	  if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
	  {
		$('#new_login_email_tip').text('Email格式不正确').css('color', 'red').show();
	  }
	  else
	  {
		var $email  = $(this).val();
		var url = '/accounts/register/check_email.php?email='+$email;
		$.get(url, function(data){
		if(data =='1')
		{
		  $('#new_login_email_tip').text('该邮箱已被注册').css('color', 'red').show();
		}
		else
		{
		  $('#new_login_email_tip').text('该邮箱可以使用').css('color', '#666699').show();
		}
		return false;
		})
	  }
	}
	})
	
	$('#new_email_cfm').bind('focus', function(){
	$('#new_email_cfm_tip').text('请您再次输入邮箱').css('color', '#666699').show();
	}).bind('blur', function(){
	$('#new_email_cfm_tip').text('');
	if(this.value!=$('#new_login_email').val())
	{
	  $('#new_email_cfm_tip').text('两次输入邮箱不一致，请重新输入').css('color', 'red');
	  this.value='';
	}
	})
  
  $('#btn_submit_modify').click(function(e)
  {
	var login_email_val = $('#login_email').val();
    var login_pwd_val = $('#login_pwd').val();
    var new_login_email_val = $('#new_login_email').val();
	var new_email_cfm_val = $('#new_email_cfm').val();
	var tip_flag = ($('#login_email_tip').text() != '') || ($('#new_login_email_tip').css('color') == 'red') || ($('#new_email_cfm_tip').css('color') == 'red');
	if(tip_flag || login_email_val == '' || login_pwd_val == '' || new_login_email_val == '' || new_email_cfm_val == '')
    {
      $('.err_notify').remove();
	  $('#c_email_form .page_title').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
	  e.preventDefault();
	}
    else
    {
      $('#c_email_form').submit();
    }
  });
})
