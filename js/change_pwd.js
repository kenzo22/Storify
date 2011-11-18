$(function(){
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
  
  $('#btn_submit_modify').click(function(e)
  {
	var old_pwd_val = $('#old_pwd').val();
    var new_pwd_val = $('#new_pwd').val();
    var pwd_cfm_val = $('#new_pwd_cfm').val();
	var tip_flag = ($('#old_pwd_tip').text() != '') || ($('#new_pwd_tip').css('color') == 'red') || ($('#new_pwd_cfm_tip').text() != '');
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