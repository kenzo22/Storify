<?php
  $html_title = "用户注册 - 口立方";
  require $_SERVER['DOCUMENT_ROOT']."/global.php";
  require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
  include $_SERVER['DOCUMENT_ROOT']."/include/mail_functions.php";

  $email=$_POST['email'];
  $username=$_POST['username'];
  $passwd=$_POST['passwd'];
  //$invite_code=$_POST['invite_code'];
  $reset_code_l = 8;
  $reset_code=produce_random_strdig($reset_code_l);
  $current_time = time();

  try   {
	if (!filled_out($_POST)) 
	{
	  throw new Exception('You have not filled the form out correctly - please go back and try again.');
	}

        /*$query="select * from ".$db_prefix."icode where ic_code='".$invite_code."'";
        $result = $DB->query($query);
        if(!$result){
                throw new Exception('执行一下sql语句失败:'.$query);
        }
        if ($DB->num_rows($result) != 1){
                throw new Exception("无效的邀请码.");
        }*/
	
	  $url = 'http://'.$_SERVER['SERVER_NAME'].'/accounts/activation';
	  $url .= '?confirmation='.$reset_code.$current_time;

	 
	  
	  $subject="口立方注册用户激活邮件";
	  $message='<p>欢迎您在口立方注册用户，请点击以下链接以激活您的帐户:<br/><br/>

	(pleae click on the following link to activate your account:)<br/><br/>

	<a href="'.$url.'" target="_blank">'.$url.'</a><br/><br/>

	如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入口立方。<br/><br/>

	感谢您对口立方的支持，希望您在口立方的体验有益和愉快。<br/><br/>

	口立方 http://www.koulifang.com<br/><br/>

	(这是一封自动产生的email，请勿回复。)</p>';
	  $imply_txt = urlencode($username."&".$email);
	  if(sendEmail($email,$subject,$message))
	  {
		$register_time=date("Y-m-d H:i:s");
		$result = $DB->query("insert into ".$db_prefix."user values
                         (null, '".$username."', sha1('".$passwd."'), '".$email."', '', '', '', 0, '', '', 0, '', '', 0, '', '', '', '".$register_time."', 0)");
		$result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."reset WHERE username='".$username."' AND email='".$email."'" );
		
		$query="select id from ".$db_prefix."reset where username='".$username."' AND email='".$email."'";
		$reset_result=$DB->query($query);
		if($DB->num_rows($reset_result) > 0)
		{
		  $DB->query("delete from ".$db_prefix."reset where username='".$username."' AND email='".$email."'");
		}
		
		$result = $DB->query("insert into ".$db_prefix."reset values
                         (null, '".$username."', '".$email."', '".$reset_code."')");
		$content="<div class='inner'>
		 <div class='page_title'>激活帐号</div>
		 <div>
		   <p>请到 ".$email." 查阅来自口立方的邮件, 从邮件激活您的密码。</p>
		   <div id='a_flag'>没有收到确认信?...</div>
		   <input type='hidden' value='".$imply_txt."' id='imply_info' />
		   <ol>
			 <li>1.  检查一下上面的邮箱地址是否正确，错了就<a href='/accounts/register'>重新注册</a>一次吧:)</li>
			 <li>2.  看看是否在邮箱的垃圾箱里</li>
			 <li>3.  稍等几分钟，若仍旧没收到确认信，让口立方<a id='a_resend' href='#'>重发一封激活邮件</a></li>
		   </ol>
		 </div>
		</div>";
		echo $content;
		if (!$result) {
          throw new Exception('Could not register you in database - please try again later.');
        }	
	  }
	
    $_SESSION['valid_user'] = $username;
  }
  catch (Exception $e) {
     echo $e->getMessage();
     exit;
  }
  
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
<script type='text/javascript' src='/js/resend_mail.js'></script>
</body>
</html>
