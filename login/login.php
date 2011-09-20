<?php
include "../global.php";
//session_start();
?>

<script type="text/javascript">
WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
{
  var cfg = {
              //key: '314237338',
			  key: '2417356638',
			  xdpath: 'http://story.com/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
});

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
$('#pwd_tip').text('请输入你在口立方注册的密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
})

$('#loginbtn').click(function(e)
{
  var email_val = $('#email_login').val();
  var pwd_val = $('#pwd_login').val();
  if(email_val == '' || pwd_val == '')
  {
    e.preventDefault();
  }
})
});
</script>

<?php
if(isset($_GET['logout']))
{
	unset($_SESSION['username']);
	if(!empty($_COOKIE['email']) || empty($_COOKIE['password']))
	{  
	  setcookie("email", null, time()-3600*24*365);  
	  setcookie("password", null, time()-3600*24*365);  
    } 
	echo "<script language='javascript' >
			window.onload = function()
			{
			  WB.connect.logout(function() 
			  {
				self.location = '/index.php';
			  });
			}
			</script>";
	session_destroy(); 
	go("/index.php");
	exit;
}

  //post 登陆验证
$email=addslashes(htmlspecialchars(trim($_POST['email'])));
$passwd=md5(trim($_POST["passwd"]));
$autologin=$_POST["autologin"];

if($email && $passwd)
{
  $result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE email='".$email."' AND passwd='".$passwd."' AND activate='1'" );

  if(!empty($result))
  {
    $_SESSION['uid']=intval($result['id']);
    $_SESSION['username']=$result['username'];
	if(!empty($autologin))
	{
	  setcookie("email", $email, time()+3600*24*365);  
	  setcookie("password", $password, time()+3600*24*365); 
	}
	$_SESSION['weibo_uid']=intval($result['weibo_user_id']);
	if(0 == $_SESSION['weibo_uid'] && '' == $result['tweibo_access_token'])
	{
	  go("/member/source.php");
	}
	else
	{
	  $_SESSION['last_key']['oauth_token']=$result['weibo_access_token'];
	  $_SESSION['last_key']['oauth_token_secret']=$result['weibo_access_token_secret'];
	  $_SESSION['last_tkey']['oauth_token']=$result['tweibo_access_token'];
	  $_SESSION['last_tkey']['oauth_token_secret']=$result['tweibo_access_token_secret'];
	  $_SESSION['last_dkey']['oauth_token']=$result['douban_access_token'];
	  $_SESSION['last_dkey']['oauth_token_secret']=$result['douban_access_token_secret'];
	  $_SESSION['yupoo_token'] = $result['yupoo_token'];
	  
	  if(isset($_GET['next']) && !empty($_GET['next']) && isLocalURL($_GET['next']))
	  {
		header("location: ".$_GET['next']); 
	  }
	  else
	  {
	    $temparray = parse_url($_SERVER['HTTP_REFERER']);
		if($temparray['path'] == '/login/login.php')
		{
		  go("/index.php");
		}
		else
		{
		  go($_SERVER['HTTP_REFERER']);
		}
	  }
	}
	
  }
  else
  {
    go("/login/login.php");
  }
}

if($_POST['act']!="login")  //default 登陆界面
{
  $content ="<form method='post'>
  <div class='inner' style='padding-top:50px;'><span class='title'> 登录 Koulifang.com </span></div>
  <div class='inner'>
    <div class='float_l' style='margin-top:20px;' id='login'>";
  if(!isset($_GET['next']))
  {
    $content .="<div style='margin:0;'><span style='color:red;'>您的Email和密码不符，请再试一次</span></div>";
  }
  $content .="<div><b> 邮 箱 &nbsp; </b><input type='text' name='email' id='email_login' size='30'></input><span class='form_tip' id='email_tip'></span></div>
	  <div><b> 密 码 &nbsp; </b><input type='password' name='passwd' id='pwd_login' size='30'></input><span class='form_tip' id='pwd_tip'></span></div><br />
	  <span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/login/forget_form.php'/>忘记密码了？</a><span>
      <div>
        <input id='loginbtn' type='submit' value='登录'/><input type='hidden' name='act' value='login'>
	  </div>
	</div>
	<div class='float_r' style='margin-top:40px;'><span>还没有口立方帐号，<a href='/register/register_form.php'/>立即注册？</a></span></div>
  </div>
  <div class='inner' style='height:50px;'></div>
</form>";

  echo $content;
}
?>

<?php
include "../include/footer.htm";
?>
