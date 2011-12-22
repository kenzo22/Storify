<?php
include $_SERVER['DOCUMENT_ROOT'].'/class/videoUrlParser.php';

$url = $_POST['url'];
$obj = VideoUrlParser::parse($url);
if($obj)
    echo json_encode($obj);
else
    echo '';
?>
