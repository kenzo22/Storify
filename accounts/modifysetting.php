<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/user_auth_fns.php';
require $_SERVER['DOCUMENT_ROOT'].'/include/functions.php';
require $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
session_start();
$uid=intval($_SESSION['uid']);

if(!islogin()){
    header("location:/");
    exit;
}

$user_name = $_POST['username'];
if(strpos($user_name,' ') !==false){
    exit("用户名不能带空格");
}
$intro = $_POST['userintro'];
$DB->query("update ".$db_prefix."user set username='".$user_name."', intro='".$intro."' where id=".$uid);
$userresult = $DB->fetch_one_array("SELECT email FROM ".$db_prefix."user where id='".$uid."'");
$email = $userresult['email'];
$DB->query("update ".$db_prefix."reset set username='".$user_name."' where email='".$email."'");
echo "<div style='width:68%; text-align:center; background-color: #FFF6EE;' class='update_notify'><span>更新设置成功</span></div>";
?>
