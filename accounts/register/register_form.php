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
<div class='inner'>
<form method='post' action='register_new.php' id='register_form'> 
  <div id='register_title' class='page_title'>用户注册</div>  
  <div id='sign_up'> 
    <div><span class='field_name'>邮 箱</span> <span > <input type='text' name='email' id='email_reg' size='30' maxlength='100' /> </span> <span class='form_tip' id='email_tip'></span></div> 
	<div><span class='field_name'>密 码</span> <span > <input type='password' name='passwd' id='pwd_reg' size='30' maxlength='16' /> </span> <span class='form_tip' id='pwd_tip'></span> </div>
	<div><span class='field_name'>确认密码</span> <span > <input type='password' name='pwd_confirm' id='pwd_confirm' size='30' maxlength='16' /> </span> <span class='form_tip' id='pwd_confirm_tip'></span> </div> 
	<div><span class='field_name'>名 号</span> <span > <input type='text' name='username' id='name_reg' size='30' maxlength='16' /></span> <span class='form_tip' id='name_tip'> </span> </div> 
	<div><input type='checkbox' id='agree_term' name='term' value='term' checked='checked'/><label for='agree_term'>我已经认真阅读并同意口立方的使用协议</label><span id='term_tip'></span></div>
	<div id='btn_submit_signup'>
	  <a class='large blue awesome'>完成注册 &raquo;</a> 
    </div> 
  </div>
</form>
</div> 
<?php
include "../include/footer.htm";	 
?>
<script type='text/javascript' src='../js/register.js'></script>
</body>
</html>
