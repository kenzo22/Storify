<?php
include "../global.php";
?>

<script type="text/javascript">
$(function(){
$('#pwd_login').focus();
$('#email_login').bind('focus', function(){
$('#email_tip').text('请输入你的email地址').css('color', '#666699').show();
}).bind('blur', function(){
$('#email_tip').text('');
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('Email格式不正确').css('color', 'red');
}
if(this.value=='')
{
  $('#email_tip').text('Email不能为空').css('color', 'red');
}
})
$('#pwd_login').bind('focus', function(){
$('#pwd_tip').text('请输入你在StoryBing注册的密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
})
})
</script>

<?php
session_start();

if(isset($_GET['logout']))
{
	unset($_SESSION['username']);
	if(!empty($_COOKIE['email']) || empty($_COOKIE['password']))
	{  
	  setcookie("email", null, time()-3600*24*365);  
	  setcookie("password", null, time()-3600*24*365);  
    } 
	session_destroy(); 
	go($rooturl);
	exit;
}

if($_POST['act']!="login")  //default 登陆界面
{
  $content="<form method='post'>
  <div class='div_center' ><span class='title'> 登录 StoryBing.com </span></div>
  <div class='div_center'>
    <div class='float_l' style='margin-top:20px;' id='login'>
	  <div><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='30'></input><span class='form_tip' id='email_tip'></span></div>
	  <div><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='30'></input><span class='form_tip' id='pwd_tip'></span></div><br />
	  <span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/storify/login/forget_form.php'/>忘记密码了？</a><span>
	  <div><span style='color:red;'>请输入你在豆瓣的注册密码</span></div>
	  <div>
        <input type='submit' value='登录'/><input type='hidden' name='act' value='login'>
	  </div>
	</div>
	<div class='float_r' style='margin-top:40px;'><span>还没有StoryBing帐号，<a href='/storify/register/register_form.php'/>立即注册？</a></span></div>
  </div>
  <div class='div_center' style='height:50px;'></div>
</form>";

  echo $content;
}

  //post 登陆验证
$email=addslashes(htmlspecialchars(trim($_POST['email'])));
$passwd=md5(trim($_POST["passwd"]));
$autologin=$_POST["autologin"];

if($email && $passwd)
{
  $result=$DB->fetch_one_array("SELECT id,username FROM ".$db_prefix."user WHERE email='".$email."' AND passwd='".$passwd."' AND activate='1'" );

  if(!empty($result))
  {
    $_SESSION['uid']=intval($result['id']);
    $_SESSION['username']=$result['username'];
	if(!empty($autologin))
	{
	  setcookie("email", $email, time()+3600*24*365);  
	  setcookie("password", $password, time()+3600*24*365); 
	}
	go($rooturl);
  }
  else
  {
    //go($rooturl."/login/?email=".$email,"邮箱或密码错误..",2);
    //go($rooturl."/login/login_form.php");
  }
}

include "../include/footer.htm";
?>
