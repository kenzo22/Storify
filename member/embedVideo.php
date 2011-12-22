<?php
include $_SERVER['DOCUMENT_ROOT'].'/class/videoUrlParser.php';

$url = $_GET['url'];
$parser = new VideoUrlParser();
//$obj = VideoUrlParser::parse($url);
$obj = $parser->parse($url);
if($obj)
    echo json_encode($obj);
else
    echo '';
?>
