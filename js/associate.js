$(function(){
$('.unselected').live('click', function(e){
  $('.selected').removeClass('selected').addClass('unselected');
  $(this).removeClass('unselected').addClass('selected');
  $("#form_1").toggle();
  $("#form_2").toggle();
});

$('#email').bind('focus', function(){
$('#email_tip').text('请输入您在口立方已经注册的邮箱').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#email_tip').text('邮箱不能为空').css('color', 'red');
}
else{
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('邮箱格式不正确').css('color', 'red');
}
else
{
  var email  = $(this).val(),
      url = '/accounts/register/check_email.php?email='+email;
  $.get(url, function(data){
  if(data =='1')
  {
	$('#email_tip').text('您可以绑定该邮箱').css('color', '#666699').show();
  }
  else
  {
	$('#email_tip').text('抱歉，该邮箱还未注册').css('color', 'red').show();
  }
  return false;
  });
}
}
});

$('#user_email').bind('focus', function(){
$('#user_email_tip').text('绑定后可直接用来登录口立方').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#user_email_tip').text('邮箱不能为空').css('color', 'red');
}
else{
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#user_email_tip').text('邮箱格式不正确').css('color', 'red');
}
else
{
  var email  = $(this).val(),
      url = '/accounts/register/check_email.php?email='+email;
  $.get(url, function(data){
  if(data =='1')
  {
	$('#user_email_tip').text('该邮箱已被注册').css('color', 'red').show();
  }
  else
  {
	$('#user_email_tip').text('该邮箱可以使用').css('color', '#666699').show();
  }
  return false;
  });
}
}
});

$('#pwd').bind('focus', function(){
$('#pwd_tip').text('请输入该邮箱的注册密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
else
{
  var email_val  = $('#email').val(),
      pwd_val = $(this).val(),
      postdata={email: email_val, pwd: pwd_val},
      url = '/accounts/login/check_credential.php';
  $.post(url, postdata, function(data){
  if(data =='0')
  {
	$('#pwd_tip').text('密码错误，请重新输入').css('color', 'red').show();
	this.value='';
  }
  else
  {
	$('#pwd_tip').text('密码输入正确').css('color', '#666699').show();
  }
  return false;
  });
}
})

$('#user_pwd').bind('focus', function(){
$('#user_pwd_tip').text('字母、数字或符号，最短四个字符，区分大小写').css('color', '#666699').show();
}).bind('blur', function(){
$('#user_pwd_tip').text('');
if(this.value=='')
{
  $('#user_pwd_tip').text('密码不能为空').css('color', 'red');
}
if(this.value!='' && this.value.length<4)
{
  $('#user_pwd_tip').text('密码长度不足四个字符').css('color', 'red');
}
})

$('#user_pwd_confirm').bind('focus', function(){
$('#pwd_confirm_tip').text('请您再次输入密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_confirm_tip').text('');
if(this.value!=$('#user_pwd').val())
{
  $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
  this.value='';
}
})

$('#user_name').bind('focus', function(){
$('#user_name_tip').text('中、英文均可，最长14个英文或7个汉字').css('color', '#666699').show();
}).bind('blur', function(){
$('#user_name_tip').text('');
var name_val = this.value;
if(name_val=='')
{
  $('#user_name_tip').text('名号不能为空').css('color', 'red');
}
else if(name_val.indexOf(" ") != -1)
{
  $('#user_name_tip').text('名号不能包含空格').css('color', 'red');
}
else
{
  var cArr = name_val.match(/[^\x00-\xff]/ig),   
      name_length = name_val.length + (cArr == null ? 0 : cArr.length);
  if(name_length > 14)
  {
    $('#user_name_tip').text('名号长度不能超过14个英文或7个汉字').css('color', 'red');
  }
}
})

$('.aa_submit').click(function(e){
var submitFlag = true, email='', username='', pwd='', pwd_confirm='', tip_flag = true;
if($('#form_1').is(':hidden'))
{
  email = $('#user_email').val();
  username = $('#user_name').val();
  pwd = $('#user_pwd').val();
  pwd_confirm = $('#user_pwd_confirm').val();
  if(pwd != pwd_confirm)
  {
    $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
    $('#user_pwd_confirm').val('');
  }
  tip_flag = ($('#user_email_tip').css('color') == 'red') || ($('#user_name_tip').css('color') == 'red') || ($('#user_pwd_tip').css('color') == 'red') || ($('#pwd_confirm_tip').css('color') == 'red');
  if(tip_flag || email == '' || username == '' || pwd == '' || pwd_confirm == '')
  submitFlag = false;
}
else if($('#form_2').is(':hidden'))
{
  email = $('#email').val();
  pwd = $('#pwd').val();
  tip_flag = ($('#email_tip').css('color') == 'red') || ($('#pwd_tip').css('color') == 'red');
  if(tip_flag || email == '' || pwd == '')
  submitFlag = false;
}
if(submitFlag)
{
  $(this).closest('form').submit();
}
else
{
  e.preventDefault();
}
});

});