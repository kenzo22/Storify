<?php
  //add new line to test git
  // include function files for this application
  $html_title = "用户注册 - 口立方";
  require "../global.php";
  require  "../include/header.php";
  include "../include/mail_functions.php";
  //require_once('../include/class.phpmailer.php');

  //create short variable names
  $email=$_POST['email'];
  $username=$_POST['username'];
  $passwd=$_POST['passwd'];
  $invite_code=$_POST['invite_code'];
  $reset_code_l = 8;
  $reset_code=produce_random_strdig($reset_code_l);
  $current_time = time();

  // start session which may be needed later
  // start it now because it must go before headers
  session_start();

  try   {
    // check forms filled in
        if (!filled_out($_POST)) {
                throw new Exception('You have not filled the form out correctly - please go back and try again.');
        }

    // attempt to register
    // this function can also throw an exception
    
	//Pay attention! register($email, $passwd, $username);
	$result = $DB->query("select * from ".$db_prefix."user where email='".$email."'");
	
        if(!$result){
	        throw new Exception('Could not execute query.');
	}
        if ($DB->num_rows($result)>0){
                throw new Exception('That email is taken - go back and choose another one.');
        }

        // check the invitation code
        $query="select * from ".$db_prefix."icode where ic_code='".$invite_code."'";
        $result = $DB->query($query);
        if(!$result){
                throw new Exception('执行一下sql语句失败:'.$query);
        }
        if ($DB->num_rows($result) != 1){
                throw new Exception("无效的邀请码.");
        }

    // if ok, put in db
    //$result = $DB->query("insert into ".$db_prefix."user values
                         //(null, '".$username."', sha1('".$password."'), '".$email."', 0)");
    //if (!$result) {
      //throw new Exception('Could not register you in database - please try again later.');
    //}
	
	  $url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/activation.php';
	  //$url .= '?un='.urlencode($username).'&em='.urlencode($email);
	  $url .= '?confirmation='.$reset_code.$current_time;

	  /*$mail = new PHPMailer();
	  $mail->CharSet = "gb2312";
	  $mail->ishtml(true);
	  $body = '<p>欢迎您在StoryBing注册用户，请点击以下链接以激活您的帐户:<br/><br/>

	(pleae click on the following link to activate your account:)<br/><br/>

	<a href="'.$url.'" target="_blank">'.$url.'</a><br/><br/>

	如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入StoryBing。<br/><br/>

	感谢您对StoryBing的支持，希望您在StoryBing的体验有益和愉快。<br/><br/>

	StoryBing http://www.storybing.com<br/><br/>

	(这是一封自动产生的email，请勿回复。)</p>';

	  $mail->IsSMTP(); // telling the class to use SMTP
	  $mail->Host       = "localhost"; // SMTP server
	  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	  //$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	  $mail->Host       = "smtp.qq.com";      // sets GMAIL as the SMTP server
	  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	  //$mail->Username   = "xinxinzhang22@gmail.com";  // GMAIL username
	  //$mail->Password   = "kooky233";            // GMAIL password
	  $mail->Username   = "11473124@qq.com";  // qq username
	  $mail->Password   = "kenzo22";            // qq password

	  $mail->SetFrom('11473124@qq.com', 'StoryBing');

	  $mail->AddReplyTo("kenzo@storybing.com","StoryBing");

	  $mail->Subject    = "StoryBing注册用户激活邮件";

	  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	  $mail->MsgHTML($body);

	  $address = "11473124@qq.com";
	  $mail->AddAddress($address, "kooky_233");

	  if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	  } else {
		//echo "Message sent!";
		$register_time=date("Y-m-d H:i:s");
		$result = $DB->query("insert into ".$db_prefix."user values
                         (null, '".$username."', sha1('".$password."'), '".$email."', 0, '".$register_time."', 0)");
		$content="<div class='div_center' > <span class='title'> 激活帐号 </span></div> 
		<div class='div_center'><span>请到 ".$email." 查阅来自StoryBing的邮件, 从邮件激活你的密码。<span></div>
		<div class='div_center'><a target='_blank' href='http://mail.google.com'><span>登录Gmail邮箱查收激活确认信</span></a> </div>";
		echo $content;
		if (!$result) {
          throw new Exception('Could not register you in database - please try again later.');
        }
	  }*/
	  
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
		$reset = $DB->query("insert into ".$db_prefix."reset values
                         (null, '".$username."', '".$email."', '".$reset_code."')");
		$content="<div class='inner' style='padding-top:50px;'>
		 <h1>激活帐号</h1>
		 <div>
		   <p>请到 ".$email." 查阅来自口立方的邮件, 从邮件激活您的密码。</p>
		   <h2 style='margin-top:160px;' id='a_flag'>没有收到确认信?...</h2>
		   <input type='hidden' value='".$imply_txt."' id='imply_info' />
		   <ol>
			 <li>1.  检查一下上面的邮箱地址是否正确，错了就<a href='/register/register_form.php'>重新注册</a>一次吧:)</li>
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
	

    // register session variable
    $_SESSION['valid_user'] = $username;

    // provide link to members page
    //echo 'Your registration was successful.  Go to the members page to start setting up your bookmarks!';
    //do_html_url('member.php', 'Go to members page');

   // end page
  }
  catch (Exception $e) {
     //do_html_header('Problem:');
     echo $e->getMessage();
     //do_html_footer();
     exit;
  }
  
include "../include/footer.htm";
?>
<script type="text/javascript">
$(function(){
$('#a_resend').click(function(e)
{
  e.preventDefault();
  $('.a_notify').remove();
  var ori_info = $('#imply_info').val();
  var info = decodeURIComponent(ori_info);
  var temp_array = info.split('&');
  var postdata = {uname: temp_array[0], email: temp_array[1]};
  $.post('send_mail.php', postdata,
	function(data, textStatus)
	{					
	  if(data == 1)
	  {
		$('#a_flag').after('<div class=\"a_notify\">邮件已重新发送，请查收！</div>');
	  }
	});
});
});
</script>
