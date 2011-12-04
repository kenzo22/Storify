;
(function() {

	if (typeof Koulifang == 'object' && Koulifang.widget) {
		//debug('Koulifang initializer already started', Koulifang.widget.stories);
		getStories();
		return;
	}

  /* Koulifang Widget */
	var jQuery;

  if(typeof Koulifang!='object') {
    Koulifang = {};
  }

	Koulifang.widget = {
		stories: []
	};

	Koulifang.ready = false;
	Koulifang.permalinks = new Array;

	DEBUG = (typeof DEBUG == 'undefined') ? true : DEBUG;
	ENV = (typeof ENV == 'undefined') ? 'production' : ENV;

/******** Load jQuery if not present *********/
	if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.5.2') {
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type", "text/javascript");
		script_tag.setAttribute("src", "http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js");
		script_tag.onload = scriptLoadHandler;
		script_tag.onreadystatechange = function() { // Same thing but for IE
			if (this.readyState == 'complete' || this.readyState == 'loaded') {
				scriptLoadHandler();
			}
		};
		// Try to find the head, otherwise default to the documentElement
		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
	} else {
		// The jQuery version on the window is the one we want to use
		KOULIFANG_JQUERY = window.jQuery;
		jQuery = KOULIFANG_JQUERY;
		main();
	}
	
	/******** Called once jQuery has loaded ******/
	function scriptLoadHandler() {
		// Restore $ and window.jQuery to their previous values and store the
		// new jQuery in our local jQuery variable
		jQuery = window.jQuery.noConflict(true);
		KOULIFANG_JQUERY = jQuery;
		// Call our main function
		main();
	}
	
	function require(jsfile, callback) {
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type", "text/javascript");
		script_tag.setAttribute("src", jsfile);
		script_tag.setAttribute("charset", "utf-8");
		if (typeof callback == 'function') {
			script_tag.onload = callback;
			script_tag.onreadystatechange = function() { // IE
				if (this.readyState == 'complete' || this.readyState == 'loaded') {
					callback();
				}
			};
		} (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
	}
	
	function setenv()
	{
	  KOULIFANG_BASE_URL = 'http://www.koulifang.com';
	}
	
	function debug(msg, obj) {
    if(window.location.href.indexOf("DEBUG") == -1 && !window.location.href.match(/localhost.koulifang.com/)) return false;
		if (!obj) obj = '';
		if (window.console && console && console.log) console.log('koulifangWidget> ' + msg, obj);
	}
	
	/******** Our main function ********/
	function main() {
		var jQuery = KOULIFANG_JQUERY;
		//debug('Using jQuery ' + jQuery.fn.jquery);

		setenv();

		//jQuery('.stfyhtml').hide();
		//jQuery('.storify_html').hide(); // hiding the html export to wordpress
		jQuery('head').append('<link rel="stylesheet" href="' + KOULIFANG_BASE_URL + '/css/widget.css' + '" type="text/css" />');
		//Koulifang.ready = true;
		//getStories();
		require('http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2417356638', function() {
				Koulifang.ready = true;
				getStories();
		});
	}
	
	function queryStringToObject(queryString)
	{
	  if(!queryString) return {};
      if(queryString.indexOf('=')===-1) return {};
    
      var paramsArray = queryString.split('&');
      var params = {};
      for (var i=0, len=paramsArray.length; i < len; i++) {
        var p = paramsArray[i];
        var r = p.split('=');
        if(r.length>1)
        params[r[0]] = r[1];
      };
      return params;
	}
	
	function getStories() {
		if (!Koulifang.ready) return;
		var permalink, query, params, scriptsrc;

		jQuery = jQuery || KOULIFANG_JQUERY;
		jQuery('script[src*="' + KOULIFANG_BASE_URL + '/user"]').each(function() {
		  var scriptsrc = jQuery(this).attr('src');
			if (scriptsrc.indexOf('/public/') == -1 && jQuery.inArray(scriptsrc, Koulifang.permalinks) == -1) {
				permalink = scriptsrc.substr(0, scriptsrc.lastIndexOf('.'));
				query = scriptsrc.substr(scriptsrc.lastIndexOf('?')+1);
				if(query) {
				  params = queryStringToObject(query);
				}
				var slug = permalink.substr(permalink.lastIndexOf('/') + 1);
				if (typeof Koulifang.widget.stories[slug] == 'undefined') {
					Koulifang.permalinks.push(permalink);
					Koulifang.widget.stories[slug] = new Koulifang.Story(permalink, params);
				}
			}
		});
		/*debugger;
		permalink = "http://koulifang.com/22/story";
		params = {};
		var slug = "story";
		Koulifang.permalinks.push(permalink);
		Koulifang.widget.stories[slug] = new Koulifang.Story(permalink, params);*/
	}
	
	function jsonpCallback(data){

    }
	
	Koulifang.Story = function(permalink, options) {
		this.permalink = permalink;
		var link_array = permalink.split('/');
		var link_array_len = link_array.length;
		var identifier = link_array[link_array_len-2];
        var embed_name = link_array[link_array_len-1];
	    this.slug = embed_name;
        this.nodeId = 'sfywdgt_StorifyWidget_' + this.slug.replace(/_|\-/g,'');
	    var self = this,
			story = {},
			html = '',
			elements;
		var getData = {id: identifier, name: embed_name};
		
		jQuery.ajax({
          url: 'http://www.koulifang.com/member/fetchjson.php',
		  data: getData,
		  dataType: 'jsonp',
		  jsonp: 'callback',
		  jsonpCallback: 'jsonpCallback',
          success: function(data) {
			var tags = data.tags;
			var content_obj = data.content_array;
			var tag_content = "";
			var content = "";
			for(var o in tags){
			  tag_content +="<a class='tag_item' href='/topic/topic.php?topic="+tags[o]+"'>"+tags[o]+"</a>";
			}
			if(tag_content != ""){
			  tag_content = "<div class='story_tag'>标签:"+tag_content+"</div>";
			}
			
			for(var sub in content_obj)
			{
			  var sub_item = content_obj[sub];
			  switch(sub_item.type)
			  {
			    case 'weibo':
				  if(sub_item.text == '')
				  {
				    content +="<li class='weibo_drop sina' id='w_"+sub_item.per_id+"'><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被删除</span></div></div></li>";
				  }
				  else
				  {
				    var img_content = '';
					var retweet_img_content = '';
					if(sub_item.img != '')
					{
					  img_content +="<div class='weibo_img_drop'><img src='"+sub_item.img+"' width='280px;' /></div>";
					}
					if(sub_item.retweet_img != '')
					{
					  retweet_img_content +="<div class='weibo_retweet_img_drop'><img src='"+sub_item.retweet_img+"' width='280px;' /></div>";
					}
					content +="<li class='weibo_drop sina' id='w_"+sub_item.per_id+"'><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>"+sub_item.text+"</span>"+retweet_img_content+img_content+"</div><div class='story_signature'><span class='float_r'><a href='http://weibo.com/"+sub_item.uid+"' target='_blank'><img class='profile_img_drop' src='"+sub_item.u_profile+"' alt='"+sub_item.u_name+"' border=0 /></a></span><div class='signature_text'><div class='text_wrapper'><span ><a class='weibo_from_drop' href='http://weibo.com/"+sub_item.uid+"' target='_blank'>"+sub_item.u_name+"</a></span></div><div class='weibo_date_drop'>"+sub_item.time+"</div></div></div></div></li>";
				  }
				  break;
				case 'tweibo':
				  if(sub_item.img != '')
				  {
					img_content +="<div class='weibo_img_drop'><img src='"+sub_item.img+"/240' /></div>";
				  }
				  if(sub_item.retweet_img != '')
				  {
				    retweet_img_content +="<div class='weibo_retweet_img_drop'><img src='"+sub_item.retweet_img+"/240' /></div>";
				  }
				  content +="<li id='t_"+sub_item.per_id+"' class='weibo_drop tencent'><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>"+sub_item.text+"</span>"+retweet_img_content+img_content+"</div><div class='story_signature'><span class='float_r'><a href='http://t.qq.com/"+sub_item.u_name+"' target='_blank'><img class='profile_img_drop' src='"+sub_item.u_profile+"' alt='"+sub_item.u_nick+"' border=0 /></a></span><div class='signature_text'><div class='text_wrapper'><span><a class='weibo_from_drop' href='http://t.qq.com/"+sub_item.u_name+"' target='_blank'>"+sub_item.u_nick+"</a></span></div><div class='weibo_date_drop'>"+sub_item.time+"</div></div></div></div></li>";
				  break;
				case 'douban_event':
				  content +="<li class='douban_drop douban' id='d_"+sub_item.per_id+"'><div class='douban_wrapper'><div class='content_wrapper'><div class='event_summary'>"+sub_item.event_summary+"</div><div class='event_wrapper'><a href='"+sub_item.event_link+"' target='_blank'><img class='item_img float_l' src='"+sub_item.event_pic+"' /></a><div class='item_meta'><div class='event_title'>活动：<a href='"+sub_item.event_link+"' target='_blank'>"+sub_item.event_title+"</a></div><div class='event_initiator'>发起人：<a href='"+sub_item.event_initiator_link+"' target='_blank'>"+sub_item.event_initiator_name+"</a></div><div class='start_time'>"+sub_item.start_time+"</div><div class='end_time'>"+sub_item.end_time+"</div><div class='event_city'>"+sub_item.event_city+"</div><div class='event_location'>"+sub_item.event_location+"</div></div></div></div><div class='douban_signature'><span class='float_r'><a href='"+sub_item.event_initiator_link+"' target='_blank'><img class='profile_img_drop' src='"+sub_item.event_initiator_pic+"' alt='"+sub_item.event_initiator_name+"' border=0 /></a></span><div class='signature_text'><div class='text_wrapper'><span ><a class='douban_from_drop' href='"+sub_item.event_initiator_link+"' target='_blank'>"+sub_item.event_initiator_name+"</a></span></div><div class='douban_date_drop'></div></div> </div></div></li>";
				  break;
				case 'douban_review':
				  content +="<li class='douban_drop douban' id='d_"+sub_item.per_id+"'><div class='douban_wrapper'><div class='content_wrapper'><div><div class='comment_title'>"+sub_item.comment_title+"</div><div class='comment_summary'>"+sub_item.comment_summary+"<a href='"+sub_item.comment_link+"' target='_blank'>[查看评论全文]</a></div></div><div class='item_info'><a href='"+sub_item.item_link+"' target='_blank'><img class='item_img' src='"+sub_item.item_pic+"' /></a><div class='item_meta'><div><a class='item_title' href='"+sub_item.item_link+"' target='_blank'>"+sub_item.item_title+"</a></div><div class='item_author'>"+sub_item.item_author+"</div><div class='item_date'>"+sub_item.item_date+"</div><div class=item_rating>"+sub_item.comment_author+"评分:"+sub_item.rating+"</div><div class='average_rating'>豆瓣评分:"+sub_item.average_rating+"&nbsp&nbsp&nbsp&nbsp共"+sub_item.num_raters+"人参与投票</div></div></div></div><div class='douban_signature'><span class='float_r'><a href='"+sub_item.comment_author_link+"' target='_blank'><img class='profile_img_drop' src='"+sub_item.comment_author_pic+"' alt='"+sub_item.comment_author+"' border=0 /></a></span><div class='signature_text'><div class='text_wrapper'><span ><a class='douban_from' href='"+sub_item.comment_author_link+"' target='_blank'>"+sub_item.comment_author+"</a></span></div><div class='douban_date_drop'>"+sub_item.comment_date+"</div></div> </div></div></li>";
				  break;
				case 'douban_item':
				  content +="<li class='douban_drop douban' id='d_"+sub_item.per_id+"'><div class='douban_wrapper'><div class='content_wrapper'><div class='item_info'><a href='"+sub_item.item_link+"' target='_blank'><img class='item_img' src='"+sub_item.item_pic+"' /></a><div class='item_meta'><div><a class='item_title' href='"+sub_item.item_link+"' target='_blank'>"+sub_item.item_title+"</a></div><div class='item_author'>"+sub_item.item_author+"</div><div class='item_date'>"+sub_item.item_date+"</div><div class='average_rating'>豆瓣评分:"+sub_item.average_rating+"&nbsp&nbsp&nbsp&nbsp共"+sub_item.num_raters+"人参与投票</div></div></div></div><div class='douban_sig_logo'></div></div></li>";
				  break;
				case 'comment':
				  content +="<li class='textElement'><div class='commentBox'>"+sub_item.text+"</div></li>";
				  break;
				case 'photo':
				  content +="<li class='photo_element'><div class='yupoo_wrapper'><a target='_blank' href='"+sub_item.photo_link+"'><img src='"+sub_item.photo_url+"'/></a><div><a class='pic_title' target='_blank' href='"+sub_item.photo_link+"'>"+sub_item.title+"</a></div><div><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/"+sub_item.author+"'>"+sub_item.author_nic+"</a></div><div class='yupoo_sign'></div></div></li>";
				  break;
				case 'video':
				  content +="<li class='video_element'><div><a class='videoTitle' target='_blank' href='"+sub_item.url+"'>"+sub_item.title+"</a></div><div class='embed'><embed src='"+sub_item.src+"' quality='high' width='420' height='340' align='middle' allowscriptaccess='always' allowfullscreen='true' mode='transparent' type='application/x-shockwave-flash' wmode='opaque'></embed></div></li>";
				  break;
				default:
				  break;
			  }
			}
			var embed_code = "<script src=\"http://www.koulifang.com/user/"+data.id+"/"+data.embed+".js\"></script>";
			
			var story_content ="<div id='publish_container'><div id='story_header'><div id='story_img'><img src='"+data.pic+"' alt='' /></div><div id='story_meta'><div class='story_title'>"+data.title+"</div><div class='story_author'>by<a href='http://koulifang.com/member/user.php?user_id="+data.id+"'>"+data.author+"</a>, "+data.time+"</div><div class='story_sum'>"+data.summary+"</div>"+tag_content+"</div><div class='tool_wrapper'><div class='story_share'><div id='ckepop'><span class='jiathis_txt'>分享到：</span><a class='jiathis_button_qzone'></a><a class='jiathis_button_tsina'></a><a class='jiathis_button_tqq'></a><a class='jiathis_button_renren'></a><a class='jiathis_button_kaixin001'></a><a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a><a class='jiathis_counter_style'></a></div><div id='story_embed'><a href='#' id='embed_a'>嵌入故事<span class='arrow_down'></span><span class='arrow_up'></span></a></div></div><div id='embed_bar'><span>复制嵌入代码:</span><span><input type='text' class='sto_embed' value='"+embed_code+"' size='68' /></span><a title='如何嵌入' class='embed_how' href='http://www.koulifang.com/user/3/4' target='_blank'></a></div></div></div><ul id='weibo_ul'>"+content+"</ul><div class='kou_signature'><span>Powered by</span><a title='口立方' name='poweredby' target='_blank' href='http://koulifang.com'></a></div></div>";
			
			this.widgetNode = jQuery(story_content);
			jQuery('script[src^="' + self.permalink + '"]').first().after(this.widgetNode);
			jQuery('#embed_a').toggle(function(e){
				  e.preventDefault();
				  jQuery('#embed_bar').slideDown("slow");
				  jQuery('.arrow_up').css('display', 'inline-block');
				  jQuery('.arrow_down').hide();
				  jQuery('#embed_bar span .sto_embed').select();
				  return false;
				},
				function(e){
				  e.preventDefault();
				  jQuery('#embed_bar').slideUp("slow");
				  jQuery('.arrow_down').show();
				  jQuery('.arrow_up').hide();
				  jQuery('#embed_bar span .sto_embed').select();
				  return false;
				});
				
		    jQuery('.sto_embed').click(function(){
			  jQuery(this).select();
			});
			
			jQuery('#weibo_ul li.sina').each(function()
			{
				var id_val = jQuery(this).attr('id');
				WB2.anyWhere(function(W){
				W.widget.hoverCard({
					id: id_val,
					search: true
					}); 
				});
			});
			
			require('http://v2.jiathis.com/code/jia.js', function() {
			});
          }
        });	
	}
	
})();