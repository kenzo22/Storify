<?php
include "../global.php";
session_start();
include_once( 'config.php' );
include_once( 'txwboauth.php' );

$result=$DB->fetch_one_array("SELECT tweibo_access_token, tweibo_access_token_secret FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
$_SESSION['last_key']['oauth_token']=$result['tweibo_access_token'];
$_SESSION['last_key']['oauth_token_secret']=$result['tweibo_access_token_secret'];
$c = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );

echo "<br /><br /><br />";

/*function binhex($str)
{
$hex = "";
$i = 0;
do {
$hex .= sprintf("%02x", ord($str{$i}));
$i++;
} while ($i < strlen($str));
return $hex;
}
echo getcwd();

$a="撇嘴.gif";
$fa=iconv("UTF-8","GBK",$a);
if (is_readable("d:/xampp/htdocs/storify/img/tweibo/".$fa))
{
echo '<li>'.$a.'</li>';

}

$dirname="../img/tweibo/";
$dir = opendir($dirname);

while(false !== ($file = readdir($dir)))
{
if($file != "." && $file != "..")
if(file_exists($dirname.$file))
echo '<li>'.iconv("GBK",'UTF-8',$file).'</li>';

}*/



//$me=$c->t_show("88065074714247");
//$me=$c->search_t("微博");
//$me = $c->user_timeline('jiapenglei', 0, 0, 20);
//$me = $c->user_other_info('jiapenglei');
$me = $c->search_user('菜虎', 2, 5);
//$me = $c->search_by_tag('创业');
var_dump($me);

/*echo "<pre>";
foreach($me as $key => $value){
    echo $key;
    if(is_array($value)){
        echo "<br />";
        foreach($value as $key1 => $value1){
            echo "  ".$key1;
            echo " = >";
            if (is_array($value1)){
                echo "<br />";
                foreach($value1 as $key2 => $value2){
                    echo "      ";
                    echo $key2;
                    echo " => ";
                    if(is_array($value2)){
                        foreach($value2 as $key3 => $value3){
                            echo "          ";
                            echo $key3. " => ";
                            echo $value3;
                            echo "<br />";
                        }
                    }else{
                    echo $value2;
                    echo "<br />";
                    }
                }
            }else{
                echo $value1;
                echo "<br />";
            }
        }
    }
}
echo "</pre>"*/

?>
