<?php
include "../global.php";
include '../include/secureGlobals.php';

if($_POST['act']!="change_pwd")
{
  $content = "<div class='inner'> 
  <form method='post' id='c_pwd_form' style='margin-top:30px;'>
    <h3> 修改密码 </h3>"; 
  
  if($_GET['next'] == 'error_flag'){
    $content .="<div class=\"err_notify\">密码不正确，请重新输入</div>";
  }
  $content .= "<div class='wrapper'> 
	  <div>
	    <span class='field_name'>你的当前密码:</span> 
		<span ><input type='password' name='old_pwd' id='old_pwd' size='30' maxlength='100'></span>
		<span class='form_tip' id='old_pwd_tip'></span>
	  </div> 
	  <div>
	    <span class='field_name'>你的新密码:</span> 
	    <span ><input type='password' name='new_pwd' id='new_pwd' size='30' maxlength='100'></span>
	    <span class='form_tip' id='new_pwd_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>再输一次:</span> 
	    <span ><input type='password' name='new_pwd_cfm' id='new_pwd_cfm' size='30' maxlength='100'></span>
	    <span class='form_tip' id='new_pwd_cfm_tip'></span>
	  </div>
      <div style='margin:20px 0 0 80px;'>
	    <a id='btn_submit_modify' class='large blue awesome'>确认修改密码 &raquo;</a> 
	    <input type='hidden' name='act' value='change_pwd'>
	  </div>
	</div>
  </form>
  <div style='height:270px;'></div>
  </div>";
  echo $content;
}
else
{
  $old_pwd=sha1(trim($_POST["old_pwd"]));
  $new_pwd=sha1(trim($_POST["new_pwd"]));
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."' AND passwd='".$old_pwd."'" );
  if(!empty($result))
  {
    $update_result=$DB->query("update ".$db_prefix."user set passwd='".$new_pwd."'  WHERE ID='".$_SESSION['uid']."'");
	session_destroy();
	go("/login/login_form.php", "修改密码成功，请重新登录", 2);
  }
  else
  {
    go("/login/change_pwd.php?next=error_flag");
  }  
}

include "../include/footer.htm";	 
?>
<script type="text/javascript">
$(function(){
    $('#old_pwd').bind('blur', function(){
	$('#old_pwd_tip').text('');
	if(this.value=='')
	{
	  $('#old_pwd_tip').text('密码不能为空').css('color', 'red').show();
	}
	});
	
	$('#new_pwd').bind('focus', function(){
	$('#new_pwd_tip').text('字母、数字或符号，最短四个字符，区分大小写').css('color', '#666699').show();
	}).bind('blur', function(){
	$('#new_pwd_tip').text('');
	if(this.value=='')
	{
	  $('#new_pwd_tip').text('密码不能为空').css('color', 'red');
	}
	if(this.value!='' && this.value.length<4)
	{
	  $('#new_pwd_tip').text('密码长度不足四个字符').css('color', 'red');
	}
	});

	$('#new_pwd_cfm').bind('blur', function(){
	$('#new_pwd_cfm_tip').text('');
	if(this.value!=$('#new_pwd').val())
	{
	  $('#new_pwd_cfm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red').show();
	  this.value='';
	}
	});
  
  $('#btn_submit_modify').click(function(e)
  {
	var old_pwd_val = $('#old_pwd').val();
    var new_pwd_val = $('#new_pwd').val();
    var pwd_cfm_val = $('#new_pwd_cfm').val();
	var tip_flag = ($('#old_pwd_tip').css('color') == 'red') || ($('#new_pwd_tip').css('color') == 'red') || ($('#new_pwd_cfm_tip').css('color') == 'red');
	if(tip_flag || old_pwd_val == '' || new_pwd_val == '' || pwd_cfm_val == '' || pwd_cfm_val == '')
    {
      $('.err_notify').remove();
	  $('#c_pwd_form h3').after('<div class=\"err_notify\">表单有误或未填写完整</div>');
	  e.preventDefault();
	}
    else
    {
      $('#c_pwd_form').submit();
    }
  });
})
</script>
