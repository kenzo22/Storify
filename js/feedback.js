$('#email_val').bind('blur', function()
{
	$('#email_tip').text('');
	if(this.value=='')
	{
	  $('#email_tip').text('Email不能为空').css('color', 'red').show();
	}
	else if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
	{
	  $('#email_tip').text('Email格式不正确').css('color', 'red').show();
	}
})

$('#btn_submit_fb').click(function(e)
  {
	var name_val = $('#name_val').val();
    var email_val = $('#email_val').val();
    var fb_val = $('#fb_val').val();
	var tip = $('#email_tip').text();
	if(tip != '' || name_val == '' || email_val == '' || fb_val == '')
    {
      $('.err_notify').remove();
	  $('#fb_form .title').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
	  e.preventDefault();
	}
    else
    {
      $('#fb_form').submit();
    }
  });