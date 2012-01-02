<?php
$rm_file = $_POST['file'];
$rm_file = "../img/upload/".$rm_file;
if(file_exists($rm_file))
{
  unlink($rm_file);
}
?>
