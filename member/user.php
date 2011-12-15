<?php
$html_title = "口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/weibo_functions.php";
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/config.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/sinaweibo.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/tweibo/config.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/tweibo/txwboauth.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/douban/config.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/douban/doubanapi.php' );
include $_SERVER['DOCUMENT_ROOT']."/member/userrelation.php";
?>

<!--[if IE]>     
<style type="text/css">
.yupoo_wrapper{border:1px solid #bbbbbb;}
</style> 
<![endif]-->

<!--[if lte IE 6]>
<script type='text/javascript' src='../js/pngfix.js'></script>
<script>
  DD_belatedPNG.fix('.png_fix, .weibo_date_drop, .douban_date_drop, .yupoo_sign, .item_action a, .arrow_down, .arrow_up, .act_digg');
</script> 
<![endif]--> 

<?php
$date_t = date("Y-m-d H:i:s");
$login_status = islogin();
$user_id = $_GET['user_id'];
$self_flag = false;
$follow_flag = false;
if($login_status && $user_id == $_SESSION['uid'])
{
  $self_flag = true;
}
else if($login_status && $user_id != $_SESSION['uid'])
{
  $follow_flag = true;
}

if(isset($_GET['user_id']) && isset($_GET['post_id']) && !isset($_GET['action']))
{
	$post_id = $_GET['post_id'];
	
	$c = new WeiboClient(WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']);
	$t = new TWeiboClient(MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']);
	$d = new DoubanClient(DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']);
	$result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
	if(!$result)
	{
	  go("/","您要查看的故事不存在",2);
      exit;
	}
	if(!$self_flag && $result['post_status'] != 'Published')
	{
	  go("/","您要查看的故事不存在",2);
      exit;
	}
	
	//update the page view
	$selResult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."pageview WHERE story_id='".$post_id."' AND domain_name='koulifang.com'" );
	if(!empty($selResult))
	{
	  $viewresult=$DB->query("update ".$db_prefix."pageview set view_count=view_count+1  WHERE story_id='".$post_id."' AND domain_name='koulifang.com'" );
	}
	else
	{
	  $viewresult=$DB->query("insert into ".$db_prefix."pageview values(null, '".$post_id."', 'koulifang.com', '', 1)");
	}
	$score = getPopularScore($post_id);
	$DB->query("update ".$db_prefix."posts set popular_count='".$score."'  WHERE ID='".$post_id."'");
	$story_author = $result['post_author'];
	
	$userresult = $DB->fetch_one_array("SELECT username, intro, photo, weibo_user_id, tweibo_access_token FROM ".$db_prefix."user where id='".$_SESSION['uid']."'");
	if($userresult['photo'] == '')
    {
	  $current_user_pic = '/img/douban_user_dft.jpg';
    }
    else
    {
	  $current_user_pic =$userresult['photo'];
    }
	$has_sina = false;
	$has_tencent = false;
	if($userresult['weibo_user_id'] != 0)
	{
	  $has_sina = true;
	}
	if($userresult['tweibo_access_token'] != '')
	{
	  $has_tencent = true;
	}
	if($story_author != $_SESSION['uid'])
	{
	  $userresult = $DB->fetch_one_array("SELECT username, intro, photo FROM ".$db_prefix."user where id='".$story_author."'");
	}
	$story_embed = $result['embed_name'];
	$story_time = dateFormatTrans($result['post_date'],$date_t);
	$story_title=$result['post_title'];
	$story_summary=nl2br($result['post_summary']);
	$story_pic=$result['post_pic_url'];
	$story_status=$result['post_status'];
	$story_content=$result['post_content'];
	$story_digg_count=$result['post_digg_count'];
	$embed_code = "<script src=\"http://www.koulifang.com/user/".$story_author."/".$story_embed.".js\"></script>";
	//get the profile image of the story author
	$user_profile_img;
    if(substr($userresult['photo'], 0, 4) == 'http')
    {
	  if(substr($userresult['photo'], 11, 4) == 'sina')
	  {
		$pattern = "/(\d+)\/50\/(\d+)/";
		$user_profile_img = preg_replace($pattern,"$1/180/$2",$userresult['photo']);
	  }
	  else
	  {
	    $pattern = "/50$/";
		$user_profile_img = preg_replace($pattern,'100',$userresult['photo']);
	  }
    }
    else
    {
	  if($userresult['photo'] == '')
	  {
		$user_profile_img = '/img/douban_user_dft.jpg';
	  }
	  else
	  {
	    $user_profile_img =$userresult['photo'];
	  }
    }
	
	$temp_array = json_decode($story_content, true);
	$items_perpage = 20;
	$story_content_array = array_slice($temp_array['content'], 0, $items_perpage, true);
	$weibo_id_array = array();
	$tweibo_id_array = array();
	if($login_status)
	{
	  $extra_class = "";
	  if($has_sina)
	  {
	    $extra_class .=" sina";
	  }
	  if($has_tencent)
	  {
	    $extra_class .=" tencent";
	  }
	  $content = "<div id='boxes' class='p_relative'>
					<div id='weibo_dialog' class='window".$extra_class."'>
					  <div class='title_bar'><span><a href='#' class='close'>关闭</a></span><span id='icon_flag'></span><span id='publish_title'>发表微博</span></div>
					  <div id='pub_wrapper'>
					    <div class='float_r counter_wrapper'><span class='gray'>还可以输入</span><span class='word_counter'>140</span><span class='gray'>字</span></div>
					    <textarea class='publish-tweet'></textarea>
					    <a class='btn_w_publish large blue awesome'><span id='pub_text'>转发</span></a>
					  </div>
					  <div class='pub_imply_sina'><span>发布到新浪微博需要绑定新浪微博帐号</span><a href='/accounts/source'>现在去绑定</a></div>
					  <div class='pub_imply_tencent'><span>发布到腾讯微博需要绑定腾讯微博帐号</span><a href='/accounts/source'>现在去绑定</a></div>
				    </div>
				  </div>";
	}
	else
	{
	  $content = "<div id='boxes' class='p_relative'>
				    <div id='weibo_dialog' class='window disable'>
					  <div class='title_bar'><span><a href='#' class='close'>关闭</a></span><span id='icon_flag'></span><span id='publish_title'>发表微博</span></div>
					  <div class='imply_color alert'>对不起，只有本站注册用户能使用该功能</div>
					  <div class='imply_color'>请您<a href='/accounts/login?next'>登录</a>或<a href='/accounts/register'>注册</a></div>
				    </div>
				  </div>";
	}
	
	if(0 == strcmp($story_status, 'Published'))
	{
	  $publish_flag = true;
	}
	else
	{
	  $publish_flag = false;
	}
	if(!$self_flag)
	{
	  $content .= "<div id='story_container'><div class='publish_wrapper'><div id='publish_container'>";
	}
	else
	{
	  if($publish_flag)
	  {
		$content .= "<div id='story_container'>
					  <div class='publish_wrapper'>
					  <div class='published-steps'>
						<div class='tabs'>
						  <button class='post-tab'>
							<div class='icon'></div>
							<h2>发布到您的网站上</h2>
							<span>嵌入故事，轻松简单</span>
						  </button>
						  <button class='notify-tab'>
							<div class='icon'></div>
							<h2>通告</h2>
							<span>喝水不忘挖井人</span>
						  </button>
						  <button class='share-tab'>
							<div class='icon'></div>
							<h2>分享</h2>
							<span>好故事要让更多人看见</span>
						  </button>
						</div>
						<div class='steps'>
						  <div class='post-content'>
						    <h2>轻松嵌入故事到你的网站中~</h2>
							<span>复制嵌入代码:</span>
							<span><input type='text' value='".$embed_code."' class='sto_embed' size='72' /></span>
							<a title='如何嵌入' class='embed_how' href='http://www.koulifang.com/user/3/4' target='_blank'></a>
						  </div>
						  <div class='notify-content'>
						    <h2>饮水思源，告诉作者你引用了他们的内容~</h2>";
		$weiboFlag=false;
		$tweiboFlag=false;
		$w_user_count = 0;
		$t_user_count = 0;
		
		//prepare the weibo author info to be notified
		$w_nic_array = array();
	    $msg = $c->verify_credentials();
	    if (isset($msg['id']))
	    {
		  $weibo_nick = $msg['screen_name'];
	    }
	    foreach($temp_array['content'] as $tempItem)
	    {
		  if(0 == strcmp($tempItem['type'], 'weibo'))
		  {
		    if($weibo_nick != $tempItem['content']['nic'])
		    {
			  $w_nic_array[] = $tempItem['content']['nic'];
		    }
		  }
	    }
	    $w_nic_array = array_unique($w_nic_array);
		$w_nic_array = array_merge($w_nic_array);
	    $w_array_length = count($w_nic_array);
		if($has_sina)
		{
		  if($w_array_length>0)
		  {
		    $weiboFlag=true;
		    $content.="<div class='sina16_icon'></div><div id='weibo_card_area' class='sina_user'>";
		    for($i=0; $i<$w_array_length; $i++)
		    {
		      $w_user_count += utf8_strlen($w_nic_array[$i]);
			  $content.="<div class='notify-user'><input type='checkbox' checked='checked' /><span>@".$w_nic_array[$i]."</span></div>";
		    }
		    $content.="</div>";
			$w_user_count += 2*$w_array_length;
		  }
		}
		else
		{
		  if($w_array_length>0)
		  {
		    $content.="<div class='sina16_icon'></div><div class='sina_user'>
				<span>发布到新浪微博需要绑定新浪微博帐号</span><a href='/accounts/source'>现在去绑定</a>
				</div>";
		  } 
		}
		
		$t_array = array();
	    $tmsg  =  $t->getinfo();
	    if (isset($tmsg['data']['nick']))
	    {
		  $tweibo_nick = $tmsg['data']['nick'];
	    }
	    foreach($temp_array['content'] as $tempItem)
	    {
		  if(0 == strcmp($tempItem['type'], 'tweibo'))
		  {
		    if($tweibo_nick != $tempItem['content']['nic'])
		    {
			  $t_array[$tempItem['content']['name']] = $tempItem['content']['nic'];
		    }
		  }
	    }
	    $t_array = array_unique($t_array);
	    $t_array_length = count($t_array);
		if($has_tencent)
		{
		  if($t_array_length>0)
		  {
		    $tweiboFlag=true;
		    $content.="<div class='tencent16_icon'></div><div class='tencent_user'>";
		    foreach($t_array as $tkey=>$tval)
		    {
		      $t_user_count += utf8_strlen($tkey);
			  $content.="<div class='notify-user'><input type='checkbox' checked='checked' /><span id='".$tkey."'><a href='http://t.qq.com/".$tkey."' target='_blank'>@".$tval."</a></span></div>";
		    }
		    $content.="</div>";
			$t_user_count += 2*$t_array_length;
		  }
		}
		else
		{
		  if($t_array_length>0)
		  {
		    $content.="<div class='tencent16_icon'></div><div class='tencent_user'>
					   <span>广播到腾讯微博需要绑定腾讯微博帐号</span><a href='/accounts/source'>现在去绑定</a>
					</div>";
		  }
		}
		
		if($weiboFlag || $tweiboFlag)
		{
		  $user_count = ($w_user_count > $t_user_count)?$w_user_count:$t_user_count;
		  $weibo_dis = $weiboFlag?"":"disabled='disabled'";
		  $tweibo_dis = $tweiboFlag?"":"disabled='disabled'";
		  $weibo_check = $weiboFlag?"checked='checked'":" ";
		  $tweibo_check = $tweiboFlag?"checked='checked'":" ";
		  $current_page_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		  $url_result = $c->shorten_url($current_page_url);
		  $url_short = $url_result[0]['url_short'];
		  $base_txt = "我刚刚引用了你的微博，快来看一看吧：";
		  $word_remain = ceil(140-($user_count/2+strlen($url_short)/2+strlen($base_txt)/3));
		  $content.="<textarea class='notify-tweet' name='tweet'>".$base_txt.$url_short."</textarea>
		  <div class='tweet_control'><input id='weibo_f' type='checkbox' name='weibo_f'".$weibo_check.$weibo_dis." /><span>发布到新浪微博</span><input id='tweibo_f' type='checkbox' name='tweibo_f'".$tweibo_check.$tweibo_dis." /><span>发布到腾讯微博</span><span id='remain_txt' class='gray'>还可以输入</span><span class='word_counter'>".$word_remain."</span><span class='gray flag'>字</span><a class='tweet_btn large blue awesome'>发布 &raquo;</a></div>";
		}
		if($w_array_length == 0 && $t_array_length == 0)
		{
		  $content.="<div>您没有引用别人的微博内容</div>";
		}
	    $content.="</div>
				  <div class='share-content'>
				    <h2>好东西大家都来分享~</h2>
					<div id='jiathis_style_32x32'>
					  <a class='jiathis_button_qzone'></a><a class='jiathis_button_tsina'></a>
					  <a class='jiathis_button_tqq'></a>
					  <a class='jiathis_button_renren'></a><a class='jiathis_button_kaixin001'></a>
					  <a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a>
					  <a class='jiathis_counter_style'></a>
					</div>
				  </div>
				</div>
				<div class='spacer'></div>
			  </div>";
		$content .= "<div id='publish_container'>
			  <div id='story_action'><span class='float_r'><a id='".$post_id."_delete' class='delete redirect png_fix' title='删除'></a>&nbsp<a class='edit png_fix' href='/user/".$user_id."/".$post_id."/edit' title='编辑'></a></span><span><a class='publish_icon png_fix' title='已发布'></a>已发布</span></div>";
	  }
	  else
	  {
		$content .= "<div id='story_container'>
		               <div class='publish_wrapper'>
					     <h3 id='draft_imply'>发布故事，分享到社交媒体，让大家都来欣赏品评你的作品~</h3>
						 <div id='draft_action'>  
						   <a class='edit png_fix medium green awesome' href='/user/".$user_id."/".$post_id."/edit' title='继续编辑'>继续编辑 &raquo;</a>
						   <a id='".$post_id."_delete' class='delete redirect png_fix medium yellow awesome' title='删了重来'>删了重来 &raquo;</a>
						   <a class='publish medium blue awesome' href='/user/".$user_id."/".$post_id."/publish' title='发布故事'>发布故事 &raquo;</a>
					     </div>
						 <div id='publish_container'>
			               <div id='story_action'>
						     <span><a class='draft_icon png_fix' title='草稿'></a>草稿</span>
						   </div>";
	  }	
	}

    // get tags for this story
    $tag_query = "select tag_id,name from story_tag,story_tag_story where story_tag.id=tag_id and story_id=".$post_id;
    $tag_names = $DB->query($tag_query);
    if($DB->num_rows($tag_names) > 0){
        while($tag_name_row = $DB->fetch_array($tag_names)){
            $tags .= "<a class='tag_item' href='/topic/".$tag_name_row['tag_id']."'>".$tag_name_row['name']."</a>";
        }
    }

	$story_author_name = $userresult['username'];
	$content .="<div id='story_header'>";
	
	if($story_pic != '')
	{
	  $content .= "<div id='story_img'><img src='".$story_pic."' alt=''/></div>";
	}		  
	$content .="<div id='story_meta'>
				  <div class='story_title'>".$story_title."</div>
				  <div class='story_author'>by<a href='http://www.koulifang.com/user/".$user_id."'>".$story_author_name."</a>, ".$story_time."</div>
				  <div class='story_sum'>".$story_summary."</div>";
	if($tags!='')
	{
	  $content .="<div class='story_tag'>标签:".$tags."</div>";
	}
	$content .="</div>";
	if($publish_flag)
	{
	  $content .= "<div class='tool_wrapper'>
					  <div class='story_share'>
						<div id='ckepop'>
							<span class='jiathis_txt'>分享到：</span>
							<a class='jiathis_button_qzone'></a>
							<a class='jiathis_button_tsina'></a>
							<a class='jiathis_button_tqq'></a>
							<a class='jiathis_button_renren'></a>
							<a class='jiathis_button_kaixin001'></a>
							<a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a>
							<a class='jiathis_counter_style'></a>
						</div>
						<div id='story_embed'>
						  <a href='#' id='embed_a'>嵌入故事<span class='arrow_down'></span><span class='arrow_up'></span></a>
					    </div>
					  </div>
					  <div id='embed_bar'>
					    <span>复制嵌入代码:</span>
						<span><input type='text' class='sto_embed' value='".$embed_code."' size='71' /></span>
						<a title='如何嵌入' class='embed_how' href='http://www.koulifang.com/user/3/4' target='_blank'></a>
					  </div>
				    </div>";
	}
    else
    {
	  $content .= "<div class='tool_wrapper'></div>";
	}	
	$content .=	"</div><ul id='weibo_ul'>";
	
	foreach($story_content_array as $key=>$val)
	{
	  switch($val['type'])
	  {
	    case "weibo":{
		$weibo_meta_data = $val['content'];
		$weibo_per_id = $weibo_meta_data['id'];
		$single_weibo  = $c->show_status($weibo_per_id );
		
		if ($single_weibo === false || $single_weibo === null){
		echo "<br/><br/><br/><br/><br/>Error occured";
		//return false;
		}
		if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
            // skip deleted weibo
            $content .="<li class='weibo_drop sina' id='w_".$weibo_per_id."'><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被原作者删除</span></div></div></li>";
            continue;
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
            
            // show emotions in text
            $single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");

            $single_weibo['text'] = subs_url($single_weibo['text'],'weibo');

			$createTime = dateFormatTrans(dateFormat($single_weibo['created_at']),$date_t);
			$content .="<li class='weibo_drop sina' id='w_".$weibo_per_id."'>";
    		if (isset($single_weibo['retweeted_status'])){
                
                $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div>
				<div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
				// show emotions in text
                $single_weibo['retweeted_status']['text']=subs_emotions($single_weibo['retweeted_status']['text'],"weibo");

                $single_weibo['retweeted_status']['text']=subs_url($single_weibo['retweeted_status']['text']);

                $content .="//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
                if(isset($single_weibo['retweeted_status']['bmiddle_pic'])){
                    $content .= "</span><div class='weibo_retweet_img_drop'><img src='".$single_weibo['retweeted_status']['bmiddle_pic']."' width='280px;' alt='微博配图'/></div>";
                }
				else
				{
				  $content .= "</span>";
				}
            }
			else{
			  $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text']."</span>";
			}
            if (isset($single_weibo['bmiddle_pic']))
			{
			  $content .= "<div class='weibo_img_drop'><img src='".$single_weibo['bmiddle_pic']."' width='280px;' alt='微博配图'/></div>";
			}
            $content .= "</div><div class='story_signature'><span class='float_r'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img_drop' src='"
			.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' /></a></span><div class='signature_text'><div class='text_wrapper'>
			<span><a class='weibo_from_drop' href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'>".$createTime."</div></div> </div></div></li>";
		}
		break;}
		 
		case "tweibo":{
		$tweibo_meta_data = $val['content'];
		$tweibo_per_id = $tweibo_meta_data['id'];
		$tweibo_id_array[] = $tweibo_per_id;
		$content .="<li id='t_".$tweibo_per_id."'></li>"; 
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
		 "<li class='douban_drop douban' id='d_".$douban_save_per_id."'>
		    <div class='douban_wrapper'>
			  <div class='content_wrapper'>
			    <div class='event_summary'>".$doubanElement['summary'][0]['$t']."</div>
			    <div class='event_wrapper'>
			      <a href='".$doubanElement['link'][1]['@href']."' target='_blank'>
				    <img class='item_img float_l' src='".$eventImg."' alt='".$doubanElement['title']['$t']."' />
				  </a>
				  <div class='item_meta'>
				    <div class='event_title'>活动：<a href='".$eventLink."' target='_blank'>".$doubanElement['title']['$t']."</a></div>
				    <div class='event_initiator'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
				    <div class='start_time'>".$doubanElement['gd:when']['startTime']."</div>
				    <div class='end_time'>".$doubanElement['gd:when']['endTime']."</div>
				    <div class='event_city'>".$doubanElement['db:location']['$t']."</div>
				    <div class='event_location'>".$doubanElement['gd:where']['@valueString']."</div>
				  </div>
			    </div>
			  </div>
			  <div class='douban_signature'>
			    <span class='float_r'>
				  <a href='".$eventInitiator_url."' target='_blank'>
				    <img class='profile_img_drop' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' />
				  </a>
				</span>
				<div class='signature_text'>
				  <div class='text_wrapper'>
				    <span >
					  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
					</span>
				  </div>
				  <div class='douban_date_drop'></div>
				</div> 
			  </div>
			</div>
	      </li>";
		}
		else
		{
		  if($val['content']['item_type'] == 'bookReviews' || $val['content']['item_type'] == 'movieReviews' || $val['content']['item_type'] == 'musicReviews')
		  {
		    $douban_item_meta;
		    $douban_item_date;
		    $douban_item_author;
		    $doubanElement = $d->get_comment($douban_save_per_id);
			
			$comment_author_name = $doubanElement['author']['name']['$t'];
			$comment_author_link = getAuthorLink($doubanElement['author']['link']);
			$comment_author_pic = getAuthorPic($doubanElement['author']['link']);
			$itemPic = getItemPic($doubanElement['db:subject']['link']);
			
		    $douban_per_url = getItemLink($doubanElement['db:subject']['link']);
		    $url_array  = explode("/", $douban_per_url);
		    $douban_item_per_id = $url_array[4];
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
			"<li class='douban_drop douban' id='d_".$douban_save_per_id."'>
			  <div class='douban_wrapper'>
				<div class='content_wrapper'>
				<div>
				  <div class='comment_title'>".$doubanElement['title']['$t']."</div>
				  <div class='comment_summary'>".$doubanElement['summary']['$t']."<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>[查看评论全文]</a></div>
				</div>
				<div class='item_info'>
				  <a href='".$douban_per_url."' target='_blank'><img class='item_img' src='".$itemPic."' /></a>
				  <div class='item_meta'>
					<div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class=item_rating>".$comment_author_name."评分:".$comment_rating."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				</div>
				<div class='douban_signature'>
				  <span class='float_r'>
					<a href='".$comment_author_link."' target='_blank'>
					  <img class='profile_img_drop' src='".$comment_author_pic."' alt='".$comment_author_name."' />
					</a>
				  </span>
				  <div class='signature_text'>
					<div class='text_wrapper'>
					  <span >
						<a class='douban_from_drop' href='".$comment_author_link."' target='_blank'>".$comment_author_name."</a>
					  </span>
					</div>
					<div class='douban_date_drop'>".$time_array[0]."</div>
				  </div> 
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
			"<li class='douban_drop douban' id='d_".$douban_save_per_id."'>
			  <div class='douban_wrapper'>
			    <div class='content_wrapper'>
				<div class='item_info'>
				  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' /></a>
				  <div class='item_meta'>
					<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				</div>
				<div class='douban_sig_logo'></div>
			  </div>
			</li>";
		  }
		}
		break;}
		 
		case "comment":{
		$comment_text = $val['content'];
		$content .="<li class='textElement'><div class='commentBox'>".$comment_text."</div></li>";	
		break;}
		 
		case "video":{
		$video_meta = $val['content'];
		$video_title = $video_meta['title'];
		$video_src = $video_meta['src'];
		$video_url = $video_meta['url'];
		$content .="<li class='video_element'><div><a class='videoTitle' target='_blank' href='".$video_url."'>".$video_title."</a></div><div class='embed'>
		<embed src='".$video_src."' quality='high' width='420' height='340' align='middle' allowscriptaccess='always' allowfullscreen='true' mode='transparent' type='application/x-shockwave-flash' wmode='opaque'></embed></div></li>";
		break;}
		 
		case "photo":{
		$photo_meta_data = $val['content'];
		$photo_title = $photo_meta_data['title'];
		$photo_author = $photo_meta_data['author'];
		$photo_per_url = $photo_meta_data['url'];
		$photo_id = $photo_meta_data['id'];
		$author_nic = $photo_meta_data['nic'];
		$photo_link = "http://www.yupoo.com/photos/".$photo_author."/".$photo_id;
		$content .="<li class='photo_element'><div class='yupoo_wrapper'><a target='_blank' href='".$photo_link."'><img src='".$photo_per_url."' alt='".$photo_title."' /></a><div><a class='pic_title' target='_blank' href='".$photo_link."'>".$photo_title."</a></div><div><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/".$photo_author."/'>".$author_nic."</a></div><div class='yupoo_sign'></div></div></li>";	 
		break;
		}
		 
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
	  if($info != null){
	  foreach( $info as $item )
	  {
		$time = getdate($item['timestamp']);
		$create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
		$create_time = dateFormatTrans($create_time, $date_t);
		$profileImgUrl = $item['head']."/50";

		$tweiboContent .="<li id='t_".$item['id']."'>";
		
		$item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);
		$item['text'] = subs_emotions($item['text'],"tweibo");

		if(isset($item['source'])){
			$tweiboContent .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
			<div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text'];
			$item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
			$item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");

			if($item['source']['text'] == null)
				$item['source']['text'] = "此微博已被原作者删除。";
			$tweiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span>";
			if(isset($item['source']['image'])){
				foreach($item['source']['image'] as $re_img_url){
					$tweiboContent .="<div class='weibo_retweet_img_drop'><img src='".$re_img_url."/240' /></div>";
				}
			}
		}else{
			$tweiboContent .= "<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
			<div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text']."</span>";
			if(isset($item['image'])){
				foreach($item['image'] as $img_url){
					$tweiboContent .="<div class='weibo_img_drop'><img src='".$img_url."/240' /></div>";
				}
			}
		}
		$tweiboContent .= "</div><div class='story_signature'><span class='float_r'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' src='"
		.$profileImgUrl."' alt='".$item['nick']."' /></a></span><div class='signature_text'><div class='text_wrapper'>
		<span ><a class='weibo_from_drop' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'>".$create_time."</div></div></div></div></li>tweibo_sep";
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
		$second_t = strpos($temp_t, "</li>");
		$tweibo_array_asoc[$t_per_id] = substr($temp_t, $first_t+1, $second_t-$first_t-1);
	  }
	  foreach($tweibo_array_asoc as $tkey=>$tval)
	  {
	    $content = str_replace("<li id='$tkey'>","<li class='weibo_drop tencent' id='$tkey'>".$tval, $content);
	  }
	  }
	}
	
	if(count($temp_array['content']) > $items_perpage)
	{
	  $content .="<li id='more'><a id='".$items_perpage."_post_".$post_id."' class='load_more' href='#'>更多</a></li>";
	}
	$content .="</ul>";
	
	$query="select COUNT(*) as num from ".$db_prefix."comments where comment_post_id =".$post_id;
	$reply_result = mysql_fetch_array(mysql_query($query));
	$reply_count = $reply_result[num];
	
	$content .="<div class='kou_signature'><span>Powered by</span><a title='口立方' name='poweredby' target='_blank' href='http://koulifang.com'></a></div></div>
	<div id='reply_container'>  
	  <div id='count_wrapper'>
		<span id='digg_count_".$post_id."' class='digg_counter' title='累计赞".$story_digg_count."次'>".$story_digg_count."</span>
		<a id='act_digg_".$post_id."' class='act_digg' title='赞一个'></a>
		<span>评论 (".$reply_count.") </span>
	  </div>
	  <div id='comment_container'>";
	if($login_status)
	{
	  $content.="<img src='".$current_user_pic."' alt='' />
				 <div id='input_wrapper'>
				   <textarea rows='1' cols='20' value='' type='text' id='reply_input'></textarea>
				   <a class='large blue awesome post_comment' id='comment_".$post_id."_".$_SESSION['uid']."'>发表评论 &raquo;</a>
				 </div>";
	}
	else
	{
	  $content.="<div><span>发表评论</span><a id='login_require' href='/accounts/login?next=".urlencode($_SERVER['REQUEST_URI'])."'>请登录</a></div>"; 
	}
	$content .="<div class='clear'></div>
        <ul id='comment_list'>";
		
	$sql="select * from ".$db_prefix."comments where comment_post_id=".$post_id." order by comment_id desc limit 0, 10";
	$comment_result = mysql_query($sql);
	
	if($self_flag || !$login_status)
	{
	  if($self_flag)
	  {
	    $comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a> | <a href='#' class='del_comment'>删除</a></span>";
	  }
	  else
	  {
	    $comment_action = '';
	  }
	  while ($item = mysql_fetch_array($comment_result))
	  {
	    $comment_id = $item['comment_id'];
	    $pic_url = $item['comment_author_pic'];
	    $comment_author = $item['comment_author'];
	    $comment_author_id = $item['user_id'];
		$comment_time = dateFormatTrans($item['comment_date'],$date_t);
	    $comment_content = nl2br($item['comment_content']);
	    $content.="<li id='comment_".$comment_id."'>
			   <a href='/user/".$comment_author_id."' target='_blank'><img alt='' src='".$pic_url."' /></a>
			   <div class='comment_wrapper'>
			     <div class='comment_author'><a href='/user/".$comment_author_id."' target='_blank'>".$comment_author."</a></div>
				 <div>".$comment_content."</div>
				 <div class='comment_action'>".$comment_action."<span>".$comment_time."</span></div>
			   </div>
			 </li>";
	  }
	}
	else
	{
	  while ($item = mysql_fetch_array($comment_result))
	  {
	    $comment_id = $item['comment_id'];
	    $pic_url = $item['comment_author_pic'];
	    $comment_author = $item['comment_author'];
	    $comment_author_id = $item['user_id'];
		if(0 == strcmp($comment_author_id, $_SESSION['uid']))
		{
		  $comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a> | <a href='#' class='del_comment'>删除</a></span>";
		}
		else
		{
		  $comment_action = "<span class='float_r'><a href='#' class='reply_comment'>回复</a></span>";
		}
	    $comment_time = dateFormatTrans($item['comment_date'],$date_t);
	    $comment_content = nl2br($item['comment_content']);
	    $content.="<li id='comment_".$comment_id."'>
			   <a href='/user/".$comment_author_id."' target='_blank'><img alt='' src='".$pic_url."' /></a>
			   <div class='comment_wrapper'>
			     <div class='comment_author'><a href='/user/".$comment_author_id."' target='_blank'>".$comment_author."</a></div>
				 <div>".$comment_content."</div>
				 <div class='comment_action'>".$comment_action."<span>".$comment_time."</span></div>
			   </div>
			 </li>";
	  }
	}
	
    if($reply_count > 10)
    {
	  $content .="<li><a id='more_comments_".$post_id."_".$comment_id."' class='load_more'>更多评论</a></li>";
	}	
	$content .="</ul></div>	
	</div>
	<div class='spacer'></div>
	</div>
	<div id='userinfo_container'>
	  <div class='user_profiles'>
	    <div class='user_box'>
		  <div class='user_info'>
		    <div class='avatar'><a href='/user/".$story_author."'><img width='80px' height='80px' src='".$user_profile_img."' alt='".$userresult['username']."' /></a></div>
			<div class='wrapper'>
			  <div class='user_name'><a href='/user/".$story_author."'><span>".$userresult['username']."</span></a></div>";
		  
	if($follow_flag)
	{
	  $login_user_id = $_SESSION['uid'];
	  
	  $query="select * from ".$db_prefix."follow where user_id=".$_SESSION[uid]." and follow_id=".$story_author;
      $relationresult=$DB->query($query);
      $num=$DB->num_rows($relationresult);
	  if($num > 0)
	  {
	    $content .="<a id='".$login_user_id."_sep_".$story_author."_flag' class='large green awesome follow_btn' href='#'>已关注</a><a id='".$login_user_id."_sep_".$story_author."' class='large green awesome follow_btn' style='display:none;' href='#'>关注</a>";
	  }
	  else
	  {
	    $content .="<a id='".$login_user_id."_sep_".$story_author."' class='large green awesome follow_btn' href='#'>关注</a><a id='".$login_user_id."_sep_".$story_author."_flag' href='#' class='large green awesome follow_btn' style='display:none;'>已关注</a>";
	  }
	  
	}
    // get the following and follower info
    $following_list = getFollowing($story_author);
    $follower_list=getFollower($story_author);

	$content .="</div></div><p class='user-bio'>".nl2br($userresult['intro'])."</p>
				  <div class='usersfollowers'>
					<div><span class='side_title'>粉丝</span><span class='count'>".sizeof($follower_list)."</span></div>
					  <ul class='follower_list'>";
    $usr_img;
	foreach($follower_list as $fower){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fower;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
		if($usr_img == '')
		{
		  $usr_img = '/img/douban_user_dft.jpg';
		}
        $content .="<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/user/".$item['id']."'><img title='".$item['username']."' src='".$usr_img."' alt='".$item['username']."' /></a></li>";
    }
    $content .= "</ul>
                </div>
		  <div class='clear'></div>
		  <div class='usersfollowing'>
		    <div><span class='side_title'>关注</span><span class='count'>".sizeof($following_list)."</span></div>
			<ul class='following_list'>";
    foreach($following_list as $fowing){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fowing;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
		if($usr_img == '')
		{
		  $usr_img = '/img/douban_user_dft.jpg';
		}
        $content .="<li id='following_id_".$item['id']."'><a class='follow_mini_icon' href='/user/".$item['id']."'><img title='".$item['username']."' src='".$usr_img."' alt='".$item['username']."' /></a></li>";
    }
	$total_count = 0;
	$count_query = "select domain_name, refer_url, view_count from ".$db_prefix."pageview where story_id=".$post_id;
	$countResult = $DB->query($count_query);
	if($DB->num_rows($countResult) > 0){
	    $pageview_asoc = array();
        while($count_result_row = $DB->fetch_array($countResult)){
			$pageview_asoc[$count_result_row['domain_name']] = $count_result_row['view_count']."-".$count_result_row['refer_url'];
			$total_count += $count_result_row['view_count'];
        }
		foreach($pageview_asoc as $pkey=>$pval)
	    {
	      $pval_array = explode("-", $pval);
		  $view_content .= "<div class='view_count'><a target='_blank' href='".$pval_array[1]."'>".$pkey."</a><span>浏览了<strong>".$pval_array[0]."</strong>次</span></div>";
	    }
    }	
	
    $content .= "
			</ul>
		  </div>
		</div>
	  </div>
	  <div class='story_stats'>
	    <div class='user_info_title'>总浏览次数: <span>".$total_count."</span></div>".$view_content."
	  </div>";
		  
	$i_query = "select * from ".$db_prefix."posts where post_status = 'Published' and post_author='".$user_id."' and ID!='".$post_id."' order by post_digg_count desc limit 3";
	$more_result=$DB->query($i_query);
	if($DB->num_rows($more_result) > 0)
	{
	    $content .="<div class='more_story'>
					  <div class='user_info_title'>".$story_author_name."的更多故事</div>
					  <ul id='more_story_list' class='sto_cover_list'>";
		while ($story_item = mysql_fetch_array($more_result))
		{
		  $post_author = $story_item['post_author'];
		  $post_pic_url = $story_item['post_pic_url'];
		  if($post_pic_url == '')
		  {
		    $post_pic_url = '/img/event_dft.jpg';
		  }
		  $post_title = $story_item['post_title'];
		  $post_date = $story_item['post_date'];
		  $temp_array = explode(" ", $story_item['post_date']);
		  $post_date = $temp_array[0];
		  $post_link = "/user/".$post_author."/".$story_item['ID'];
		  $post_link = htmlspecialchars($post_link);
		  $content .= "<li>
							  <div class='story_wrap'>	
								<a href='".$post_link."'>
								  <img class='cover' src='".$post_pic_url."' alt=''/>
								</a>
								<a class='title_wrap' href='".$post_link."'>
								  <span class='title'>".$post_title."</span>
								</a>
							  </div>
							  <div class='story_meta'>
								<span>
								  <a class='meta_date'>".$post_date."</a>
								  <img src='".$user_profile_img."' alt='".$story_author_name."'/>
								  <a class='meta_author' href='/user/".$post_author."'>".$story_author_name."</a>
								</span>
							  </div>
							</li>";
		}
		$content .="</ul><a href='/user/".$story_author."'>访问".$story_author_name."的主页 &raquo;</a></div>";
	}
	
	$content .="</div></div>";
	echo $content;
	echo "<script type='text/javascript' language='javascript'>
		   document.title = '$story_title'+' - '+'$story_author_name'+' - 口立方';
		</script>";
}

else if(isset($_GET['user_id']) && isset($_GET['post_id']) && isset($_GET['action']))
{
	$story_id = $_GET['post_id'];
	$story_action = $_GET['action'];
	if(0 == strcmp($story_action, 'edit'))
	{
	    header("location: /user/".$user_id."/".$story_id."/edit");
	}
	else if(0 == strcmp($story_action, 'publish'))
	{
	  $result=$DB->query("update ".$db_prefix."posts set post_status='Published'  WHERE ID='".$story_id."'");
	    header("location: /user/".$user_id."/".$story_id);
	}
	else
	{
	  throw new Exception('Undefined story action.');
	}
}

else if(isset($_GET['user_id']) && !isset($_GET['post_id']))
{
  $tbl_name="story_posts";
  // How many adjacent pages should be shown on each side?
  $adjacents = 3;
  $userresult = $DB->fetch_one_array("SELECT username, photo, intro FROM ".$db_prefix."user where id='".$user_id."'");
  if(!$userresult)
  {
    go("/","您要查看的用户不存在",2);
    exit;
  }
  $username = $userresult['username'];
  
  $user_profile_img = $userresult['photo'];
  if($user_profile_img == '')
  {
	$user_profile_img = '/img/douban_user_dft.jpg';
  }
  
  if(substr($userresult['photo'], 0, 4) == 'http')
  {
    if(substr($userresult['photo'], 11, 4) == 'sina')
    {
	  $pattern = "/(\d+)\/50\/(\d+)/";
	  $user_avatar_img = preg_replace($pattern,"$1/180/$2",$userresult['photo']);
    }
    else
    {
	  $pattern = "/50$/";
	  $user_avatar_img = preg_replace($pattern,'100',$userresult['photo']);
    }
  }
  else
  {
	$user_avatar_img = $user_profile_img;
  }
  $following_list = getFollowing($user_id);
  $follower_list=getFollower($user_id);
  
  if($self_flag)
  {
    $query = "SELECT COUNT(*) as num FROM $tbl_name where post_author='".$user_id."'";
  }
  else
  {
    $query = "SELECT COUNT(*) as num FROM $tbl_name where post_author='".$user_id."' and post_status = 'Published'";
  }
  $total_pages = mysql_fetch_array(mysql_query($query));
  $total_pages = $total_pages[num];
  
  $story_content = "<div id='userstory_container' class='inner'>
					  <div class='userinfo_wrapper'>
						<div class='avatar'><a href='/user/".$user_id."'><img width='80px' height='80px' src='".$user_avatar_img."' alt='".$username."' /></a></div>
						<div class='misc_wrapper'>
						  <div class='user_name'><a href='/user/".$user_id."'><span>".$username."</span></a></div>
						  <div class='account_count'>
							<span>粉丝:</span><span class='fans_count'>".sizeof($follower_list)."</span>
							<span>关注:</span><span class='follow_count'>".sizeof($following_list)."</span>
							<span>故事:".$total_pages."</span>
						  </div>";
					  
  if($follow_flag)
  {
	  $login_user_id = $_SESSION['uid'];
	  
	  $query="select * from ".$db_prefix."follow where user_id=".$_SESSION[uid]." and follow_id=".$user_id;
      $relationresult=$DB->query($query);
      $num=$DB->num_rows($relationresult);
	  if($num > 0)
	  {
	    $story_content .="<a id='".$login_user_id."_sep_".$user_id."_flag' class='large green awesome follow' href='#'>已关注</a><a id='".$login_user_id."_sep_".$user_id."' class='large green awesome follow' href='#' style='display:none;'>关注</a>";
	  }
	  else
	  {
	    $story_content .="<a id='".$login_user_id."_sep_".$user_id."' class='large green awesome follow' href='#'>关注</a><a id='".$login_user_id."_sep_".$user_id."_flag' class='large green awesome follow' href='#' style='display:none;'>已关注</a>";
	  }
  }
  
  $story_content .="</div><div id='user_intro'>".nl2br($userresult['intro'])."</div></div><div class='userstory_list'>";
  
  
  if(0 == $total_pages)
  {
    $story_content.="<div style='height:30px;'></div>";
	if($self_flag)
	{
	  $story_content.="<h4 class='text'>你可以用口立方报道新闻，追踪网络热点事件，汇总美食，旅游，时尚周边信息，写书评影评，等等～</h4><a class='large green awesome' href='/create'>开始创建 &raquo;</a><div class='footer_spacer'></div></div></div>";
	}
	else
	{
	  $story_content.="<div class='footer_spacer'></div></div></div>";
	}
  }
  else
  {	
	$targetpage = "/user/".$user_id; 
	$limit = 12; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	if($self_flag)
	{
	  $sql = "SELECT * FROM $tbl_name where post_author='".$user_id."'LIMIT $start, $limit";
	}
	else
	{
	  $sql = "SELECT * FROM $tbl_name where post_author='".$user_id."' and post_status = 'Published' LIMIT $start, $limit";
	}
	$result = mysql_query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);	
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage/page=$prev\">« 前页</a>";
		else
			$pagination.= "<span class=\"disabled\">« 前页</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage/page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage/page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage/page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage/page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage/page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage/page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage/page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage/page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage/page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage/page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage/page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage/page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage/page=$next\">后页 »</a>";
		else
			$pagination.= "<span class=\"disabled\">后页 »</span>";
		$pagination.= "</div>\n";		
	}
  
	  $story_content .="<ul class='sto_cover_list'>";
	  while ($story_item = mysql_fetch_array($result))
	  {
		//printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
		$post_id = $story_item['ID'];
		$post_title = $story_item['post_title'];
		$post_pic_url = $story_item['post_pic_url'];
		if($post_pic_url == '')
		{
	      $post_pic_url = '/img/event_dft.jpg';
		}
		$post_status = $story_item['post_status'];
		if(0 == strcmp($post_status, 'Published'))
		{
		  $post_status_txt = '已发布';
		  $icon_type = 'publish_icon';
		}
		else
		{
		  $post_status_txt = '草稿';
		  $icon_type = 'draft_icon';
		}
		$post_date = $story_item['post_date'];
		$temp_array = explode(" ", $story_item['post_date']);
		$post_date = $temp_array[0];
		$post_link = "/user/".$user_id."/".$story_item['ID'];
		$post_link = htmlspecialchars($post_link);
		$story_content .="<li>
							<div class='story_wrap'>
							  <a href='".$post_link."'>
								<img class='cover' src='".$post_pic_url."' alt='' />
							  </a>
							  <a class='title_wrap' href='".$post_link."'>
								<span class='title'>".$post_title."</span>
							  </a>";
		if($self_flag)
		{
		  $story_content .="<div class='editable'>
		  <div class='actions'>
			<a id='".$post_id."_delete' class='icon delete png_fix' title='删除' href='#'></a>
			<a class='icon edit png_fix' title='编辑' href='/user/".$user_id."/".$post_id."/edit'></a>
		  </div>
		  <div class='status'>
			<div class='".$post_status."'>
			  <div class='".$icon_type." png_fix'></div>
			  <span>".$post_status_txt."</span>
			</div>
		  </div>
		  <div class='clear'></div>
		  </div>";
		}
		$story_content .="</div><div class='story_meta'><span><a class='meta_date'>".$post_date."</a><img src='".$user_profile_img."' alt='".$username."' /><a class='meta_author'>".$username."</a></span></div></li>";
	  }
	  $story_content .="</ul></div>".$pagination."</div>";
  }
  echo $story_content;
  echo "<script type='text/javascript' language='javascript'>
		  document.title = '$username'+'的个人主页'+' - 口立方';
		</script>";
}

else
{
  if(!$login_status)
  {
    header("location: /accounts/login"); 
    exit;
  }
}
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>

<script type='text/javascript' src='/js/userstory.js'></script>
<script type="text/javascript" src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2417356638" charset="utf-8"></script>
<script type="text/javascript">var jiathis_config = {data_track_clickback:true};</script>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js?uid=1542042" charset="utf-8"></script>
</body>
</html>
