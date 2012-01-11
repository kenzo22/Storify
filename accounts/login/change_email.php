<?php
$html_title = "更改登录邮箱 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
require $_SERVER['DOCUMENT_ROOT']."/include/functions.php";

if(!islogin())
{
  header("location: /accounts/login"); 
  exit;
}

if($_POST['act']!="change_email")
{
  $content = "<div class='inner'> 
  <form method='post' id='c_email_form'>
    <div class='page_title'> 修改登录邮箱 </div>"; 
  
  if($_GET['next'] == 'error_flag'){
    $content .="<div class=\"err_notify\">帐号与登录密码不匹配，请重新输入</div>";
  }
  $content .= "<div class='wrapper'> 
	  <div>
	    <span class='field_name'>你的登录邮箱:</span> 
		<span ><input type='text' name='login_email' id='login_email' size='30' maxlength='100' /></span>
		<span class='form_tip' id='login_email_tip'></span>
	  </div> 
	  <div>
	    <span class='field_name'>你的登录密码:</span> 
	    <span ><input type='password' name='login_pwd' id='login_pwd' size='30' maxlength='100' /></span>
	    <span class='form_tip' id='login_pwd_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>新登录邮箱:</span> 
	    <span ><input type='text' name='new_login_email' id='new_login_email' size='30' maxlength='100' /></span>
	    <span class='form_tip' id='new_login_email_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>再输入一次:</span> 
	    <span ><input type='text' name='new_email_cfm' id='new_email_cfm' size='30' maxlength='100' /></span>
	    <span class='form_tip' id='new_email_cfm_tip'></span>
	  </div>
      <div>
	    <a id='btn_email_modify' class='large blue awesome'>确认修改邮箱 &raquo;</a> 
	    <input type='hidden' name='act' value='change_email' />
	  </div>
	</div>
  </form>
  <div class='footer_spacer'></div>
  </div>";
  echo $content;
}
else
{
  $uid=intval($_SESSION['uid']);
  $login_email=$_POST['login_email'];
    if(!is_email($login_email)){
        go("/accounts/change_email","Email格式不正确，绕过前端验证",5);
    }
  $login_pwd=sha1(trim($_POST["login_pwd"]));
  $new_login_email=$_POST['new_login_email'];
    if(!is_email($new_login_email)){
        go("/accounts/change_email","Email格式不正确，绕过前端验证",5);
    }
  
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$login_email."' AND passwd='".$login_pwd."'" );
  if(!empty($result))
  {
    $update_result=$DB->query("update ".$db_prefix."user set email='".$new_login_email."'  WHERE email='".$login_email."' AND passwd='".$login_pwd."'" );
	$userresult = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user where id='".$uid."'");
	$username = $userresult['username'];
	$DB->query("update ".$db_prefix."reset set email='".$new_login_email."' where username='".$username."'");
	session_destroy();
	go("/accounts/login", "修改邮箱成功，请重新登录", 2);
  }
  else
  {
    header("location:/acounts/change_email/error");
  }  
}

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";	 
?>
<script type='text/javascript' src='/js/general_setting.js'></script>
</body>
</html>
