$(function(){
$('#new_pwd').bind('focus', function(){
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
if(this.value!=$('#new_pwd').val())
{
  $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
  this.value='';
}
})

$('#btn_cfm_pwd').click(function(e)
{
  var pwd_val = $('#new_pwd').val();
  var pwd_cfm_val = $('#pwd_confirm').val();
  if(pwd_val != pwd_cfm_val)
  {
    $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
    $('#pwd_confirm').val('');
  }
  
  var tip_flag = ($('#pwd_tip').css('color') == 'red') || ($('#pwd_confirm_tip').css('color') == 'red');
  if(tip_flag || pwd_val == '' || pwd_cfm_val == '')
  {
    e.preventDefault();
  }
  else
  {
    $('#new_pwd_form').submit();
  }
})

})