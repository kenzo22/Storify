<?php
$html_title = "口立方编辑器 - 创建故事";
include "../global.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include_once( '../include/weibo_functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php print $html_title; ?></title>
	<link type='text/css' href="../css/layout.css" rel='stylesheet' />
	<link type="text/css" href="../css/jquery.ui.theme.css" rel="stylesheet" />
	<link type="text/css" rel="stylesheet" href="../CLEditor/jquery.cleditor.css" />
	<link type="image/ico" rel="shortcut icon" href="../img/favicon.ico" /> 
  </head>
  <body class='editor' onload='javascript:return bindonbeforeunload();'>
  
  <!--[if IE]>     
<style type="text/css">
.yupoo_wrapper{border:1px solid #bbbbbb;}
</style> 
<![endif]-->
 
<?php
session_start();
$debug=1;

if (!empty($_SERVER[HTTP_REFERER])) $url=htmlspecialchars($_SERVER[HTTP_REFERER]); 

if (get_magic_quotes_gpc()) {  //magic_quotes_gpc开了会加"\" 先去掉
	$_GET = stripslashes_array($_GET);
	$_POST = stripslashes_array($_POST);
	$_COOKIE = stripslashes_array($_COOKIE); 
	$GLOBALS = stripslashes_array($GLOBALS);
} 
set_magic_quotes_runtime(0);
if(islogin())
{ 
  $content="<div id='actions' style='display:block; position:absolute; top:4px; right:0;'>
				<span><a id='draftBtn' href='./' >保存草稿</a></span>
				<span><a id='previewBtn' href='./' >预览</a></span>
				<span><a id='publishBtn' class='large blue awesome' href='./' >发布 &raquo;</a></span>
			  </div>";
  $userresult=$DB->fetch_one_array("SELECT id, photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>".$content."</div></div>";
}
else
{
  $content="<div id='actions' style='display:block; position:absolute; top:4px; right:0;'>
				<span><a id='draftBtn' class='disable' href='./' >保存草稿</a></span>
				<span><a id='previewBtn' class='disable' href='./' >预览</a></span>
				<span><a id='publishBtn' class='large blue awesome disable' href='./' >发布 &raquo;</a></span>
			  </div>";
  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
  ".$content."</div></div>";
}

require ('../include/secureGlobals.php');

$extra_class = "";
$hasSina = "sina_disable";
$hasTencent = "tencent_disable";
$hasYupoo = "yupoo_disable";
if(islogin())
{
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
  $content = "<div id='storyContent'>
				<div id='boxes'>";
}
else
{
  $content="<div id='storyContent'>
            <div id='boxes'>
			  <div id='dialog' class='window'>
			    <div class='title_bar'><span><a href='#' class='close'>关闭</a></span><span>登录 koulifang.com</span></div>
			    <form method='post' action='/accounts/login/login.php'>
			    <div class='wrapper'>
				  <div id='login_modal'>
				    <div class='form_div'><b> 邮 箱 &nbsp; </b><span><input type='text' name='email' id='email_login' onclick='this.value=\"\"'/></span></div>
				    <div class='form_div'><b> 密 码 &nbsp; </b> <span><input type='password' name='passwd' id='pwd_login' onclick='this.value=\"\"'/> </span></div>
				    <div class='auto_login'><span> <input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='/accounts/login/forget_form.php'>忘记密码了？</a></span></div>
				    <div>
					  <input type='submit' id='login_modal_btn' value='登录'/>
				    </div>
				  </div>
				  <div class='login_right'>
				    <div>还没有口立方帐号?</div>
					<a class='large green awesome register_awesome' href='/accounts/register/register_form.php'>马上注册 &raquo;</a>
					<div><span>使用新浪微博帐号登录</span></div>
				    <div><a id='connectBtn' href='#'><span class='sina_icon'></span><span class='sina_name'>新浪微博</span></a></div>  
				  </div>
			    </div>
			    </form>
			  </div>";
}

$content .= "
    <div id='weibo_dialog' class='window".$extra_class."'>
	  <div style='background-color:#f3f3f3; padding:5px; margin-bottom:10px;'><span><a href='#' class='close'>关闭</a></span><span id='icon_flag'></span><span id='publish_title' style='color: #336699;'>发表微博</span></div>
	  <div id='pub_wrapper'>
	    <div class='float_r counter_wrapper'><span style='margin-left:28px; color: #B8B7B7;'>还可以输入</span><span class='word_counter'>140</span><span style='color: #B8B7B7;'>字</span></div>
	    <textarea class='publish-tweet' cols='50' rows='3'></textarea>
	    <a class='btn_w_publish large blue awesome'><span id='pub_text'>转发</span></a>
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
		    <li class='weiboLi'><a><span class='source_img' title='新浪微博'></span></a></li>
			<li class='tweiboLi'><a><span class='source_img' title='腾讯微博'></span></a></li>
			<li class='doubanLi'><a><span class='source_img' title='豆瓣社区'></span></a></li>
		    <li class='videoLi'><a><span class='source_img' title='优酷视频'></span></a></li>
			<li class='yupooLi'><a><span class='source_img' title='又拍社区'></span></a></li>
		  </ul>
		  <div id='weiboTabs'>
		    <ul>
			  <li><a id='search_tab' href='#tabs-1'>话题搜索</a></li>
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
			      <input id='keywords' name='keywords' type='text' />
			      <button id='weibo_search_btn' type='submit' value='search'>搜索话题</button>
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
			      <input id='d_keywords' name='d_keywords' type='text' />
			      <button id='douban_search_btn' type='submit' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  <div id='videoTabs'>
		    <form action='#' style='padding-top:15px; padding-bottom:29px;'>
		    <div>
			  <div>优酷视频地址:</div>          
			  <input style='margin-top:13px;' id='videoUrl' name='videoUrl' type='text' />
			  <button style='margin-top:13px;' type='submit' value='嵌入视频' id='embedVideo'>嵌入视频</button>
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
			      <input id='pic_keywords' name='pic_keywords' type='text' />
			      <button id='pic_search_btn' type='submit' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  
		</div>
		<ul id='source_list' class='connectedSortable'>
		  <li class='trends_li'>
		    <a class='trends_wrapper' href='#'>
		      <span id='view_trends'>点击查看本周热门话题</span>
		    </a>
		  </li>
		</ul>    	
	  </div>
	</div>
	</div>
	<div class='right_half'>
	<div id='story_pane'>
	  <div id='story'>";
	  
if(isset($_GET['user_id']) && isset($_GET['post_id']))
{ 
  if(!islogin())
  {
    header("location: /accounts/login/login_form.php"); 
    exit;
  } 
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
		    <p><img id='story_thumbnail' width='88' height='88' src='".$story_pic."' alt='故事封面' /></p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'></a></li>
			  <li><a id='next_img' href='#'></a></li>
			</ul>
		  </div>
		  <span > <input type='text' value='".$story_title."' name='story_title' id='sto_title' /> </span>
		  <div>
		    <textarea id='sto_summary' cols='40' rows='4'>".$story_summary."</textarea>
		  </div>
		  <div>
		    <span><input type='text' value='".$tags."' name='story_tag' id='sto_tag' /></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'><li class='addTextElementAnchor'>
			  <span><a class='add_comment'></a></span></li>";
  
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
        $content .= "<li class='weibo_drop sina' id='w_".$weibo_per_id."'><div class='cross' action='delete' onclick='remove_item(event)'></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被原作者删除</span></div></div></li><li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>";
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
		$content .= "<li class='weibo_drop sina' id='w_".$weibo_per_id."'><div class='cross' action='delete' onclick='remove_item(event)'></div>";

    	if (isset($single_weibo['retweeted_status'])){
            
            $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
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
		  $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
		}
        if (isset($single_weibo['bmiddle_pic']))
		{
		  $content .= "<div class='weibo_img_drop'><img src='".$single_weibo['bmiddle_pic']."' /></div>";
		}     
        $content .= "</div>";
        $content .= "<div class='story_signature'><span class='float_r'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img_drop' src='"
					.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span class='signature_text_drop'><div class='text_wrapper'><span ><a class='weibo_from_drop' href='http://weibo.com/"
					.$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'>".$createTime."</div></span></div></div></li><li class='addTextElementAnchor'><span>
					<a class='add_comment'></a></span></li>";
	  }
		break;}
		
	  case "tweibo":{
	  $tweibo_meta_data = $val['content'];
	  $tweibo_per_id = $tweibo_meta_data['id'];
	  $tweibo_id_array[] = $tweibo_per_id;
	  $content .="<li id='t_".$tweibo_per_id."'></li>";
	  break;
      }
		
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
		 "<li class='douban_drop douban event' id='d_".$douban_save_per_id."'>
			<div class='cross' action='delete' onclick='remove_item(event)'></div>
			<div class='handle'></div>
			<div class='douban_wrapper'>
			  <div class='content_wrapper'>
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
			  </div>
			  <div class='douban_signature'>
				<span class='float_r'>
				  <a href='".$eventInitiator_url."' target='_blank'>
					<img class='profile_img_drop' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' border=0 />
				  </a>
				</span>
				<span class='signature_text_drop'>
				  <div class='text_wrapper'>
					<span >
					  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
					</span>
				  </div>
				  <div class='douban_date_drop'></div>
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
			$time_array = explode("T", $doubanElement['published']['$t']);
			$content .=
			  "<li class='douban_drop douban ".$val['content']['item_type']."' id='d_".$douban_save_per_id."'>
				<div class='cross' action='delete' onclick='remove_item(event)'></div>
				<div class='handle'></div>
				<div class='douban_wrapper'>
				  <div class='content_wrapper'>
				  <div>
					<div class='comment_title_drop' style='font-weight:bold;'>".$doubanElement['title']['$t']."</div>
					<div class='comment_summary_drop'>".$doubanElement['summary']['$t']."<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>[查看评论全文]</a></div>
				  </div>
				  <div class='item_info_drop' style='overflow:auto;'>
					<a href='".$douban_per_url."' target='_blank'><img class='item_img_drop' src='".$itemPic."' style='float:left;' /></a>
					<div class='item_meta_drop' style='margin-left:100px;'>
					  <div>
						<a class='item_title_drop' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a>
					  </div>
					  <div class='item_author_drop'>".$douban_item_author."</div>
					  <div class='item_date_drop'>".$douban_item_date."</div>
					  <div class=item_rating_drop>".$doubanElement['author']['name']['$t']."评分:".$comment_rating."</div>
					  <div class='average_rating_drop'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
					</div>
				  </div>
				  </div>
				  <div class='douban_signature'>
					<span class='float_r'>
					  <a href='".$comment_author_link."' target='_blank'>
						<img class='profile_img_drop' src='".$comment_author_pic."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
					  </a>
					</span>
					<span class='signature_text_drop'>
					  <div class='text_wrapper'>
						<span >
						  <a class='douban_from_drop' href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>".$doubanElement['author']['name']['$t']."</a>
						</span>
					  </div>
					  <div class='douban_date_drop'>".$time_array[0]."</div>
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
			"<li class='douban_drop douban ".$val['content']['item_type']."' id='d_".$douban_save_per_id."'>
			  <div class='cross' action='delete' onclick='remove_item(event)'></div>
			  <div class='handle'></div>
			  <div class='douban_wrapper'>
			    <div class='content_wrapper'>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				</div>
				<div class='douban_signature'></div>
			  </div>
			</li>";
		}
	  }
	  $content .="<li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>";
		break;}
		
	  case "comment":{
	  $comment_text = $val['content'];
	  $content .="<li class='textElement editted'><div class='cross' action='delete' onclick='remove_item(event)'></div><div class='handle'></div><div class='commentBox'>"
	  .$comment_text."</div></li><li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>";		
		break;}	
		
	  case "video":{
	  $video_meta = $val['content'];
	  $video_title = $video_meta['title'];
	  $video_src = $video_meta['src'];
	  $video_url = $video_meta['url'];
	  $content .="<li class='video_drop'><div class='cross' action='delete' onclick='remove_item(event)'></div><div class='handle'></div><div class='youku_wrapper'><div><a class='videoTitle' target='_blank' href='"
	  .$video_url."'>".$video_title."</a></div><div class='embed'><embed src='".$video_src."' quality='high' width='420' height='340' align='middle' allowscriptaccess='always' allowfullscreen='true' mode='transparent' type='application/x-shockwave-flash' wmode='opaque'></embed></div></div></li>
	  <li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>";    	
		break;}
		
	  case "photo":{
	  $photo_meta_data = $val['content'];
	  $photo_title = $photo_meta_data['title'];
	  $photo_author = $photo_meta_data['author'];
	  $photo_per_url = $photo_meta_data['url'];	
	  $photo_id = $photo_meta_data['id'];
	  $author_nic = $photo_meta_data['nic'];
	  $photo_link = "http://www.yupoo.com/photos/".$photo_author."/".$photo_id."/";
	  $content .="<li class='pic_drop'><div class='cross' action='delete' onclick='remove_item(event)'></div><div class='handle'></div><div class='yupoo_wrapper'><a target='_blank' href='".$photo_link."'><img class='pic_img' src='"
				.$photo_per_url."'/></a><div style='line-height:1.5;'><a class='pic_title' target='_blank' href='".$photo_link."'>".$photo_title."</a></div><div style='line-height:1.5;'><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/".$photo_author."/'>".$author_nic."</a></div><div class='yupoo_sign'></div></div></li><li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>"; 
		break;}
		
	  default:
	    break;
	}
  }
  
  if(count($tweibo_id_array) > 0)
  {
	  $tweibo_ids = implode(",", $tweibo_id_array);
	  $tweibo  = $t->t_list($tweibo_ids);
	  $info = $tweibo['data']['info'];
	  $tweiboContent = "";
	  foreach( $info as $item )
	  {
		$time = getdate($item['timestamp']);
		$create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
		$profileImgUrl = $item['head']."/50";
		
		//show nick name
		$item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);

		// show face gif 
		$item['text'] = subs_emotions($item['text'],"tweibo");

		$tweiboContent .="<li id='t_".$item['id']."'>";

		if(isset($item['source'])){
			$tweiboContent .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text'];
			//nick name
			$item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
			
			// emotion substution
			$item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");

			if($item['source']['text'] == null)
				$item['source']['text'] = "此微博已被原作者删除。";
			$tweiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span></div>";
			if(isset($item['source']['image'])){
				foreach($item['source']['image'] as $re_img_url){
					$tweiboContent .="<div class='weibo_retweet_img_drop'><img src='".$re_img_url."/240' /></div>";
				}
			}
		}else{
			$tweiboContent .= "<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text']."</span></div>";
			if(isset($item['image'])){
				foreach($item['image'] as $img_url){
					$tweiboContent .="<div class='weibo_img_drop'><img src='".$img_url."/240' /></div>";
				}
			}
		}
		$tweiboContent .= "<div class='story_signature'><span class='float_r'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' src='"
		.$profileImgUrl."' alt='".$item['nick']."' border=0 /></a></span><span class='signature_text_drop'><div class='text_wrapper'>
		<span ><a class='weibo_from_drop' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'>".$create_time."</div></span></div></div></li><li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>tweibo_sep";
	  }
	  $tweibo_array = explode("tweibo_sep", $tweiboContent);
	  $tweibo_array_len = count($tweibo_array);
	  $tweibo_array_asoc = array();
	  for($i=0; $i<$tweibo_array_len-1; $i++)
	  {
	    $temp_t = $tweibo_array[$i];
		$first_q = strpos($temp_t, "'");
		$second_q = strpos(substr($temp_t, $first_q+1), "'");
		$t_per_id = substr($temp_t, $first_q+1, $second_q);
		$first_t = strpos($temp_t, ">");
		$tweibo_array_asoc[$t_per_id] = substr($temp_t, $first_t+1);
	  }
	  foreach($tweibo_array_asoc as $tkey=>$tval)
	  {
		$content = str_replace("<li id='$tkey'></li>","<li class='weibo_drop tencent' id='$tkey'><div class='cross' action='delete' onclick='remove_item(event)'></div>".$tval, $content);
	  }
  }
  

  $content .="</ul></div></div></div></div></div></div>";
  echo $content;
}
else
{
  $content .= "<div id='story_header'>
		  <div id='story_pic'>
		    <p><img id='story_thumbnail' width='88' height='88' src='' style='background-color:#EFEFEF;' alt='故事封面'/></p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'></a></li>
			  <li><a id='next_img' href='#'></a></li>
			</ul>
		  </div>
		  <span ><input type='text' value='' name='story_title' id='sto_title' /></span>
		  <div>
		    <textarea id='sto_summary' cols='40' rows='4'></textarea>
		  </div>
		  <div>
		    <span ><input type='text' value='' name='story_tag' id='sto_tag' /></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'>
		    <li class='addTextElementAnchor'>
			  <span><a class='add_comment'></a></span>
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
<script type='text/javascript' src='../js/jquery-ui-1.8.16.custom.min.js'></script>
<script type='text/javascript' src='../js/editstory.js'></script>
<script type='text/javascript' src='../js/json2.js'></script>
<script type="text/javascript" src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2417356638" charset="utf-8"></script>
<script type="text/javascript" src="../CLEditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="../js/jquery.embedly.min.js"></script>
</body>
</html>
