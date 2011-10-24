<?php
function secureGlobals(&$value, $key)
{
    $value = trim($value);
    $value = str_ireplace("script", "blocked", $value);
    if(!get_magic_quotes_gpc())
        $value = mysql_real_escape_string($value);
}

if(isset($_GET))
    array_walk($_GET, 'secureGlobals');
if(isset($_POST))
    array_walk($_POST, 'secureGlobals');
?>
