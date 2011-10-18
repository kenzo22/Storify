<?php
include "../editorglobal.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include_once( '../include/weibo_functions.php');
?>
<link type="text/css" href="../css/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="../css/jquery.ui.tabs.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="http://js.wcdn.cn/t3/style/css/common/card.css" />
<link rel="stylesheet" type="text/css" href="../CLEditor/jquery.cleditor.css" />

<?php
if(!islogin())
{
  header("location: /login/login_form.php"); 
  exit;
}
$extra_class = "";
$hasSina = "sina_disable";
$hasTencent = "tencent_disable";
$hasYupoo = "yupoo_disable";
$userresult=$DB->fetch_one_array("SELECT weibo_user_id, tweibo_access_token, yupoo_token FROM ".$db_prefix."user where id='".$_SESSION['uid']."'");
if(!$userresult)
{
  throw new Exception('Could not execute query.');
}
if(!empty($userresult))
{
  if(0 != $userresult['weibo_user_id'])
  {
    $hasSina = "sina_enable";
	$extra_class .=" sina";
  }
  if('' != $userresult['tweibo_access_token'])
  {
    $hasTencent = "tencent_enable";
	$extra_class .=" tencent";
  }
  if('' != $userresult['yupoo_token'])
  {
    $hasYupoo = "yupoo_enable";
  }
}

$content = "
<div id='storyContent' style='margin-bottom:0;'>
  <div id='boxes'>
    <div id='weibo_dialog' class='window".$extra_class."'>
	  <div style='background-color:#f3f3f3; padding:5px; margin-bottom:10px;'><span id='publish_title' style='color: #B8B7B7;'>发表微博</span><span><a href='#' class='close'/>关闭</a></span></div>
	  <div id='pub_wrapper'>
	    <div class='float_r counter_wrapper'><span style='margin-left:28px; color: #B8B7B7;'>还可以输入</span><span class='word_counter'>140</span><span style='color: #B8B7B7;'>字</span></div>
	    <textarea class='publish-tweet'></textarea>
	    <a class='btn_w_publish'><span id='pub_text'>转发</span></a>
	  </div>
	  <div class='pub_imply_sina'><span style='margin-left:6px; margin-right:5px; color:#878787;'>发布到新浪微博需要绑定新浪微博帐号</span><a href='/member/source.php'>现在去绑定</a></div>
	  <div class='pub_imply_tencent'><span style='margin-left:6px; margin-right:5px; color:#878787;'>发布到腾讯微博需要绑定腾讯微博帐号</span><a href='/member/source.php'>现在去绑定</a></div>
  </div>
  <div id='mask'></div>
  </div>
  <div class='inner'>
	<div class='left_half'>
	<div id='source_pane'>
	  <div id='sourcelist_container'>
	    <div id='vtab'>
		  <ul>
		    <li class='weiboLi'><a><img class='source_img' title='新浪微博' src='../img/sina24.png' /></a></li>
			<li class='tweiboLi'><a><img class='source_img' title='腾讯微博' src='../img/tencent24.png' /></a></a></li>
			<li class='doubanLi'><a><img class='source_img' title='豆瓣社区' src='../img/logo_douban.png' /></a></a></li>
		    <li class='videoLi'><a><img class='source_img' title='优酷视频' src='../img/icon-youku.png' /></a></li>
			<li class='yupooLi'><a><img class='source_img' title='又拍社区' src='../img/yupoo-logo.png' /></a></li>
		  </ul>
		  <div id='weiboTabs'>
		    <ul>
			  <li><a id='search_tab' href='#tabs-1'>微博搜索</a></li>
		      <li><a id='my_tab' class='".$hasSina." ".$hasTencent."' href='#tabs-2'>我的微博</a></li>
		      <li><a id='follow_tab' class='".$hasSina." ".$hasTencent."' href='#tabs-3'>我的关注</a></li>
		      <li><a id='user_tab' href='#tabs-4'>用户搜索</a></li>
	        </ul> 
			<div id='tabs-1'> 

	        </div> 
	        <div id='tabs-2'> 

	        </div> 
	        <div id='tabs-3'> 

	        </div> 
	        <div id='tabs-4'> 
		      
	        </div>
			<div id='weibo_search'>
		      <form id='source_controller_form' action='#'>
		        <div class='sep'>         
			      <input id='keywords' name='keywords' type='text'>
			      <button id='weibo_search_btn' type='button' value='search'>搜索微博</button>
                </div>
		      </form>
		    </div>
		  </div>
		  <div id='doubanTabs'>
		    <ul>
			  <li><a id='book_tab' href='#dtabs-1'>图书</a></li>
		      <li><a id='movie_tab' href='#dtabs-2'>电影</a></li>
		      <li><a id='music_tab' href='#dtabs-3'>音乐</a></li>
		      <li><a id='event_tab' href='#dtabs-4'>活动</a></li>
	        </ul> 
			<div id='dtabs-1'> 

	        </div> 
	        <div id='dtabs-2'> 

	        </div> 
	        <div id='dtabs-3'> 

	        </div> 
	        <div id='dtabs-4'> 
		      
	        </div>
			<div id='douban_search'>
		      <form action='#'>
		        <div class='sep'>        
			      <input id='d_keywords' name='d_keywords' type='text'>
			      <button id='douban_search_btn' type='button' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  <div id='videoTabs'>
		    <form action='#' style='padding-top:15px; padding-bottom:35px;'>
		    <div>
			  <label for='videoUrl'>优酷视频地址:</label><br />           
			  <input style='margin-top:13px;' id='videoUrl' name='videoUrl' type='text'>
			  <button style='margin-top:13px;' type='button' value='嵌入视频' id='embedVideo'>嵌入视频</button>
            </div>
		    </form>
		  </div>
		  <div id='picTabs'>
		    <ul>
			  <li><a id='search_tab_pic' href='#pictabs-1'>图片搜索</a></li>
		      <li><a id='user_tab_pic' class='".$hasYupoo."' href='#pictabs-2'>用户搜索</a></li>
			  <li><a id='collect_tab_pic' href='#pictabs-3'>用户收藏</a></li>
			  <li><a id='recom_tab_pic' href='#pictabs-4'>又拍精彩</a></li>
	        </ul> 
			<div id='pictabs-1'> 

	        </div> 
	        <div id='pictabs-2'> 

	        </div> 
			<div id='pictabs-3'> 

	        </div> 
			<div id='pictabs-4'> 

	        </div> 
			<div id='pic_search'>
		      <form action='#'>
		        <div class='sep'>           
			      <input id='pic_keywords' name='pic_keywords' type='text'>
			      <button id='pic_search_btn' type='button' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  
		</div>
		<ul id='source_list' class='connectedSortable'>
		</ul>    	
	  </div>
	</div>
	</div>
	<div class='right_half'>
	<div id='story_pane'>
	  <div id='story'>";
	  
if(isset($_GET['post_id']))
{  
  $c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']  );
  $t = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
  $d = new DoubanClient( DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']  );
  $post_id = $_GET['post_id'];
  echo "<script language=javascript >
  var post_id=$post_id;
  </script>";
  $result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
  if(!$result)
  {
	throw new Exception('Could not execute query.');
  }
  $story_title=$result['post_title'];
  $story_summary=$result['post_summary'];
  $story_pic=$result['post_pic_url'];
  
  $tag_query = "select name from story_tag,story_tag_story where story_tag.id=tag_id and story_id=".$post_id;
  $tag_names = $DB->query($tag_query);
  if($DB->num_rows($tag_names) > 0)
  {
    while($tag_name_row = $DB->fetch_array($tag_names))
	{
      $tags .= $tag_name_row['name']." ";
    }
  }
  
  $story_content=$result['post_content'];
  $story_content_array = json_decode($story_content, true);
  $weibo_id_array = array();
  $tweibo_id_array = array();
	
  $content .="<div id='story_header'>
		  <div id='story_pic'>
		    <p><img id='story_thumbnail' width='88' height='88' src='".$story_pic."' /></p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'><img src='../img/left.png' /></a></li>
			  <li><a id='next_img' href='#'><img src='../img/right.png' /></a></li>
			</ul>
		  </div>
		  <span > <input type='text' value='".$story_title."' name='story_title' id='sto_title'> </span>
		  <div>
		    <textarea id='sto_summary'>".$story_summary."</textarea>
		  </div>
		  <div>
		    <span ><input type='text' value='".$tags."' name='story_tag' id='sto_tag'></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'><li class='addTextElementAnchor'>
			  <span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";
  
  foreach($story_content_array['content'] as $key=>$val)
  {	
	switch($val['type'])
	{
      case "weibo":{
	  $weibo_meta_data = $val['content'];
	  $weibo_per_id = $weibo_meta_data['id'];
	  $single_weibo  = $c->show_status($weibo_per_id );
		
	  if ($single_weibo === false || $single_weibo === null)
	  {
	    echo "Error occured";
	    return false;
	  }
	  if (isset($single_weibo['error_code']) && isset($single_weibo['error']))
	  {
        $content .= "<li class='weibo_drop sina' id='$weibo_per_id'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被原作者删除</span></div>";
        continue;
//		echo ('Error_code: '.$single_weibo['error_code'].';  Error: '.$single_weibo['error'] );
//		return false;
	  }
	  if (isset($single_weibo['id']) && isset($single_weibo['text']))
	  {
        
        // show emotions in text
        $single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");

        $single_weibo['text'] = subs_url($single_weibo['text'],"weibo");

		$createTime = dateFormat($single_weibo['created_at']);
		$content .= "<li class='weibo_drop sina' id='$weibo_per_id'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div>";

    	if (isset($single_weibo['retweeted_status'])){
            
            $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost sina'><img src='/img/retweet.png'/ ><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><img src='/img/reply.png'/ ><span>评论</span></a></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
			// show emotions in text
            $single_weibo['retweeted_status']['text'] = subs_emotions($single_weibo['retweeted_status']['text'],"weibo");

            $single_weibo['retweeted_status']['text'] = subs_url($single_weibo['retweeted_status']['text']);

		    $content .= "//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
            if(isset($single_weibo['retweeted_status']['bmiddle_pic']))
			{
                $content .= "</span><div class='weibo_retweet_img_drop'><img src='".$single_weibo['retweeted_status']['bmiddle_pic']."' /></div>";
            }
			else
			{
			  $content .= "</span>";
			}
        }
		else{
		  $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f sina'><img src='/img/retweet.png'/ ><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><img src='/img/reply.png'/ ><span>评论</span></a></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
		}
        if (isset($single_weibo['bmiddle_pic']))
		{
		  $content .= "<div class='weibo_img_drop'><img src='".$single_weibo['bmiddle_pic']."' /></div>";
		}     
        $content .= "</div>";
        $content .= "<div id='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
					.$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span> <img border='0' style='position:relative; top:2px' src='../img/sina16.png'/><a>"
					.$createTime."</a></span></div></span></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";
	  }
		break;}
		
	  case "tweibo":{
	  $tweibo_meta_data = $val['content'];
	  $tweibo_per_id = $tweibo_meta_data['id'];
	  $tweibo_id_array[] = $tweibo_per_id;
	  $content .="<li class='weibo_drop tencent' id='$tweibo_per_id'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div></li>
	  <li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";
		break;}
		
	  case "douban":{
	  $douban_save_per_id = $val['content']['item_id'];	
	  if($val['content']['item_type'] == 'event')
	  {
	    $doubanElement = $d->get_event($douban_save_per_id);
		$eventImg = getItemPic($doubanElement['link']);
		$eventLink = getItemLink($doubanElement['link']);
	    $eventInitiator_url = getAuthorLink($doubanElement['author']['link']);
	    $eventInitiator_name = $doubanElement['author']['name']['$t'];
		$eventInitiator_pic = getAuthorPic($doubanElement['author']['link']);
	  
	    $content .=
		 "<li class='douban_drop douban event' id='$douban_save_per_id'>
			<div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div>
			<div class='douban_wrapper'>
			  <div class='event_summary_drop'>".$doubanElement['summary'][0]['$t']."</div>
			  <div style='margin-top:10px; overflow:auto;'>
				<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>
				  <img class='item_img_drop' src='".$eventImg."' style='float:left;' />
				</a>
				<div class='item_meta_drop' style='margin-left:220px;'>
				  <div class='event_title_drop'>活动：<a href='".$eventLink."' target='_blank'>".$doubanElement['title']['$t']."</a></div>
				  <div class='event_initiator_drop'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
				  <div class='start_time_drop'>".$doubanElement['gd:when']['startTime']."</div>
				  <div class='end_time_drop'>".$doubanElement['gd:when']['endTime']."</div>
				  <div class='event_city_drop'>".$doubanElement['db:location']['$t']."</div>
				  <div class='event_location_drop'>".$doubanElement['gd:where']['@valueString']."</div>
				</div>
			  </div>
			  <div id='douban_signature'>
				<span style='float:right;'>
				  <a href='".$eventInitiator_url."' target='_blank'>
					<img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' border=0 />
				  </a>
				</span>
				<span class='signature_text_drop' style=' margin-right:5px; float:right;' >
				  <div style='text-align:right; height:16px;'>
					<span >
					  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
					</span>
				  </div>
				  <div class='douban_date_drop'  style='text-align:right; height:16px;'>
					<span>
					  <img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='../img/logo_douban.png'/>
					</span>
				  </div>
				</span> 
			  </div>
			</div>
		  </li>";
	  }
	  else
	  {
	    if($val['content']['item_type'] == 'bookReviews' || $val['content']['item_type'] == 'movieReviews' || $val['content']['item_type'] == 'musicReviews')
		{
			$doubanElement = $d->get_comment($douban_save_per_id);
			$comment_author_link = getAuthorLink($doubanElement['author']['link']);
			$comment_author_pic = getAuthorPic($doubanElement['author']['link']);
			$itemPic = getItemPic($doubanElement['db:subject']['link']);
			$douban_per_url = getItemLink($doubanElement['db:subject']['link']);
			$url_array  = explode("/", $douban_per_url);
			$douban_item_per_id = $url_array[4];
			$douban_item_meta;
			$douban_item_date;
			$douban_item_author;
			if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_meta = $d->get_book($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_meta = $d->get_movie($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_meta = $d->get_music($douban_item_per_id);
			}
			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$author = getAuthors($douban_item_meta['author']);
		
			if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
			}
			$comment_rating = 2*$doubanElement['gd:rating']['@value'];
			$time_array = explode("T", $doubanElement['updated']['$t']);
			$content .=
			  "<li class='douban_drop douban ".$val['content']['item_type']."' id='$douban_save_per_id'>
				<div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div>
				<div class='douban_wrapper'>
				  <div>
					<div class=item_rating_drop>".$doubanElement['author']['name']['$t']."评分:".$comment_rating."</div>
					<div class='comment_title_drop' style='font-weight:bold;'>".$doubanElement['title']['$t']."</div>
					<div class='comment_summary_drop'>".$doubanElement['summary']['$t']."</div>
					<div style='text-align:right;'>
					  <a href='".$doubanElement['link'][1]['@href']."' target='_blank'>查看评论全文</a>
					</div>
				  </div>
				  <div class='item_info_drop' style='overflow:auto;'>
					<a href='".$douban_per_url."' target='_blank'><img class='item_img_drop' src='".$itemPic."' style='float:left;' /></a>
					<div class='item_meta_drop' style='margin-left:100px;'>
					  <div>
						<a class='item_title_drop' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a>
					  </div>
					  <div class='item_author_drop'>".$douban_item_author."</div>
					  <div class='item_date_drop'>".$douban_item_date."</div>
					  <div class='average_rating_drop'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
					</div>
				  </div>
				  <div id='douban_signature'>
					<span style='float:right;'>
					  <a href='".$comment_author_link."' target='_blank'>
						<img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$comment_author_pic."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
					  </a>
					</span>
					<span class='signature_text_drop' style=' margin-right:5px; float:right;' >
					  <div style='text-align:right; height:16px;'>
						<span >
						  <a class='douban_from_drop' href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>".$doubanElement['author']['name']['$t']."</a>
						</span>
					  </div>
					  <div class='douban_date_drop'  style='text-align:right; height:16px;'>
						<span> 
						  <img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='../img/logo_douban.png'/>
						  <a>".$time_array[0]."</a>
						</span>
					  </div>
					</span> 
				  </div>
				</div>
			  </li>";
		}
		else if($val['content']['item_type'] == 'book' || $val['content']['item_type'] == 'movie' || $val['content']['item_type'] == 'music')
		{
		    if($val['content']['item_type'] == 'book')
			{
			  $douban_item_meta = $d->get_book($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_meta = $d->get_movie($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_meta = $d->get_music($douban_save_per_id);
			}

			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$itemPic = getItemPic($douban_item_meta['link']);
			$itemLink = getItemLink($douban_item_meta['link']);
			$author = getAuthors($douban_item_meta['author']);
			
			if($val['content']['item_type'] == 'book')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
			}
			$content .=
			"<li class='douban_drop douban ".$val['content']['item_type']."' id='$douban_save_per_id'>
			  <div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div>
			  <div class='douban_wrapper'>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				<div class='douban_signature' style='text-align:right;'>
				  <img border='0' style='width:16px; height:16px;' src='../img/logo_douban.png'/>
				</div>
			  </div>
			</li>";
		}
	  }
	  $content .="<li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";
		break;}
		
	  case "comment":{
	  $comment_text = $val['content'];
	  $content .="<li class='textElement editted'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='commentBox'>"
	  .$comment_text."</div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";		
		break;}	
		
	  case "video":{
	  $video_url_php = $val['content'];
	  $content .="<li class='video_drop'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div><a class='videoTitle' target='_blank' href='"
	  .$video_url_php."'></a></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>";    	
		break;}
		
	  case "photo":{
	  $photo_meta_data = $val['content'];
	  $photo_title = $photo_meta_data['title'];
	  $photo_author = $photo_meta_data['author'];
	  $photo_per_url = $photo_meta_data['url'];	
	  $photo_id = $photo_meta_data['id'];
	  $author_nic = $photo_meta_data['nic'];
	  $photo_link = "http://www.yupoo.com/photos/".$photo_author."/".$photo_id."/";
	  $content .="<li class='pic_drop'><div class='cross' action='delete'><a><img src='../img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='yupoo_wrapper'><a target='_blank' href='".$photo_link."'><img class='pic_img' src='"
				.$photo_per_url."'/></a><div style='line-height:1.5;'><a class='pic_title' target='_blank' href='".$photo_link."'>".$photo_title."</a></div><div style='line-height:1.5;'><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/".$photo_author."/'>".$author_nic."</a></div><div class='yupoo_sign'></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span></li>"; 
		break;}
		
	  default:
	    break;
	}
  }
  
  if(count($tweibo_id_array) > 0)
  {
    $tweibo_ids = implode(",", $tweibo_id_array);
    echo "<script language='javascript' >
		$(function()
		{			  
		  $.get('../tweibo/tweibooperation.php', {operation: 'list_weibo', weibo_ids: '$tweibo_ids'},
		  function(data, textStatus)
		  {
			if(textStatus == 'success')
			{
			  var count = $(data).find('li').length;
			  for(var j=0; j<count; j++)
			  {
				var li = $('li:eq('+j+')', data);
				var temp_id = li.attr('id');
				$('#'+temp_id).append(li.contents());
			  }
			}
		  });
		});
		</script>";
  }

  $content .="</ul></div></div></div></div></div></div>";
  echo $content;
  echo "<script language='javascript' >
			window.onload = function()
			{			  
			  $('.video_drop').each(function(){
			  var videoUrlJs = $(this).find('.videoTitle').attr('href');
			  append_video_content(videoUrlJs);
			  });
			}
			</script>";
}
else
{
  $content .= "<div id='story_header'>
		  <div id='story_pic'>
		    <p><img id='story_thumbnail' width='88' height='88' src='' style='background-color:#EFEFEF;'/></p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'><img src='../img/left.png' /></a></li>
			  <li><a id='next_img' href='#'><img src='../img/right.png' /></a></li>
			</ul>
		  </div>
		  <span ><input type='text' value='' name='story_title' id='sto_title'></span>
		  <div>
		    <textarea id='sto_summary'></textarea>
		  </div>
		  <div>
		    <span ><input type='text' value='' name='story_tag' id='sto_tag'></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'>
		    <li class='addTextElementAnchor'>
			  <span><a><img class='add_comment' src='../img/editcomment.png' border='0'/></a></span>
		    </li>
		  </ul>
		</div>
	  </div>
	</div>
	</div>
  </div>
</div>";
  echo $content;
}
include "../include/footer.htm";
?>
<script type="text/javascript" src="../CLEditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="../js/jquery.embedly.min.js"></script>
<script type='text/javascript' src='../js/weibo.js'></script>
<script type='text/javascript' src='../js/jquery-ui-1.8.12.custom.min.js'></script>
<script type='text/javascript' src='../js/editstory.js'></script>
