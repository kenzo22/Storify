<?php
include "../global.php";
session_start();
include_once( 'config.php' );
include_once( 'doubanapi.php' );

$result=$DB->fetch_one_array("SELECT douban_access_token, douban_access_token_secret FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
$_SESSION['last_dkey']['oauth_token']=$result['douban_access_token'];
$_SESSION['last_dkey']['oauth_token_secret']=$result['douban_access_token_secret'];

$douban_uid;
$accessToken = $_SESSION['last_dkey']['oauth_token'];
$accessTokenSecret = $_SESSION['last_dkey']['oauth_token_secret'];

$c = new DoubanClient( DB_AKEY , DB_SKEY , $accessToken , $accessTokenSecret);
					  
//$msg1 = $c->verify_credentials();

//$msg2 = $c->get_user();

//$msg3 = $c->search_music_reviews(2272292);

//$msg3 = $c->search_event('秋天');
//$msg3 = $c->search_movie_reviews(1424406);
//$msg3 = $c->search_book_reviews(3259440);
$msg3 = $c->get_comment(2023817);

//$msg3 = $c->search_book('幻城');
echo "<br/><br/><br/><br/><br/>";
var_dump($msg3);

?>
<?php include "../include/footer.htm"; ?>

