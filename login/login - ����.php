<?php
include "../global.php";
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

if($_POST['act']!="login")  //default ��½����
{
  $email=htmlspecialchars(trim($_GET['email']));
  $content="<div class='div_center_870' ><form method='post'><table align='center' cellpadding='10px' >
	          <tr><td colspan=3 align='center'> <BR><BR><BR> <b>�û���¼</b> <BR><BR></td></tr>
	          <tr><td width='80px'><b>�� ��</b></td> <td><input type='text' name='email' size='25' value='".$email."'></input></td> <td></td></tr>
              <tr><td><b>�� ��</b></td> <td><input type='password' name='password' size='15'></input> </td> <td> <span style='font-size:12px'> </span></td> </tr>
			   <tr><td colspan=2 align='center'> <input type='submit' value='��½'> <input type='hidden' name='act' value='login'></td> <td> <a>��������?</a> &nbsp; &nbsp;<a>ע��</a>  </td> </tr>
			  
	          </table></form></div>";

  echo $content;
}

//post ��½��֤
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
    //go($rooturl."/login/?email=".$email,"������������..",2);
    go($rooturl."/login/login_form.php");
  }
}

include "../include/footer.htm";
?>
