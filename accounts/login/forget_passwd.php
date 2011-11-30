<?php
$html_title = "重设密码 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
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
      go("/accounts/forget_password","该链接已过期失效，请重新到忘记密码页面生成新链接",2);
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
	  go("/accounts/login","修改密码成功,请重新登陆",2);
	  exit;
	}
  }
  include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
<script type='text/javascript' src='/js/forget_pwd.js'></script>
</body>
</html>
