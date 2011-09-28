<?php
@header('Content-Type:text/html;charset=utf-8');
require_once('config.php');
require_once('yupoo.php');

$y = new YupooAPI( YB_AKEY , YB_SKEY);
$yurl = $y->generate_authurl();

header("Location: ".$yurl);
exit;
?>
