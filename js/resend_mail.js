$(function(){
$('#a_resend').click(function(e)
{
    debugger;
  e.preventDefault();
  $('.a_notify').remove();
  var ori_info = $('#imply_info').val();
  var info = decodeURIComponent(ori_info);
  var temp_array = info.split('&');
  var postdata = {uname: temp_array[0], email: temp_array[1]};
  $.post('/accounts/register/send_mail.php', postdata,
	function(data, textStatus)
	{					
	  if(data == 1)
	  {
		$('#a_flag').after('<div class=\"a_notify\">邮件已重新发送，请查收！</div>');
	  }
	});
});
});
