$(function()
{
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
    var cArr = this.value.match(/[^\x00-\xff]/ig);   
    var name_length = this.value.length + (cArr == null ? 0 : cArr.length);
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
	var username_val = $('#user_name').val();
	var userintro_val = $('#user_intro').val();
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
});