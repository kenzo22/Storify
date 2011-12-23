<?php
include $_SERVER['DOCUMENT_ROOT'].'/class/videoUrlParser.php';

$url = $_GET['url'];
$parser = new VideoUrlParser();
$obj = $parser->parse($url);
echo json_encode($obj);
?>
