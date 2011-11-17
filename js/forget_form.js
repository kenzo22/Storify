$(function()
{
  $('#signup_email').bind('focus', function(){
  $('#email_tip').text('请输入你的email地址').css('color', '#666699').show();
  }).bind('blur', function(){
    if(this.value=='')
	{
	  $('#email_tip').text('邮箱不能为空').css('color', 'red');
	}
	else
	{
		if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
		{
		  $('#email_tip').text('邮箱格式不正确').css('color', 'red');
		}
		else
		{
		  var email  = $(this).val();
		  var url = '/register/check_email.php?email='+email;
		  $.get(url, function(data){
		  if(data !='1')
		  {
			$('#email_tip').text('该邮箱地址还没有注册过').css('color', 'red');
		  }
		  else
		  {
		    $('#email_tip').text('').css('color', '#666699');
		  }
		  return false;
		  });
		}
	}
  });
  $('#btn_submit_forget').click(function(e)
  {
	var email_val = $('#signup_email').val();
	var tip_flag = $('#email_tip').css('color') == 'red';
	if(tip_flag || email_val == '')
    {
      e.preventDefault();
	}
    else
    {
      $('#f_pwd_form').submit();
    }
  });
});