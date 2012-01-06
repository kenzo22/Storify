$(function(){
	$('#sina_weibo').click(function(e){
	e.preventDefault();
	var postdata;
	if($(this).text() == '添加')
	{
	  postdata = {operation: 'add'};
	  $.post('/accounts/weibosource.php', postdata,
	  function(data, textStatus)
	  {					
		self.location = data;
	  });
	}
	else
	{
	  postdata = {operation: 'delete'};
	  $.post('/accounts/weibosource.php', postdata,
	  function(data, textStatus)
	  {	
		if(textStatus == 'success')
		{
		  $('#sina_weibo').text('添加');
		  $('#sina_weibo').prev().text('未添加帐号');
		  $('.modify_notify').remove();
		  $('#source_ul').before(data);
		}
	  });
	}
	});

	$('#tencent_weibo').click(function(e){
	e.preventDefault();
	var postdata;
	if($(this).text() == '添加')
	{
	  postdata = {operation: 'add'};
	  $.post('/accounts/tweibosource.php', postdata,
	  function(data, textStatus)
	  {					
		self.location = data;
	  });
	}
	else
	{
	  postdata = {operation: 'delete'};
	  $.post('/accounts/tweibosource.php', postdata,
	  function(data, textStatus)
	  {	
		if(textStatus == 'success')
		{
		  $('#tencent_weibo').text('添加');
		  $('#tencent_weibo').prev().text('未添加帐号');
		  $('.modify_notify').remove();
		  $('#source_ul').before(data);
		}
	  });
	}
	});

	$('#douban_forum').click(function(e){
	e.preventDefault();
	var postdata;
	if($(this).text() == '添加')
	{
	  postdata = {operation: 'add'};
	  $.post('/accounts/doubansource.php', postdata,
	  function(data, textStatus)
	  {					
		self.location = data;
	  });
	}
	else
	{
	  postdata = {operation: 'delete'};
	  $.post('/accounts/doubansource.php', postdata,
	  function(data, textStatus)
	  {	
		if(textStatus == 'success')
		{
		  $('#douban_forum').text('添加');
		  $('#douban_forum').prev().text('未添加帐号');
		  $('.modify_notify').remove();
		  $('#source_ul').before(data);
		}
	  });
	}
	});

	$('#yupoo_pic').click(function(e){
	e.preventDefault();
	var postdata;
	if($(this).text() == '添加')
	{
	  postdata = {operation: 'add'};
	  $.post('/accounts/yupoosource.php', postdata,
	  function(data, textStatus)
	  {					
		self.location = data;
	  });
	}
	else
	{
	  postdata = {operation: 'delete'};
	  $.post('/accounts/yupoosource.php', postdata,
	  function(data, textStatus)
	  {	
		if(textStatus == 'success')
		{
		  $('#yupoo_pic').text('添加');
		  $('#yupoo_pic').prev().text('未添加帐号');
		  $('.modify_notify').remove();
		  $('#source_ul').before(data);
		}
	  });
	}
	});
  
  $('#user_name').bind('focus', function(){
    $('#name_tip').text('最长14个英文或7个汉字').css('color', '#666699').show();;
  }).bind('blur', function(){
  $('#name_tip').text('');
  if(this.value=='')
  {
    $('#name_tip').text('名号不能为空').css('color', 'red');
  }
  else
  {
    var cArr = this.value.match(/[^\x00-\xff]/ig),   
        name_length = this.value.length + (cArr == null ? 0 : cArr.length);
    if(name_length > 14)
    {
      $('#name_tip').text('名号长度不能超过14个英文或7个汉字').css('color', 'red');
    }
  }
  })
  
  $('#update_btn a').click(function(e)
  {
	e.preventDefault();
	$('.update_notify').remove();
	var username_val = $('#user_name').val(),
	    userintro_val = $('#user_intro').val();
	if(($('#name_tip').css('color') == 'red') || username_val == '')
	{
	  return false;
    }
	else
	{
	  var postdata = {username: username_val, userintro: userintro_val};			  
      $.post('/accounts/modifysetting', postdata,
      function(data, textStatus)
      {
		if("success" == textStatus)
	    {
	      $('#lzform').before(data);
	    }
      });
	}
  });
	
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
		var email  = $(this).val(),
		    url = '/accounts/register/check_email.php?email='+email;
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
  
  $('#btn_email_modify').click(function(e)
  {
	var login_email_val = $('#login_email').val(),
        login_pwd_val = $('#login_pwd').val(),
        new_login_email_val = $('#new_login_email').val(),
	    new_email_cfm_val = $('#new_email_cfm').val(),
	    tip_flag = ($('#login_email_tip').text() != '') || ($('#new_login_email_tip').css('color') == 'red') || ($('#new_email_cfm_tip').css('color') == 'red');
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
  
  $('#old_pwd').bind('blur', function(){
	$('#old_pwd_tip').text('');
	if(this.value=='')
	{
	  $('#old_pwd_tip').text('密码不能为空').css('color', 'red').show();
	}
	});
	
	$('#new_pwd').bind('focus', function(){
	$('#new_pwd_tip').text('字母、数字或符号，最短四个字符，区分大小写').css('color', '#666699').show();
	}).bind('blur', function(){
	$('#new_pwd_tip').text('');
	if(this.value=='')
	{
	  $('#new_pwd_tip').text('密码不能为空').css('color', 'red');
	}
	if(this.value!='' && this.value.length<4)
	{
	  $('#new_pwd_tip').text('密码长度不足四个字符').css('color', 'red');
	}
	});

	$('#new_pwd_cfm').bind('blur', function(){
	$('#new_pwd_cfm_tip').text('');
	if(this.value!=$('#new_pwd').val())
	{
	  $('#new_pwd_cfm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red').show();
	  this.value='';
	}
	});
  
  $('#btn_pwd_modify').click(function(e)
  {
	var old_pwd_val = $('#old_pwd').val(),
        new_pwd_val = $('#new_pwd').val(),
        pwd_cfm_val = $('#new_pwd_cfm').val(),
	    tip_flag = ($('#old_pwd_tip').text() != '') || ($('#new_pwd_tip').css('color') == 'red') || ($('#new_pwd_cfm_tip').text() != '');
	if(tip_flag || old_pwd_val == '' || new_pwd_val == '' || pwd_cfm_val == '' || pwd_cfm_val == '')
    {
      $('.err_notify').remove();
	  $('#c_pwd_form .page_title').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
	  e.preventDefault();
	}
    else
    {
      $('#c_pwd_form').submit();
    }
  });
})
