<?php
include "../global.php";
include_once( 'config.php' );
//include_once( 'weibooauth.php' );
include_once( 'sinaweibo.php' );

//$token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");

//$token = $DB->fetch_one_array("select * from ".$db_prefix."user where id='1'");
//$_SESSION['last_wkey']['oauth_token']=$token['weibo_access_token'];
//$_SESSION['last_wkey']['oauth_token_secret']=$token['weibo_access_token_secret'];
/*echo "<br /><br /><br /><br /><br />";
echo "token".$_SESSION['last_wkey']['oauth_token']."<br />";
echo "secret".$_SESSION['last_wkey']['oauth_token_secret']."<br />";
echo "after"."<br />";*/
//$hashtoken = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='2'");
//$token = $DB->fetch_one_array("select * from ".$db_prefix."user where id='1'");
//$_SESSION['last_wkey']['oauth_token']=$hashtoken['weibo_access_token'];
//$_SESSION['last_wkey']['oauth_token_secret']=$hashtoken['weibo_access_token_secret'];
//echo "token".$_SESSION['last_wkey']['oauth_token']."<br />";
//echo "secret".$_SESSION['last_wkey']['oauth_token_secret']."<br />";


$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
//$me = $c->user_timeline(1, 20, '风景');
$me  = $c->search_weibo(1, 20, '风景饭第三方但是洛克菲勒但是');

/*$result=$DB->fetch_one_array("SELECT weibo_access_token, weibo_access_token_secret FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
$_SESSION['last_wkey']['oauth_token']=$result['weibo_access_token'];
$_SESSION['last_wkey']['oauth_token_secret']=$result['weibo_access_token_secret'];*/
//$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
//$ms  = $c->friends_timeline(); // done
//$ms  = $c->update("@Briggs 我刚刚引用了你的微博，快来看一看吧：http://t.cn/asvjDv我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博，我刚刚引用了你的微博"); // done

//$me = $c->verify_credentials();
//$me = $c->show_status('3362345674672760');
//$me = $c->shorten_url('http://open.weibo.com/wiki/Short_url/shorten');
//$me = $c->get_emotions();

//echo $me[0]['url_short'];
echo "<br /><br /><br />";
var_dump($me);

/*
$prefix="../img/weibo/";
if (!is_dir($prefix)){
    mkdir($prefix);
}
set_time_limit(0);
foreach ($me as $element){
    $url=$element['url'];
    $name=$element['phrase'];
    preg_match("/\[(.*?)\]/",$name,$matches);
    $array=preg_split("/\./",basename($url));
    $local_file=$prefix.$matches[1].".".$array[1];
    if(file_put_contents($local_file,file_get_contents($url))){
        chmod($local_file,0755);
    }
}
*/


//var_dump($me);


?>
<!--<div class='div_center' >
<form action="weibolist.php" >
<input type="text" name="avatar" style="width:300px" value="头像url" />
&nbsp;<input type="submit" />
</form>
<h2>发送新微博</h2>
<form action="weibolist.php" >
<input type="text" name="text" style="width:300px" />
&nbsp;<input type="submit" />
</form>

<h2>发送图片微博</h2>
<form action="weibolist.php" >
<input type="text" name="text" style="width:300px" value="文字内容" />
<input type="text" name="pic" style="width:300px" value="图片url" />
&nbsp;<input type="submit" />
</form>
</div>-->
<?php
/*if( isset($_REQUEST['text']) || isset($_REQUEST['avatar']) )
{

if( isset($_REQUEST['pic']) )
	$rr = $c ->upload( $_REQUEST['text'] , $_REQUEST['pic'] );
elseif( isset($_REQUEST['avatar']  ) )
	$rr = $c->update_avatar( $_REQUEST['avatar'] );
else
	$rr = $c->update( $_REQUEST['text'] );	

	echo "<p>发送完成</p>" ; 

}

$msg = $c->show_status(3351803345848198);
	if ($msg === false || $msg === null){
		echo "Error occured";
		return false;
	}
	if (isset($msg['error_code']) && isset($msg['error'])){
		echo ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
		return false;
	}
	if (isset($msg['id']) && isset($msg['text'])){
		echo($msg['id'].' : '.$msg['text']);
	}*/
?>


<?php include "../include/footer.htm"; ?>



