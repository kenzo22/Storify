<?php
include "../global.php"; 
session_start();
include_once( 'config.php' );
include_once( 'yupoo.php' );
include_once( '../tweibo/demo/class.krumo.php' );

$y = new YupooAPI( YB_AKEY , YB_SKEY);

$frob = $y->get_frob();

$token = $y->get_token($frob);

$_SESSION['yupoo_token'] = $token;

/*$userdata = $y->get_userid_by_name("tokune");
$userid = $userdata[user][id];
$picData  = $y->search_user($userid, 1, $_SESSION['yupoo_token']);*/
//$picData  = $y->search_photo('dota', 1, $_SESSION['yupoo_token']);
//$picData = $y->get_photo_info("2574883-81746747");
//krumo($userdata);
//krumo($picData);

$result=$DB->query("update ".$db_prefix."user set yupoo_token='".$token."' WHERE id='".$_SESSION['uid']."'");
header("location: ../member/source.php"); 
?>
