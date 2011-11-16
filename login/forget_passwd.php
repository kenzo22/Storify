<?php
$html_title = "重设密码 - 口立方";
require "../global.php";
require  "../include/header.php";
include '../include/secureGlobals.php';
  if($_POST['act']!="cfm_pwd")
  {
	$content="<div class='inner'>
			    <form method='post' id='new_pwd_form' style='margin-top:30px; overflow:auto;'>
				  <h2>重设密码</h2>
				  <div><span class='field_name'>新口令</span><span><input type='password' name='new_pwd' id='new_pwd' size='30' maxlength='100'></span><span class='form_tip' id='pwd_tip'></span></div> 
				  <div style='margin-top:20px;'><span class='field_name'>再输一次</span><span><input type='password' name='pwd_confirm' id='pwd_confirm' size='30' maxlength='100'></span><span class='form_tip' id='pwd_confirm_tip'></span></div>
				  <div style='margin-top:20px;'>
					<a id='btn_cfm_pwd' class='large blue awesome'>确认新密码 &raquo;</a> 
					<input type='hidden' name='act' value='cfm_pwd'>
				  </div>
				</form>
				<div style='height:250px;'></div>
			  </div>";
	echo $content;
  }
  else
  {
	$confirmation = $_GET['confirmation'];
    $reset_code = substr($confirmation, 0, 8);
    $send_time = substr($confirmation, 8);
    $current_time = time();
    if(($current_time-$send_time)>43200)
    {
      go("/login/forget_form.php","该链接已过期失效，请重新到忘记密码页面生成新链接",2);
      exit;
    }
	$reset = $DB->fetch_one_array("select username, email from ".$db_prefix."reset where reset_code='".$reset_code."'");
    if(!empty($reset))
	{
	  $username = $reset['username'];
	  $email = $reset['email'];
	}
	$result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user where username='".$username."' AND email='".$email."'");
	if($result)
	{
      $pwd=sha1($_POST['new_pwd']);
	  $DB->query("update ".$db_prefix."user set passwd='".$pwd."' where username='".$username."' AND email='".$email."'");
      session_destroy();
	  go("/login/login_form.php","修改密码成功,请重新登陆",2);
	  exit;
	}
  }
  include "../include/footer.htm";
?>
<script type="text/javascript">
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
</script>
</body>
</html>
