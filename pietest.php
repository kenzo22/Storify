<?php
 
// Make sure SimplePie is included. You may need to change this to match the location of simplepie.inc.
require_once('simplepie/simplepie.inc');
 
// We'll process this feed with all of the default options.
$feed = new SimplePie();
 
// Set which feed to process.
//$feed_url = 'http://feed.keso.cn/PlayinWithIt/';
//$feed_url = 'http://feed.williamlong.info/';
//$feed_url = 'http://feeds.feedburner.com/Mashable/';
//$feed_url = 'http://coolshell.cn/feed';
//$feed_url = 'http://simplepie.org/blog/feed/';
$feed_url = 'http://36kr.com/feed/';
//$feed_url = 'http://cn.engadget.com/rss.xml';
//$feed_url = 'http://blog.sina.com.cn/rss/twocold.xml';
//$feed_url = 'http://rss.sina.com.cn/news/marquee/ddt.xml';
//$feed_url = 'http://rss.sina.com.cn/news/world/focus15.xml';
$feed->set_feed_url($feed_url);

 
// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();
 
// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Sample SimplePie Page</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
 
	<div class="header">
		<h1><a href="<?php echo $feed->get_permalink(); ?>"><?php echo $feed->get_title(); ?></a></h1>
		<p><?php echo $feed->get_description(); ?></p>
	</div>
 
	<?php
	/*
	Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
	*/
	foreach ($feed->get_items() as $item):
	?>
 
		<div class="item">
			<h2><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
			<p>
			<?php 
			  $desc = $item->get_description();
              preg_match('#<p>\s*(.*+)\s*<\/p>#',$desc,$matches);
              if($matches)
              {
			    echo $matches[1];
			  }			  
			?>
			</p>
			<p><small>Posted on <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
		</div>
 
	<?php endforeach; ?>
 
</body>
</html>