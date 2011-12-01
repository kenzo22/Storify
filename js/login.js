$(function(){
$('#email_login').focus();
$('#email_login').bind('blur', function(){
$('#email_tip').text('');
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('Email格式不正确').css('color', 'red').show();
}
if(this.value=='')
{
  $('#email_tip').text('Email不能为空').css('color', 'red').show();
}
})

$('#pwd_login').bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red').show();
}
})

$('#loginbtn').click(function(e)
{
  var email_val = $('#email_login').val();
  var pwd_val = $('#pwd_login').val();
  var tip_flag = ($('#email_tip').text() != '') || ($('#pwd_tip').text() != '');
  if(tip_flag || email_val == '' || pwd_val == '')
  {
    e.preventDefault();
  }
  else
  {
    $('#login_form').submit();
  }
})

$('#email_login, #pwd_login').bind('keyup', function(e)
{
  var code = e.keyCode || e.which; 
  if(code == 13)
  {
    var email_val = $('#email_login').val();
    var pwd_val = $('#pwd_login').val();
    var tip_flag = ($('#email_tip').text() != '') || ($('#pwd_tip').text() != '');
    if(tip_flag || email_val == '' || pwd_val == '')
    {
      e.preventDefault();
    }
    else
    {
      $('#login_form').submit();
    }
  }
});

$('#connectBtn').live('click', function(e)
{
e.preventDefault();
$.post('/accounts/login/sina_auth.php', {}, 		
function(data, textStatus)
{
  self.location=data;
});
});

});