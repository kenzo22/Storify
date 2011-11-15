<?php
$html_title = "更改登录邮箱 - 口立方";
include "../global.php";
include '../include/secureGlobals.php';

if(!islogin())
{
  header("location: /login/login_form.php"); 
  exit;
}

if($_POST['act']!="change_email")
{
  $content = "<div class='inner'> 
  <form method='post' id='c_email_form' style='margin-top:30px;'>
    <h3> 修改登录邮箱 </h3>"; 
  
  if($_GET['next'] == 'error_flag'){
    $content .="<div class=\"err_notify\">帐号与登录密码不匹配，请重新输入</div>";
  }
  $content .= "<div class='wrapper'> 
	  <div>
	    <span class='field_name'>你的登录邮箱:</span> 
		<span ><input type='text' name='login_email' id='login_email' size='30' maxlength='100'></span>
		<span class='form_tip' id='login_email_tip'></span>
	  </div> 
	  <div>
	    <span class='field_name'>你的登录密码:</span> 
	    <span ><input type='password' name='login_pwd' id='login_pwd' size='30' maxlength='100'></span>
	    <span class='form_tip' id='login_pwd_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>新登录邮箱:</span> 
	    <span ><input type='text' name='new_login_email' id='new_login_email' size='30' maxlength='100'></span>
	    <span class='form_tip' id='new_login_email_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>再输入一次:</span> 
	    <span ><input type='text' name='new_email_cfm' id='new_email_cfm' size='30' maxlength='100'></span>
	    <span class='form_tip' id='new_email_cfm_tip'></span>
	  </div>
      <div style='margin:20px 0 0 80px;'>
	    <a id='btn_submit_modify' class='large blue awesome'>确认修改邮箱 &raquo;</a> 
	    <input type='hidden' name='act' value='change_email'>
	  </div>
	</div>
  </form>
  <div style='height:270px;'></div>
  </div>";
  echo $content;
}
else
{
  $login_email=$_POST['login_email'];
  $login_pwd=sha1(trim($_POST["login_pwd"]));
  $new_login_email=$_POST['new_login_email'];
  
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$login_email."' AND passwd='".$login_pwd."'" );
  if(!empty($result))
  {
    $update_result=$DB->query("update ".$db_prefix."user set email='".$new_login_email."'  WHERE email='".$login_email."' AND passwd='".$login_pwd."'" );
	session_destroy();
	go("/login/login_form.php", "修改邮箱成功，请重新登录", 2);
  }
  else
  {
    go("/login/change_email.php?next=error_flag");
  }  
}

include "../include/footer.htm";	 
?>
<script type="text/javascript">
$(function(){
    $('#login_email').bind('blur', function(){
	if(this.value=='')
	{
	  $('#login_email_tip').text('Email不能为空').css('color', 'red').show();
	}
	else if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
	{
	  $('#login_email_tip').text('Email格式不正确').css('color', 'red').show();
	}
	})

	$('#new_login_email').bind('blur', function(){
	if(this.value=='')
	{
	  $('#new_login_email_tip').text('Email不能为空').css('color', 'red').show();
	}
	else
	{
	  if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
	  {
		$('#new_login_email_tip').text('Email格式不正确').css('color', 'red').show();
	  }
	  else
	  {
		var $email  = $(this).val();
		var url = '../register/check_email.php?email='+$email;
		$.get(url, function(data){
		if(data =='1')
		{
		  $('#new_login_email_tip').text('该邮箱已被注册').css('color', 'red').show();
		}
		else
		{
		  $('#new_login_email_tip').text('该邮箱可以使用').css('color', '#666699').show();
		}
		return false;
		})
	  }
	}
	})
	
	$('#new_email_cfm').bind('focus', function(){
	$('#new_email_cfm_tip').text('请您再次输入邮箱').css('color', '#666699').show();
	}).bind('blur', function(){
	$('#new_email_cfm_tip').text('');
	if(this.value!=$('#new_login_email').val())
	{
	  $('#new_email_cfm_tip').text('两次输入邮箱不一致，请重新输入').css('color', 'red');
	  this.value='';
	}
	})
  
  $('#btn_submit_modify').click(function(e)
  {
	var login_email_val = $('#login_email').val();
    var login_pwd_val = $('#login_pwd').val();
    var new_login_email_val = $('#new_login_email').val();
	var new_email_cfm_val = $('#new_email_cfm').val();
	var tip_flag = ($('#login_email_tip').css('color') == 'red') || ($('#new_login_email_tip').css('color') == 'red') || ($('#new_email_cfm_tip').css('color') == 'red');
	if(tip_flag || login_email_val == '' || login_pwd_val == '' || new_login_email_val == '' || new_email_cfm_val == '')
    {
      $('.err_notify').remove();
	  $('#c_email_form h3').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
	  e.preventDefault();
	}
    else
    {
      $('#c_email_form').submit();
    }
  });
})
</script>
