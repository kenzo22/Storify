<?php
include $_SERVER['DOCUMENT_ROOT'].'/class/class.videoUrlParser.php';

$url = trim($_GET['url']);
if(strpos($url,' ') !== false){
    exit;
}
$parser = new VideoUrlParser();
$obj = $parser->parse($url);
echo json_encode($obj);
?>
