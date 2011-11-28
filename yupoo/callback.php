<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
session_start();
include_once 'config.php';
include_once 'yupoo.php';

$y = new YupooAPI( YB_AKEY , YB_SKEY);

//$frob = $y->get_frob();
$frob = $_GET['frob'];

$token = $y->get_token($frob);

/*$errormessage = $y->get_error_msg();
$errorcode = $y->get_error_code();

echo "<br/><br/>";
echo "frob:".$frob;
echo "<br/>token:".$token;
echo "vardump<br/>";
var_dump($token);
echo "<br/>erromsg:".$errormessage;
echo "<br/>errorcode:".$errorcode;*/

$_SESSION['yupoo_token'] = $token;

/*$userdata = $y->get_userid_by_name("tokune");
$userid = $userdata[user][id];
$picData  = $y->search_user($userid, 1, $_SESSION['yupoo_token']);*/
//$picData  = $y->search_photo('dota', 1, $_SESSION['yupoo_token']);
//$picData = $y->get_photo_info("2574883-81746747");
//krumo($userdata);
//krumo($picData);

$result=$DB->query("update ".$db_prefix."user set yupoo_token='".$token."' WHERE id='".$_SESSION['uid']."'");
header("location: /accounts/source"); 
exit;
?>
