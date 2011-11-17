<?php
$html_title = "用户注册 - 口立方";
require "../global.php";
require  "../include/header.php";
if(islogin())
{
  header("location: /index.php"); 
  exit;
}
?>
<form method='post' action='register_new.php' id='register_form'> 
  <div class='inner' style='padding-top:50px;' id='register_title'><span class='title'>新用户注册</span></div>  
  <div class='inner' id='sign_up'> 
    <div><span class='field_name'>邮 箱</span> <span > <input type='text' name='email' id='email_reg' size='30' maxlength='100'> </span> <span class='form_tip' id='email_tip'></span></div> 
	<div><span class='field_name'>密 码</span> <span > <input type='password' name='passwd' id='pwd_reg' size='30' maxlength='16'> </span> <span class='form_tip' id='pwd_tip'></span> </div>
	<div><span class='field_name'>确认密码</span> <span > <input type='password' name='pwd_confirm' id='pwd_confirm' size='30' maxlength='16'> </span> <span class='form_tip' id='pwd_confirm_tip'></span> </div> 
	<div><span class='field_name'>名 号</span> <span > <input type='text' name='username' id='name_reg' size='30' maxlength='16'></span> <span class='form_tip' id='name_tip'> </span> </div> 
	<div><span class='field_name'>邀请码</span> <span > <input type='text' name='invite_code' id='code_reg' id='signup_invite_code' size='30' maxlength='16'></span> <span class='form_tip' id='code_tip' style='display:inline;'><a href='../about/?faq#invitecode'> 如何获取? </a></span></div> 
	<div style='padding-left:80px;'><input type='checkbox' id='agree_term' name='term' value='term' checked='checked'/><label for='agree_term'>我已经认真阅读并同意口立方的使用协议</label><span id='term_tip'></span></div>
	<div id='btn_submit_signup' style='padding-left:80px;'>
	  <a class='large blue awesome'>完成注册 &raquo;</a> 
    </div> 
	<div style='height:30px;'></div>
  </div>
</form> 
<?php
include "../include/footer.htm";	 
?>
<script type='text/javascript' src='../js/register.js'></script>
</body>
</html>
