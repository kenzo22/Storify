<?php
  // include function files for this application
  include "../global.php";
  //权限判断
  //if(!islogin())   
  //go($rooturl."/login","请先登录..",2);
  //$email=addslashes(htmlspecialchars(trim($_POST['email'])));
  if(!empty($_SESSION['username']))
  {
	$username = $_SESSION['username'];
  }
  else
  {
	$username = urldecode($_GET['username']);
  }
  //$username = $_GET['username'];
  if($_POST['act']!="pwd")  //default 登陆界面
  {
    //$email=htmlspecialchars(trim($_GET['email']));
	$content="<div class='div_center_870' ><form method='post'><table align='center' cellpadding='10px' >
	          <tr><td colspan=3 align='center'> <BR><BR><BR> <b>重设密码</b> <BR><BR></td></tr>
	          <tr><td width='80px'><b>你的新口令(英文字母，符号或数字):</b></td> <td><input type='password' name='newpwd' size='15' value=''></input></td> <td></td></tr>
              <tr><td><b>再输一次</b></td> <td><input type='password' name='confirm' size='15'></input> </td> <td> <span style='font-size:12px'> </span></td> </tr>
			  <tr><td colspan=2 align='center'> <input type='submit' value='确认新密码'> <input type='hidden' name='act' value='pwd'></td> <td> <a>忘记密码?</a> &nbsp; &nbsp;<a>注册</a>  </td> </tr>
	          </table></form></div>";

	echo $content;
	//$_POST['email'] = $email;
  }
  else
  {
	//$uid=intval($_SESSION['uid']);
	//$email=addslashes(htmlspecialchars(trim($_POST['email'])));  
	$result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE username='".$username."'");
	if (!$result)
	{
	  go("forget_passwd.php","没有这个注册用户",2);
	}
    if(empty($_POST['newpwd'])||empty($_POST['confirm'])){ 
      go("forget_passwd.php","新密码不能为空",2);
	}
    if($_POST['newpwd']!=$_POST['confirm']){ 
	  go("forget_passwd.php","两次输入密码不一致",2);
	}
	
	$pwd=md5($_POST['newpwd']);
	$DB->query("update ".$db_prefix."user set passwd='".$pwd."'  WHERE username='".$username."'");
    session_destroy();
	go($rooturl."/login/login.php","修改密码成功,请重新登陆",2);
  }
  include "../include/footer.htm";
?>