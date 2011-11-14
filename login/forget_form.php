<?php
include "../global.php";
include "../include/mail_functions.php";
include '../include/secureGlobals.php';

if($_GET['act']!="forget_pwd")
{
  $content = "<div class='inner'> 
  <form method='get' id='f_pwd_form' style='margin-top:50px;'>
    <h3> 重设密码 </h3>  
    <div id='forget_passwd' style='float:left;'> 
	  <div>
	    <span style='margin-right:10px;'>邮箱:</span> 
		<span ><input type='text' name='email' id='signup_email' size='30' maxlength='100'></span>
		<span class='form_tip' id='email_tip'></span>
	  </div> 	
      <div style='margin:20px 0 0 40px;'>
	    <a id='btn_submit_forget' class='large blue awesome'>重设密码 &raquo;</a> 
	    <input type='hidden' name='act' value='forget_pwd'>
	  </div>
	</div>
	<div class='float_r'>
      <span>还没有口立方帐号，<a href='/register/register_form.php'/>立即注册？</a></span>
    </div>
</form>
<div style='height:270px;'></div>
</div>";
  echo $content;
}
else
{
  $email=$_GET['email'];  
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$email."'");
  if(!empty($result))
  {
    $username = $result['username']; 
	
	$reset = $DB->fetch_one_array("select reset_code from ".$db_prefix."reset where username='".$username."' AND email='".$email."'");
	if(!empty($reset))
	{
	  $reset_code = $reset['reset_code'];
	}
	$current_time = time();
	
    $url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/forget_passwd.php';
    $url .= '?confirmation='.$reset_code.$current_time;

    $subject = "重设".$username."在口立方的密码";
    $message = '<p>您的密码重设要求已经得到验证。请点击以下链接输入您新的密码: <br/><br/>
	该链接在12小时内有效，过期请到忘记密码页面重新生成链接。<br/><br/>

	pleae click on the following link(effective in next 12 hours) to reset your password:<br/><br/>

	<a href="'.$url.'" target="_blank">'.$url.'</a><br/><br/>

	如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入口立方。<br/><br/>

	感谢您对口立方的支持，再次希望您在口立方的体验有益和愉快。<br/><br/>

	口立方 http://www.koulifang.com<br/><br/>

	(这是一封自动产生的email，请勿回复。)</p>';

    if(sendEmail($email,$subject,$message))
    {
      $content="<div class='inner' style='padding-top:50px;'> 
	              <h1> 重设密码 </h1> 
				  <div><span>请到 ".$email." 查阅来自口立方的邮件, 从邮件重设你的密码。<span></div>
				  <div class='float_r'>
					<span>还没有口立方帐号，<a href='/register/register_form.php'/>立即注册？</a></span>
				  </div>
				</div>
				<div class='inner' style='height:250px;'></div>";
	  echo $content;
    }
    else
    {
      echo "Mailer Error: ";
    }
  }
}

include "../include/footer.htm";	 
?>
<script type="text/javascript">
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
</script>
