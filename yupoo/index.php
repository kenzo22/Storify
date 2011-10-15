<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( 'config.php' );
include_once( 'yupoo.php' );

$y = new YupooAPI( YB_AKEY , YB_SKEY);

//$picData  = $y->search_photo('哈哈', 1);
$userdata = $y->get_userid_by_name('cyong132');
//$userdata = $y->get_photo_info('2645118-78450942');
//$userdata = $y->get_user_collection('2498644', 1);
//$userdata = $y->get_yupoo_recommend_date(1, '2011-9');
//$userdata = $y->get_yupoo_recommend(1);
var_dump($userdata);


//$picData  = $y->search_user('2645119', 1, $_SESSION['yupoo_token']);
//var_dump($picData);
?>