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
		debugger;
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
		    debugger;
			
			var story_content ="<div id='publish_container'><div id='story_header' style='margin:0; padding:0;'><div style='float:right; padding: 0 10px 0 0'><img src='"+data.pic+"' style='width:60px; height:60px;' /></div><div id='story_meta' style='margin-top:10px;'><div class='story_title'>"+data.title+"</div><div class='story_author'>by<a href='http://koulifang.com/member/user.php?user_id="+data.id+"'>"+data.author+"</a>, "+data.time+"</div><div class='story_sum'>"+data.summary+"</div></div><div class='tool_wrapper'><div class='story_share'><div id='ckepop'><span class='jiathis_txt'>分享到：</span><a class='jiathis_button_qzone'></a><a class='jiathis_button_tsina'></a><a class='jiathis_button_tqq'></a><a class='jiathis_button_renren'></a><a class='jiathis_button_kaixin001'></a><a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a><a class='jiathis_counter_style'></a></div><div id='story_embed'><a href='#' id='embed_a'>嵌入故事<span class='arrow_down'></span><span class='arrow_up'></span></a></div></div><div id='embed_bar'><span style='margin-left:20px;'>复制嵌入代码:</span><span><input type='text' class='sto_embed' value='"+data.embed+"' size='68' /></span><a title='如何嵌入' class='embed_how'></a></div></div></div><ul id='weibo_ul' style='padding:0;'>"+data.content+"</ul><div style='display: block; padding:0 10px 0 5px; text-align:right;'>Powered by <a name='poweredby' target='_blank' href='http://koulifang.com'>口立方</a></div></div>";
			
			this.widgetNode = jQuery(data.content);
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