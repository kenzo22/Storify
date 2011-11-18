<?php
$html_title = "反馈已提交 - 口立方";
require "../global.php";
require  "../include/header.php";
require "../include/mail_functions.php";
//include "../include/secureGlobals.php";

$name=$_POST['user_name'];
$email=$_POST['email'];
$feedback=nl2br($_POST['feedback']);

$dest = "xinxinzhang22@gmail.com";
$subject = "口立方的用户反馈(".$name.")";

$message =  "用户姓名: ".$name."<br/>".
            "用户邮箱: ".$email."<br/>".
            "用户意见: <br/>".$feedback."<br/>";

while(!sendEmail($dest, $subject, $message))
    ;
?>

<div class='inner'>
  <div style='height:30px;'></div>
  <p>我们已经收到您的反馈，非常感谢！<p>
  <span>&gt;&nbsp;<a href='/'>返回主页</a></span>
  <div style='height:180px;'></div>
</div>
<?php
include "../include/footer.htm";	 
?>
</body>
</html>
