<?php
$rm_file = $_GET['file'];
$rm_file = "../img/upload/".$rm_file;
if(file_exists($rm_file))
{
  unlink($rm_file);
}
?>
