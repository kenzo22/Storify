<?php
require_once('simplepie/simplepie.inc');

$feed_url=$_GET['url']; 
$feed = new SimplePie();
 
// Set which feed to process.
//$feed_url = 'http://feed.keso.cn/PlayinWithIt/';
//$feed_url = 'http://feed.williamlong.info/';
//$feed_url = 'http://feeds.feedburner.com/Mashable/';
//$feed_url = 'http://coolshell.cn/feed';
//$feed_url = 'http://simplepie.org/blog/feed/';
//$feed_url = 'http://36kr.com/feed/';
//$feed_url = 'http://cn.engadget.com/rss.xml';
//$feed_url = 'http://blog.sina.com.cn/rss/twocold.xml';
//$feed_url = 'http://rss.sina.com.cn/news/marquee/ddt.xml';
//$feed_url = 'http://rss.sina.com.cn/news/world/focus15.xml';
$feed->set_feed_url($feed_url);

// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

$feed_title = $feed->get_title();
$feed_t_link = $feed->get_permalink();
//$feed_description = $feed->get_description();					
//$feed_icon = $feed->get_favicon(); 

//$content="<h1 id='feed_title'><a target='_blank' href='".$feed_t_link."'>".$feed_title."</a></h1><h3 id='feed_des'>".$feed_description."</h3>";
$content="";

foreach ($feed->get_items() as $item)
{
  $link = $item->get_permalink();
  $title = $item->get_title();
  $description = $item->get_description();
  if(preg_match('#<p>\s*(.*?)\s*<\/p>#',$description,$matches))
  {
    $description = $matches[1];
  }
  if($author = $item->get_author())
  {
	$author_name = $author->get_name();
  }
  $date = $item->get_date('j F Y | g:i a');
  $content .="<li class='feed_drag'>
                <div class='feed_wrapper'>
				  <div class='feed_title'>
				    <a class='feed_link' target='_blank' href='".$link."'>".$title."</a>
				  </div>
				  <div class='feed_des'>".$description."</div>
				  <div class='feed_sig'>
				    <div><img src='/img/feed.png' /></div>
				    <div class='feed_author'>".$author_name."</div>
				    <div><a target='_blank' href='".$feed_t_link."'>".$feed_title."</a></div>
				  </div>
				  <div class='feed_date'>".$date."</div>
				</div>  
			  </li>";
}

echo $content;