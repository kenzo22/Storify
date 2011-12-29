<?php
function secureQ(&$value, $key='')
{
    $value = trim($value);
    $value = str_ireplace("script", "blocked", $value);
    if(!get_magic_quotes_gpc())
        $value = mysql_real_escape_string($value);
    $value = mb_convert_encoding($value, 'utf-8','utf-8');
    $value = htmlentities($value, ENT_QUOTES, 'utf-8');
}

function secureNQ(&$value, $key='')
{
    $value = trim($value);
    $value = str_ireplace("script", "blocked", $value);
    if(!get_magic_quotes_gpc())
        $value = mysql_real_escape_string($value);
    $value = mb_convert_encoding($value, 'utf-8','utf-8');
    $value = htmlentities($value, ENT_NOQUOTES, 'utf-8');
}

?>
