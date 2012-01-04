<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/secureCommon.php';

if(isset($_GET))
    array_walk($_GET, 'secureQ');
if(isset($_POST))
    array_walk($_POST, 'secureQ');
if(isset($_REQUEST))
    array_walk($_REQUEST, 'secureQ');
?>
