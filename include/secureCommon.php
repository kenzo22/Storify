<?php
function secureForDB($value)
{
    if(is_string($value))
        $r_value = trim($value);
    $r_value = str_ireplace("script", "blocked", $r_value);
    
    if(!get_magic_quotes_gpc())
        $r_value = mysql_real_escape_string($r_value);
    return $r_value;
}

function secureQ(&$value, $key='')
{
    $value = mb_convert_encoding($value, 'utf-8','utf-8');
    $value = htmlentities($value, ENT_QUOTES, 'utf-8',false);
    $value = secureForDB($value);
    return $value;
}

function secureNQ(&$value, $key='')
{
    $value = mb_convert_encoding($value, 'utf-8','utf-8');
    $value = htmlentities($value, ENT_NOQUOTES, 'utf-8',false);
    $value = secureForDB($value);
    return $value;
}

?>