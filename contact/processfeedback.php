<?php
include "../include/mail_functions.php";
//include "../include/secureGlobals.php";

$name=$_POST['user_name'];
$email=$_POST['email'];
$feedback=nl2br($_POST['feedback']);

$dest = "crazyscar@163.com";
$subject = "口立方的用户反馈(".$name.")";

$message =  "用户姓名: ".$name."<br/>".
            "用户邮箱: ".$email."<br/>".
            "用户意见: <br/>".$feedback."<br/>";

while(!sendEmail($dest, $subject, $message))
    ;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> 用户反馈已提交 </title>
</head>

<body>
<p>您的反馈已经发送。谢谢！</p>
</body>
</html>
