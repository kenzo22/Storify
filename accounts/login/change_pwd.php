<?php
$html_title = "更改密码 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require  $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';

if(!islogin())
{
  header("location: /accounts/login/login_form.php"); 
  exit;
}

if($_POST['act']!="change_pwd")
{
  $content = "<div class='inner'> 
  <form method='post' id='c_pwd_form'>
    <div class='page_title'> 修改密码 </div>"; 
  
  if($_GET['next'] == 'error_flag'){
    $content .="<div class=\"err_notify\">密码不正确，请重新输入</div>";
  }
  $content .= "<div class='wrapper'> 
	  <div>
	    <span class='field_name'>你的当前密码:</span> 
		<span ><input type='password' name='old_pwd' id='old_pwd' size='30' maxlength='100' /></span>
		<span class='form_tip' id='old_pwd_tip'></span>
	  </div> 
	  <div>
	    <span class='field_name'>你的新密码:</span> 
	    <span ><input type='password' name='new_pwd' id='new_pwd' size='30' maxlength='100' /></span>
	    <span class='form_tip' id='new_pwd_tip'></span>
	  </div>
	  <div>
	    <span class='field_name'>再输一次:</span> 
	    <span ><input type='password' name='new_pwd_cfm' id='new_pwd_cfm' size='30' maxlength='100' /></span>
	    <span class='form_tip' id='new_pwd_cfm_tip'></span>
	  </div>
      <div>
	    <a id='btn_submit_modify' class='large blue awesome'>确认修改密码 &raquo;</a> 
	    <input type='hidden' name='act' value='change_pwd' />
	  </div>
	</div>
  </form>
  <div class='footer_spacer'></div>
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
	go("/accounts/login/login_form.php", "修改密码成功，请重新登录", 2);
  }
  else
  {
        header("location: /accounts/login/change_pwd.php?next=error_flag");
  }  
}

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";	 
?>
<script type='text/javascript' src='/js/change_pwd.js'></script>
</body>
</html>
